<?php

namespace App\Controller;

use App\Entity\Evenements;
use App\Form\EvenementsType;
use App\Repository\CommentsRepository;
use App\Repository\EvenementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users;
use Symfony\Component\Security\Core\Security;
use App\Repository\UsersRepository;
use App\Repository\RessourcesRepository;
use App\Entity\Ressources;


/**
 * @Route("/evenements")
 */
class EvenementsController extends AbstractController
{
    /**
     * @Route("/", name="evenements_index", methods={"GET"})
     */
    public function index(EvenementsRepository $evenementsRepository, Security $security): Response
    {
        $evenements = $evenementsRepository->findBy(["user" => $security->getUser()->getId()]);
        return $this->render('Evenements/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    /**
     * @Route("/new", name="evenements_new", methods={"GET","POST"})
     */
    public function new(Request $request, UsersRepository $usersRepository, Security $security, RessourcesRepository $ressourcesRepository): Response
    {
        $evenement = new Evenements();
        $form = $this->createForm(EvenementsType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ressource = new Ressources();
            $ressource->setUser($security->getUser())
                ->setEvenement($evenement)
                ->setTitle($evenement->getTitre())
                ->setPublished(false);

            foreach ($ressource->getCategory() as $cat) {
                $ressource->addCategory($cat);
            };
            $entityManager = $this->getDoctrine()->getManager();
            $evenement->setUser($security->getUser());
            $evenement->setPublished(false);
            $entityManager->persist($evenement);
            $entityManager->persist($ressource);
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
    public function show($id, Evenements $evenement, RessourcesRepository $ressourcesRepository): Response
    {
        $ressource = $ressourcesRepository->findOneBy(["evenement" => $id]);
        return $this->render('evenements/show.html.twig', [
            'evenement' => $evenement,
            'ressourceId' => $ressource->getId()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="evenements_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Evenements $evenement, RessourcesRepository $ressourcesRepository): Response
    {
        $form = $this->createForm(EvenementsType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ressource = $ressourcesRepository->findOneBy(["evenement" => $evenement->getId()]);
            $ressource->setUpdatedAt();
            $ressource->setTitle($evenement->getTitre());
            foreach ($ressource->getCategory() as $cat) {
                $ressource->addCategory($cat);
            };
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
    public function delete(Request $request, Evenements $evenement, RessourcesRepository $ressourcesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $evenement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $ressource = $ressourcesRepository->findOneBy(["evenement" => $evenement->getId()]);
            $entityManager->remove($evenement);
            $entityManager->remove($ressource);
            $entityManager->flush();
        }

        return $this->redirectToRoute('evenements_index');
    }

    /**
     * @Route("/ask/publish/{id}", name="evenements_publish")
     */
    public function publish($id, Evenements $evenement): Response
    {
        if ($evenement->getPublished() == false) {
            $entityManager = $this->getDoctrine()->getManager();
            $evenement->setPublished(true);
            $entityManager->persist($evenement);
            $entityManager->flush();
        } else {
            $this->addFlash('warning', 'Article déjà publié');
        }

        return $this->redirectToRoute('evenements_index');
    }
}
