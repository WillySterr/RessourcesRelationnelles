<?php

namespace App\Controller;

use App\Entity\Ressources;
use App\Entity\Videos;
use App\Form\VideosType;
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

            $videos = $form->get('video')->getData();


            foreach ($videos as $item) {
                $file = md5(uniqid()) . '.' . $item->guessExtension();

                // On copie le fichier dans le dossier uploads
                $item->move(
                    $this->getParameter('videos_directory'),
                    $file
                );

                $video->setVideo($file);
            }

            $ressource = new Ressources();
            $ressource->setUser($security->getUser())
                ->setTitle($video->getTitre())
                ->setVideo($video)
                ->setPublished(false);
            $entityManager = $this->getDoctrine()->getManager();
            $video->setUser($security->getUser());
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
    public function show(Videos $video): Response
    {
        return $this->render('videos/show.html.twig', [
            'video' => $video,
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

            $item = $video->getVideo();

            if ($this->getParameter('videos_directory') . '/' . $item != true) {
                unlink($this->getParameter('videos_directory') . '/' . $item);
            }

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

                $video->setVideo($newVideo);
            }


            $ressource = $ressourcesRepository->findOneBy(["video" => $video->getId()]);
            $ressource->setUpdatedAt();
            $ressource->setTitle($video->getTitre());
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
    public function delete(Request $request, Videos $video): Response
    {
        if ($this->isCsrfTokenValid('delete' . $video->getId(), $request->request->get('_token'))) {
            $file = $video->getVideo();
            unlink($this->getParameter('videos_directory') . '/' . $file);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($video);
            $entityManager->flush();
        }

        return $this->redirectToRoute('videos_index');
    }
}
