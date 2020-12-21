<?php

namespace App\Controller;

use App\Entity\Conversations;
use App\Entity\Messages;
use App\Repository\ConversationsRepository;
use App\Repository\MessagesRepository;
use App\Repository\UsersRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

class MessengerController extends AbstractController
{


    //Get all Conversation of Current User

    //Create a new conversation

    //Recup conversation with other user


    /**
     * @Route("/messenger", name="messenger")
     */
    public function index(UsersRepository $usersRepository, ConversationsRepository $conversationsRepository, Security $security): Response
    {

        $users = $usersRepository->findAll();
        $conversationOfCurrentUser = $conversationsRepository->getCurrentUserConversation($security->getUser()->getId());

        return $this->render('messenger/index.html.twig', [
            "users" => $users,
            "conversations" => $conversationOfCurrentUser
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

            if($conversationsRepository->checkConversationExist($security->getUser()->getId(), $request->request->get('user')) !== []){
                $this->addFlash('danger', 'La conversation existe déjà');
            }

            else{
                $newConversation = new Conversations();
                $newConversation->addUser($currentUser)
                    ->addUser($distantUser);
                $entityManager->persist($newConversation);
                $entityManager->flush();
            }

        }



        return $this->redirectToRoute('messenger');
    }

    /**
     * @Route("/conversation/user", name="get_conversation_messages")
     */
    public function getConversationMessage(MessagesRepository $messagesRepository, Request $request): Response
    {
        $messages = $messagesRepository->getMessagesAboutConversation(json_decode($request->getContent()));

        $conversation = [];
        foreach($messages as $message) {
            $conversation[] = array(
                'id' => $message->getId(),
                'user' => [
                    "id" => $message->getUser()->getId(),
                    "lastName" => $message->getUser()->getLastName(),
                    "firstName" => $message->getUser()->getFirstName()
                ],
                'message' => $message->getMessage(),
                'conversation' => [
                    'id' => $message->getConversation()->getId()
                ],
                'postedAt' => $message->getPostedAt()
                // ... Same for each property you want
            );
        }
        $response = new Response(json_encode($conversation));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    /**
     * @Route("/conversation/user/message", name="add_new_message")
     */
    public function addNewMessage(MessagesRepository $messagesRepository, Request $request, Security $security, ConversationsRepository $conversationsRepository, EntityManagerInterface $entityManager): Response
    {


        //Recup current user

        $currentUser = $security->getUser();

        //Recup id conversation and message of xhr request

        $data = json_decode($request->getContent(), true);

        $conversationId = $data['conversationId'];
        $currentMessage = $data['message'];

        //Recup the current conversation

        $conversation = $conversationsRepository->findOneBy(['id' => $conversationId]);

        $newMessage = new Messages();
            $newMessage->setUser($currentUser)
                ->setConversation($conversation)
                ->setMessage($currentMessage)
                ->setPostedAt(new DateTime('now', new \DateTimeZone("Europe/Paris")));
            $entityManager->persist($newMessage);

        $conversation->setLastMessage($currentMessage)
            ->setLastMessageDate($newMessage->getPostedAt());

        $entityManager->persist($conversation);

        $entityManager->flush();

        $messages = $messagesRepository->getMessagesAboutConversation($conversationId);

        $conversationCurrent = [];
        foreach($messages as $message) {
            $conversationCurrent[] = array(
                'id' => $message->getId(),
                'user' => [
                    "id" => $message->getUser()->getId(),
                    "lastName" => $message->getUser()->getLastName(),
                    "firstName" => $message->getUser()->getFirstName()
                ],
                'message' => $message->getMessage(),
                'conversation' => [
                    'id' => $message->getConversation()->getId()
                ],
                'postedAt' => $message->getPostedAt()
                // ... Same for each property you want
            );
        }
        $response = new Response(json_encode($conversationCurrent));
        $response->headers->set('Content-Type', 'application/json');

        return $response;



    }
    /**
     * @Route("/conversations", name="get_conversations")
     */
    public function getConversationAboutCurrentUser(ConversationsRepository $conversationsRepository, Security $security, UsersRepository $usersRepository)
    {
        $conversationOfCurrentUser = $conversationsRepository->getCurrentUserConversation($security->getUser()->getId());

        $conversations = [];
        foreach ($conversationOfCurrentUser as $conversation){
            foreach ($conversation->getUsers() as $user){

                if($user->getId() !== $security->getUser()->getId()){
                $conversations[] = array(
                'id' => $conversation->getId(),
                'lastMessage' => $conversation->getLastMessage(),
                'lastMessageDate' => $conversation->getLastMessageDate(),
                'userLastName' => $user->getLastName(),
                'userFirstName' => $user->getFirstName()
            );
                }

            }

        }


        $response = new Response(json_encode($conversations));
        $response->headers->set('Content-Type', 'application/json');

        return $response;



    }






}
