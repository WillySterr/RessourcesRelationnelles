<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Ressources;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
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
        $articles = $articlesRepository->findBy(["id" => $security->getUser()->getId()]);
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
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                $article->setPhoto($fichier);

            }

            foreach ($videos as $video){
                $file = md5(uniqid()).'.'.$video->guessExtension();

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
    public function show(Articles $article): Response
    {
        return $this->render('articles/show.html.twig', [
            'article' => $article,
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

            if ($this->getParameter('videos_directory').'/'.$video != true){
                unlink($this->getParameter('videos_directory').'/'.$video);
            }

            if ($this->getParameter('images_directory').'/'.$nom != true){
                unlink($this->getParameter('images_directory').'/'.$nom);
            }
            // On récupère les photos transmises
            $images = $form->get('photo')->getData();
            $files = $form->get('video')->getData();


            foreach($files as $file){
                // On génère un nouveau nom de fichier
                $newVideo = md5(uniqid()).'.'.$file->guessExtension();

                // On copie le fichier dans le dossier uploads
                $file->move(
                    $this->getParameter('images_directory'),
                    $newVideo
                );

                // On crée l'image dans la base de données

                $article->setVideo($newVideo);
            }


            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();

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
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            // On récupère le nom de l'image
            $photo = $article->getPhoto();
            $video = $article->getVideo();
            // On supprime le fichier
            unlink($this->getParameter('images_directory').'/'.$photo);
            unlink($this->getParameter('videos_directory').'/'.$video);

            $entityManager = $this->getDoctrine()->getManager();
            $ressource = $ressourcesRepository->findOneBy(["article" => $article->getId()]);
            $entityManager->remove($article);
            $entityManager->remove($ressource);
            $entityManager->flush();
        }

        return $this->redirectToRoute('articles_index');
    }
}
