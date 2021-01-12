<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Repository\CommentsRepository;
use App\Repository\RessourcesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CommentsController extends AbstractController
{
    /**
     * @Route("/comments/add", name="new_comments")
     */
    public function addNewComments(Security $security, Request $request, CommentsRepository $commentsRepository, RessourcesRepository $ressourcesRepository, EntityManagerInterface $entityManager): Response
    {
       // On récupère le user
        $user = $security->getUser();

        // On récupère les données (ressourceId, contenu)

        $data = json_decode($request->getContent(), true);

        $ressource = $ressourcesRepository->findOneBy(["id" => $data['idRessource']]);
        $commentsContenu = $data['comments'];

        // On créer le nouveau commentaire

        $comments = new Comments();
        $comments->setUser($user)
            ->setContenu($commentsContenu)
            ->setRessource($ressource)
            ->setCreatedAt(new \DateTime('now', new \DateTimeZone("Europe/Paris")));
        $entityManager->persist($comments);
        $entityManager->flush();

        // On récupère les commentaires de cette ressource

        $commentsOfRessource = $commentsRepository->findBy(["ressource" => $data['idRessource']]);

        // On construit le tableau de commentaires

        $arrayComments = [];

        foreach($commentsOfRessource as $comment){
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
    /**
     * @Route("/comments/get", name="get_comments")
     */
    public function getArticleComments(Request $request, CommentsRepository $commentsRepository){

        // On récupère les données de la requete ajax

        $data = json_decode($request->getContent(), true);

        // On récupère l'id de la ressource

        $ressourceId = $data['idRessource'];

        // On récupère les commentaires de la ressource

        $comments = $commentsRepository->findBy(["ressource" => $ressourceId]);

        // On construit le tableau de commentaires

        $arrayComments = [];

        foreach($comments as $comment){
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
    /**
     * @Route("/comments/all", name="get_all_comments")
     */
    public function getAllComments(CommentsRepository $commentsRepository){

        $comments = $commentsRepository->findAll();

        $arrayComments = [];

        foreach($comments as $comment){
            $arrayComments[] = array(
                'id' => $comment->getId(),
                'ressource' => $comment->getRessource()->getId(),
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
