<?php

namespace App\Controller;

use App\Entity\Photos;
use App\Form\PhotosType;
use App\Repository\FavorisRepository;
use App\Repository\PhotosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Repository\UsersRepository;
use App\Repository\RessourcesRepository;
use App\Entity\Ressources;


/**
 * @Route("/photos")
 */
class PhotosController extends AbstractController
{
    /**
     * @Route("/", name="photos_index", methods={"GET"})
     */
    public function index(PhotosRepository $photosRepository, Security $security): Response
    {
        $photos = $photosRepository->findBy(["user" => $security->getUser()->getId()]);
        return $this->render('Photos/index.html.twig', [
            'photos' => $photos,
        ]);
    }

    /**
     * @Route("/new", name="photos_new", methods={"GET","POST"})
     */
    public function new(Request $request, UsersRepository $usersRepository, Security $security, RessourcesRepository $ressourcesRepository): Response
    {
        $photo = new Photos();
        $form = $this->createForm(PhotosType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les photos transmises
            $images = $form->get('image')->getData();
            // if($images[]->getMimeType() = "application/pdf"){
                
            // }



            // On boucle sur les images
            foreach ($images as $image) {
                //dd($image->getMimeType());
                if($image->getMimeType() != "application/jpg" && $image->getMimeType() != "application/png" && $image->getMimeType() != "image/png" && $image->getMimeType() != "image/jpg"){
                    $this->addFlash('danger', 'Veuillez insérer une image au format "jpg" ou "png"');

                return $this->redirectToRoute('photos_new'); ;
                }
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données

                $photo->setImage($fichier);
            }


            $ressource = new Ressources();
            $ressource->setUser($security->getUser())
                ->setPhoto($photo)
                ->setTitle($photo->getTitre())
                ->setPublished(false);
            $entityManager = $this->getDoctrine()->getManager();
            $photo->setUser($security->getUser());
            $photo->setPublished(false);
            foreach ($photo->getCategory() as $cat) {
                $ressource->addCategory($cat);
            };
            $entityManager->persist($photo);
            $entityManager->persist($ressource);
            $entityManager->flush();

            return $this->redirectToRoute('photos_index');
        }

        return $this->render('photos/new.html.twig', [
            'photo' => $photo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="photos_show", methods={"GET"})
     */
    public function show($id, Photos $photo, RessourcesRepository $ressourcesRepository, Security $security, FavorisRepository $favorisRepository): Response
    {

        $ressource = $ressourcesRepository->findOneBy(["photo" => $id]);

        $userCo = $security->getUser();
        $favoList = $favorisRepository->findByUser($userCo);


        return $this->render('photos/show.html.twig', [
            'photo' => $photo,
            'ressourceId' => $ressource->getId(),
            'favoList' => $favoList,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="photos_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Photos $photo, RessourcesRepository $ressourcesRepository): Response
    {
        $form = $this->createForm(PhotosType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère le nom de l'image
            $nom = $photo->getImage();
            // On supprime le fichier

            if ($this->getParameter('images_directory') . '/' . $nom != true) {
                unlink($this->getParameter('images_directory') . '/' . $nom);
            }
            // On récupère les photos transmises
            $images = $form->get('image')->getData();

            // On boucle sur les images
            foreach ($images as $image) {

                if($image->getMimeType() != "application/jpg" && $image->getMimeType() != "application/png" && $image->getMimeType() != "image/png" && $image->getMimeType() != "image/jpg"){
                    $this->addFlash('danger', 'Veuillez insérer une image au format "jpg" ou "png"');

                return $this->redirectToRoute('photos_edit'); ;
                }
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données

                $photo->setImage($fichier);
            }

            $ressource = $ressourcesRepository->findOneBy(["photo" => $photo->getId()]);
            $ressource->setUpdatedAt();
            $ressource->setTitle($photo->getTitre());
            foreach ($photo->getCategory() as $cat) {
                $ressource->addCategory($cat);
            };
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('photos_index');
        }

        return $this->render('photos/edit.html.twig', [
            'photo' => $photo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="photos_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Photos $photo, RessourcesRepository $ressourcesRepository): Response
    {

        if ($this->isCsrfTokenValid('delete' . $photo->getId(), $request->request->get('_token'))) {

            $entityManager = $this->getDoctrine()->getManager();
            $ressource = $ressourcesRepository->findOneBy(["photo" => $photo->getId()]);
            $entityManager->remove($ressource);
            $entityManager->remove($photo);
            $entityManager->flush();

        }

        return $this->redirectToRoute('photos_index');
    }

    /**
     * @Route("/ask/publish/{id}", name="photos_publish")
     */
    public function publish($id, Photos $photo): Response
    {
        if ($photo->getPublished() == false) {
            $entityManager = $this->getDoctrine()->getManager();
            $photo->setPublished(true);
            $entityManager->persist($photo);
            $entityManager->flush();
        } else {
            $this->addFlash('warning', 'Article déjà publié');
        }

        return $this->redirectToRoute('photos_index');
    }
}