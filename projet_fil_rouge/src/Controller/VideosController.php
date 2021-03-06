<?php

namespace App\Controller;

use App\Entity\Ressources;
use App\Entity\Videos;
use App\Form\VideosType;
use App\Repository\FavorisRepository;
use App\Repository\RessourcesRepository;
use App\Repository\UsersRepository;
use App\Repository\VideosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


/**
 * @Route("/videos")
 */
class VideosController extends AbstractController
{
    /**
     * @Route("/", name="videos_index", methods={"GET"})
     */
    public function index(VideosRepository $videosRepository, Security $security): Response
    {
        $videos = $videosRepository->findBy(["user" => $security->getUser()->getId()]);
        return $this->render('videos/index.html.twig', [
            'videos' => $videos,
        ]);
    }

    /**
     * @Route("/new", name="videos_new", methods={"GET","POST"})
     */
    public function new(Request $request, Security $security, UsersRepository $usersRepository): Response
    {
        $video = new Videos();
        $form = $this->createForm(VideosType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $videos = $form->get('mediaFile')->getData();


            foreach ($videos as $item) {
                if($item->getMimeType() != "application/mp4" && $item->getMimeType() != "application/avi" && $item->getMimeType() != "video/mp4" && $item->getMimeType() != "video/avi"){
                    $this->addFlash('danger', 'Veuillez insérer une video au format "mp4" ou "avi"');

                return $this->redirectToRoute('videos_new'); ;
                }

                $file = md5(uniqid()) . '.' . $item->guessExtension();

                // On copie le fichier dans le dossier uploads
                $item->move(
                    $this->getParameter('videos_directory'),
                    $file
                );

                $video->setMediaFile($file);
            }

            $ressource = new Ressources();
            $ressource->setUser($security->getUser())
                ->setTitle($video->getTitre())
                ->setVideo($video)
                ->setPublished(false);
            $entityManager = $this->getDoctrine()->getManager();
            $video->setUser($security->getUser());
            $video->setPublished(false);
            foreach ($video->getCategory() as $cat) {
                $ressource->addCategory($cat);
            };
            $entityManager->persist($video);
            $entityManager->persist($ressource);
            $entityManager->flush();

            return $this->redirectToRoute('videos_index');
        }

        return $this->render('videos/new.html.twig', [
            'video' => $video,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="videos_show", methods={"GET"})
     */
    public function show($id, Videos $video, RessourcesRepository $ressourcesRepository, Security $security, FavorisRepository $favorisRepository): Response
    {

        $ressource = $ressourcesRepository->findOneBy(["video" => $id]);
        $userCo = $security->getUser();
        $favoList = $favorisRepository->findByUser($userCo);

        return $this->render('videos/show.html.twig', [
            'video' => $video,
            'ressourceId' => $ressource->getId(),
            "favoList" => $favoList
        ]);
    }

    /**
     * @Route("/{id}/edit", name="videos_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Videos $video, RessourcesRepository $ressourcesRepository): Response
    {
        $form = $this->createForm(VideosType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $item = $video->getMediaFile();

            if ($this->getParameter('videos_directory') . '/' . $item != true) {
                unlink($this->getParameter('videos_directory') . '/' . $item);
            }

            $files = $form->get('mediaFile')->getData();


            foreach ($files as $file) {

                if($file->getMimeType() != "application/mp4" && $file->getMimeType() != "application/avi" && $file->getMimeType() != "video/mp4" && $file->getMimeType() != "video/avi"){
                    $this->addFlash('danger', 'Veuillez insérer une image au format "mp4" ou "avi"');

                return $this->redirectToRoute('videos_edit'); ;
                }
                // On génère un nouveau nom de fichier
                $newVideo = md5(uniqid()) . '.' . $file->guessExtension();

                // On copie le fichier dans le dossier uploads
                $file->move(
                    $this->getParameter('videos_directory'),
                    $newVideo
                );

                // On crée l'image dans la base de données

                $video->setMediaFile($newVideo);
            }


            $ressource = $ressourcesRepository->findOneBy(["video" => $video->getId()]);
            $ressource->setUpdatedAt();
            $ressource->setTitle($video->getTitre());
            foreach ($video->getCategory() as $cat) {
                $ressource->addCategory($cat);
            };
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('videos_index');
        }

        return $this->render('videos/edit.html.twig', [
            'video' => $video,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="videos_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Videos $video, RessourcesRepository $ressourcesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $video->getId(), $request->request->get('_token'))) {

            $entityManager = $this->getDoctrine()->getManager();
            $ressource = $ressourcesRepository->findOneBy(["video" => $video->getId()]);
            $entityManager->remove($ressource);
            $entityManager->remove($video);
            $entityManager->flush();

        }

        return $this->redirectToRoute('videos_index');
    }
}
