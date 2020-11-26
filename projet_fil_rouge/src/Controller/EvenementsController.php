<?php

namespace App\Controller;

use App\Entity\Evenements;
use App\Form\EvenementsType;
use App\Repository\EvenementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users;
use Symfony\Component\Security\Core\Security;
use App\Repository\UsersRepository;


/**
 * @Route("/evenements")
 */
class EvenementsController extends AbstractController
{
    /**
     * @Route("/", name="evenements_index", methods={"GET"})
     */
    public function index(EvenementsRepository $evenementsRepository, UsersRepository $usersRepository): Response
    {
        return $this->render('evenements/index.html.twig', [
            'evenements' => $evenementsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="evenements_new", methods={"GET","POST"})
     */
    public function new(Request $request, UsersRepository $usersRepository, Security $security): Response
    {
        $evenement = new Evenements();
        $form = $this->createForm(EvenementsType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $evenement->setUser($security->getUser());
            $evenement->setPublished(false);
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('evenements_index');
        }

        return $this->render('evenements/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="evenements_show", methods={"GET"})
     */
    public function show(Evenements $evenement): Response
    {
        return $this->render('evenements/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="evenements_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Evenements $evenement): Response
    {
        $form = $this->createForm(EvenementsType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('evenements_index');
        }

        return $this->render('evenements/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="evenements_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Evenements $evenement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('evenements_index');
    }

    /**
     * @Route("/ask/publish/{id}", name="evenements_publish")
     */
    public function publish($id, Evenements $evenement): Response
    {
        if($evenement->getPublished() == false)
        {
            $entityManager = $this->getDoctrine()->getManager();
            $evenement->setPublished(true);
            $entityManager->persist($evenement);
            $entityManager->flush();
        }else{
            $this->addFlash('warning', 'Article déjà publié');
        }

        return $this->redirectToRoute('evenements_index');
    }
}
