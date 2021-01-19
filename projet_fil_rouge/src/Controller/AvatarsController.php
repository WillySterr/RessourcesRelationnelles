<?php

namespace App\Controller;

use App\Entity\Avatars;
use App\Form\AvatarsType;
use App\Repository\AvatarsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/avatars")
 */
class AvatarsController extends AbstractController
{
    /**
     * @Route("/", name="avatars_index", methods={"GET"})
     */
    public function index(AvatarsRepository $avatarsRepository): Response
    {
        return $this->render('avatars/index.html.twig', [
            'avatars' => $avatarsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="avatars_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $avatar = new Avatars();
        $form = $this->createForm(AvatarsType::class, $avatar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les avatars transmises
            $avatarIcons = $form->get('avatarIcon')->getData();



            // On boucle sur les avatarIcons
            foreach ($avatarIcons as $avatarIcon) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $avatarIcon->guessExtension();

                // On copie le fichier dans le dossier uploads
                $avatarIcon->move(
                    $this->getParameter('avatarIcons_directory'),
                    $fichier
                );

                // On crée l'avatarIcon dans la base de données

                $avatar->setAvatarIcon($fichier);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($avatar);
            $entityManager->flush();

            return $this->redirectToRoute('avatars_index');
        }

        return $this->render('avatars/new.html.twig', [
            'avatar' => $avatar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="avatars_show", methods={"GET"})
     */
    public function show(Avatars $avatar): Response
    {
        return $this->render('avatars/show.html.twig', [
            'avatar' => $avatar,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="avatars_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Avatars $avatar): Response
    {
        $form = $this->createForm(AvatarsType::class, $avatar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('avatars_index');
        }

        return $this->render('avatars/edit.html.twig', [
            'avatar' => $avatar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="avatars_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Avatars $avatar): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avatar->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($avatar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('avatars_index');
    }
}
