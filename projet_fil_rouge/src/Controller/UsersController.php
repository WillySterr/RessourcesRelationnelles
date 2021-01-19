<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\EditUserType;
use App\Form\RegisterType;
use App\Repository\FavorisRepository;
use App\Repository\RessourcesRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class UsersController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager)
    {
        $user = new Users();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordEncrypt = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($passwordEncrypt)
                ->setRoles("ROLE_USER");
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute("login");
        }

        return $this->render('users/register.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $utils)
    {
        return $this->render('users/login.html.twig', [
            "lastUserName" => $utils->getLastUsername(),
            "error" => $utils->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->redirectToRoute("register");
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function getUserProfile(UsersRepository $usersRepository, RessourcesRepository $ressourcesRepository, Security $security, FavorisRepository $favorisRepository)
    {

        $user = $usersRepository->findOneBy(["id" => $security->getUser()]);
        $lastRessources = $ressourcesRepository->getLastRessourceOfCurrentUser($security->getUser());
        $favs = $favorisRepository->getLastFavorisOfCurrentUser($security->getUser());



        return $this->render("users/profile.html.twig", [
            "user" => $user,
            "lastRessources" => $lastRessources,
            "favs" => $favs
        ]);
    }

    /**
     * @Route("/{id}/userandco_delete", name="userandco_delete", methods={"GET", "DELETE"})
     */
    public function deleteUserAndCo(Request $request, UsersRepository $usersRepository, Security $security, $id)
    {

        $user = $usersRepository->findOneBy(["id" => $security->getUser()]);

        $currentUserId = $this->getUser()->getId();
        if ($currentUserId == $id) {
            $session = $this->get('session');
            $session = new Session();
            $session->invalidate();
        }


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();


        return $this->redirectToRoute('logout');
    }

    /**
     *  @Route("/{id}/edituser", name="edituser", methods={"GET", "POST"})
     */
    public function edituser(Request $request, UsersRepository $usersRepository, Security $security, $id)
    {
        $user = $usersRepository->findOneBy(["id" => $security->getUser()]);

        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile');
        }

        return $this->render('users/useredit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/support", name="support")
     */
    public function support(Request $request, MailerInterface $mailer): Response
    {
        if ($request->isMethod("post") && isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
            $mailSup = $request->request->get('email');
            $message = $request->request->get("message");
            $secret = '6LeHGC8aAAAAAKGK6QHyY7i61rHZC2B4pJ9S1PGy';
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
            $responseData = json_decode($verifyResponse);
            if ($responseData->success && filter_var($mailSup, FILTER_VALIDATE_EMAIL) && isset($mailSup) && isset($message) && strlen($message) > 10) {
                $email = (new Email())
                    ->sender($mailSup)
                    ->to('arnaudpadula5@gmail.com')
                    ->subject('Demande de support Resources Relationnelles')
                    ->html('Email : ' . $mailSup . '<br/>' . 'Message : ' . $message);
                $mailer->send($email);
                $this->addFlash('success', 'Demande de support prise en compte');
            } else {
                $this->addFlash('danger', 'L\'email n\'est pas au bon format ou le message n\'a pas au moins 10 caractères');
            }
        }



        return $this->render('users/support.html.twig');
    }
}
