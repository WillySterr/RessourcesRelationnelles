<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessengerController extends AbstractController
{


    //Get all Conversation of Current User

    //Create a new conversation

    //Recup conversation with other user


    /**
     * @Route("/messenger", name="messenger")
     */
    public function index(UsersRepository $usersRepository): Response
    {

        $users = $usersRepository->findAll();

        return $this->render('messenger/index.html.twig', [
            "users" => $users
        ]);
    }


}
