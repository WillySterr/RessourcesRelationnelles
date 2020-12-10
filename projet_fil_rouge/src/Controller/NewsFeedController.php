<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Favoris;
use App\Form\CommentsType;
use App\Repository\RessourcesRepository;
use App\Repository\FavorisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class NewsFeedController extends AbstractController
{
    /**
     * @Route("/", name="news_feed")
     */
    public function index(RessourcesRepository $ressourcesRepository, Security $security, FavorisRepository $favorisRepository): Response
    {
        $newsFeed = $ressourcesRepository->getAllNewsFeed();
        $userCo = $security->getUser();
        $favoList = $favorisRepository->findByUser($userCo);

        return $this->render('news_feed/index.html.twig', [
            'news' => $newsFeed,
            'userCo' => $userCo,
            'favoList' => $favoList,
        ]);
    }

    /**
     * @Route("/comments/{id}", name="add_comment")
     */
    public function addComments(Request $request, RessourcesRepository $ressourcesRepository, EntityManagerInterface $entityManager, Security $security, $id)
    {

        if ($request->request->get('comments') !== [] && $request->request->get('comments') !== null && $request->request->get('comments') !== "") {
            $comments = new Comments();
            $comments->setUser($security->getUser())
                ->setContenu($request->request->get('comments'))
                ->setRessource($ressourcesRepository->findOneBy(["id" => $id]))
                ->setCreatedAt(new \DateTime('now', new \DateTimeZone("Europe/Paris")));
            $entityManager->persist($comments);
            $entityManager->flush();
        } else {
            return $this->redirectToRoute('news_feed');
        }

        return $this->redirectToRoute('news_feed');
    }


    /**
     * @Route("/favoris/{id}", name="add_favoris", methods={"GET", "POST"})
     */
    public function addFavoris(Request $request, Security $security, RessourcesRepository $ressourcesRepository, FavorisRepository $favorisRepository, $id): Response
    {
        $favori = new Favoris();
        $favori->setUser($security->getUser())
            ->setRessource($ressourcesRepository->findOneBy(["id" => $id]));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($favori);
        $entityManager->flush();
        return $this->redirectToRoute('news_feed');

        //return $this->redirectToRoute('news_feed');

    }

    /**
     * @Route("/delete/favoris/{id}", name="remove_favoris", methods={"GET", "DELETE"})
     */
    public function removeFavoris(Request $request, RessourcesRepository $ressourcesRepository, FavorisRepository $favorisRepository, Favoris $favori, $id): Response
    {
        $favoris = $favorisRepository->findOneBy(["id" => $id]);
        //dd($favoris->getRessource()->getFavoris());
        //$ressource->removeFavori($favori);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($favori);
        $entityManager->flush();


        return $this->redirectToRoute('news_feed');
    }
}
