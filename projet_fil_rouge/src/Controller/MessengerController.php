<?php

namespace App\Controller;

use App\Entity\Conversations;
use App\Repository\ConversationsRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\JsonEncode;

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
    /**
     * @Route("/messenger/conversation/create", name="create_conversation")
     */
    public function createConversation(Request $request, Security $security, UsersRepository $usersRepository, EntityManagerInterface $entityManager, ConversationsRepository $conversationsRepository)
    {
        $currentUser = $security->getUser();
        $distantUser = $usersRepository->findOneBy(["id" => $request->request->get('user')]);

        if(isset($distantUser)){
            $newConversation = new Conversations();
            $newConversation->addUser($currentUser)
                ->addUser($distantUser);
            $entityManager->persist($newConversation);
            $entityManager->flush();
        }

        $conversationOfCurrentUser = $conversationsRepository->findAll();

        return $this->redirectToRoute('messenger');
    }


}
