<?php

namespace App\Controller;

use App\Entity\Informations;
use App\Form\InformationsType;
use App\Repository\InformationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Repository\UsersRepository;

/**
 * @Route("/informations")
 */
class InformationsController extends AbstractController
{
    /**
     * @Route("/", name="informations_index", methods={"GET"})
     */
    public function index(InformationsRepository $informationsRepository): Response
    {
        return $this->render('informations/index.html.twig', [
            'informations' => $informationsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="informations_new", methods={"GET","POST"})
     */
    public function new(Request $request, Security $security, UsersRepository $usersRepository): Response
    {

        $information = new Informations();
        $form = $this->createForm(InformationsType::class, $information);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $information->setUser($security->getUser());
            $entityManager->persist($information);
            $entityManager->flush();

            return $this->redirectToRoute('informations_index');
        }

        return $this->render('informations/new.html.twig', [
            'information' => $information,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="informations_show", methods={"GET"})
     */
    public function show(Informations $information): Response
    {
        return $this->render('informations/show.html.twig', [
            'information' => $information,
        ]);
    }

    /**
     * @Route("/{slug}", name="informations_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Informations $information): Response
    {
        $form = $this->createForm(InformationsType::class, $information);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('informations_index');
        }

        return $this->render('informations/edit.html.twig', [
            'information' => $information,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="informations_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Informations $information): Response
    {
        if ($this->isCsrfTokenValid('delete' . $information->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($information);
            $entityManager->flush();
        }

        return $this->redirectToRoute('informations_index');
    }
}
