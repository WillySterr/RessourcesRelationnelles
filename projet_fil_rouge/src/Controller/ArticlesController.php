<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Ressources;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use App\Repository\CommentsRepository;
use App\Repository\RessourcesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/articles")
 */
class ArticlesController extends AbstractController
{
    /**
     * @Route("/", name="articles_index", methods={"GET"})
     */
    public function index(ArticlesRepository $articlesRepository, Security $security): Response
    {
        $articles = $articlesRepository->findBy(["user" => $security->getUser()->getId()]);
        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/new", name="articles_new", methods={"GET","POST"})
     */
    public function new(Request $request, Security $security): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // On récupère les photos transmises
            $images = $form->get('photo')->getData();
            $videos = $form->get('video')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                $article->setPhoto($fichier);
            }

            foreach ($videos as $video) {
                $file = md5(uniqid()) . '.' . $video->guessExtension();

                // On copie le fichier dans le dossier uploads
                $video->move(
                    $this->getParameter('videos_directory'),
                    $file
                );

                $article->setVideo($file);
            }

            // On crée l'image dans la base de données






            $ressource = new Ressources();
            $ressource->setUser($security->getUser())
                ->setArticle($article)
                ->setTitle($article->getTitre())
                ->setPublished(false);
            foreach ($article->getCategory() as $cat) {
                $ressource->addCategory($cat);
            };
            $article->setUser($security->getUser())
                ->setPublished(false);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->persist($ressource);
            $entityManager->flush();

            return $this->redirectToRoute('articles_index');
        }

        return $this->render('articles/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="articles_show", methods={"GET"})
     */
    public function show($id, Articles $article, RessourcesRepository $ressourcesRepository, CommentsRepository $commentsRepository): Response
    {

        //Récupérer la ressource

        $ressource = $ressourcesRepository->findOneBy(["article" => $id]);

        //Récupérer les commentaires de la ressource

        $comments = $commentsRepository->findBy(["ressource" => $ressource->getId()]);


        return $this->render('articles/show.html.twig', [
            'article' => $article,
            'comments' => $comments,
            'ressourceId' => $ressource->getId()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="articles_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Articles $article, RessourcesRepository $ressourcesRepository): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère le nom de l'image
            $nom = $article->getPhoto();
            $video = $article->getVideo();
            // On supprime le fichier

            if ($this->getParameter('videos_directory') . '/' . $video != true) {
                unlink($this->getParameter('videos_directory') . '/' . $video);
            }

            if ($this->getParameter('images_directory') . '/' . $nom != true) {
                unlink($this->getParameter('images_directory') . '/' . $nom);
            }
            // On récupère les photos transmises
            $images = $form->get('photo')->getData();
            $files = $form->get('video')->getData();


            foreach ($files as $file) {
                // On génère un nouveau nom de fichier
                $newVideo = md5(uniqid()) . '.' . $file->guessExtension();

                // On copie le fichier dans le dossier uploads
                $file->move(
                    $this->getParameter('images_directory'),
                    $newVideo
                );

                // On crée l'image dans la base de données

                $article->setVideo($newVideo);
            }


            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données

                $article->setPhoto($fichier);
            }


            $ressource = $ressourcesRepository->findOneBy(["article" => $article->getId()]);
            $ressource->setTitle($article->getTitre());
            $ressource->setUpdatedAt();
            foreach ($article->getCategory() as $cat) {
                $ressource->addCategory($cat);
            };
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('articles_index');
        }
        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="articles_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Articles $article, RessourcesRepository $ressourcesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            // On récupère le nom de l'image
            $photo = $article->getPhoto();
            $video = $article->getVideo();
            // On supprime le fichier
            unlink($this->getParameter('images_directory') . '/' . $photo);
            unlink($this->getParameter('videos_directory') . '/' . $video);

            $entityManager = $this->getDoctrine()->getManager();
            $ressource = $ressourcesRepository->findOneBy(["article" => $article->getId()]);
            $entityManager->remove($article);
            $entityManager->remove($ressource);
            $entityManager->flush();
        }

        return $this->redirectToRoute('articles_index');
    }

    /**
     * @Route("/article/comments", name="article_comments")
     */
    public function getArticleComments(Request $request, CommentsRepository $commentsRepository)
    {

        // On récupère les données de la requete ajax

        $data = json_decode($request->getContent(), true);

        // On récupère l'id de la ressource

        $ressourceId = $data['idRessource'];

        // On récupère les commentaires de la ressource

        $comments = $commentsRepository->findBy(["ressource" => $ressourceId]);

        // On construit le tableau de commentaires

        $arrayComments = [];

        foreach ($comments as $comment) {
            $arrayComments[] = array(
                'id' => $comment->getId(),
                'user' => [
                    "id" => $comment->getUser()->getId(),
                    "lastName" => $comment->getUser()->getLastName(),
                    "firstName" => $comment->getUser()->getFirstName()
                ],
                'contenu' => $comment->getContenu(),
                'date' => $comment->getUpdatedAt() ? $comment->getUpdatedAt() : $comment->getCreatedAt()
            );
        }
        $response = new Response(json_encode($arrayComments));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
