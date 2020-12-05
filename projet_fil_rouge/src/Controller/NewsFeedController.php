<?php

namespace App\Controller;

use App\Repository\RessourcesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsFeedController extends AbstractController
{
    /**
     * @Route("/", name="news_feed")
     */
    public function index(RessourcesRepository $ressourcesRepository): Response
    {
        $newsFeed = $ressourcesRepository->getAllNewsFeed();
        return $this->render('news_feed/index.html.twig', [
            'news' => $newsFeed,
        ]);
    }
}
