<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\EditUserType;
use App\Form\RegisterType;
use App\Repository\AvatarsRepository;
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
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UsersController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager, AvatarsRepository $avatarsRepository, ValidatorInterface $validator)
    {
        $cgu = file_get_contents($_SERVER['DOCUMENT_ROOT'].'cgu.txt');
        $user = new Users();
        $avatars = $avatarsRepository->findAll();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        $errors = $validator->validate($user);

        $checkAvatarIsChecked = $request->request->get('avatar');

        $checkCguIsChecked = false;

        $request->request->get('cgu') && $request->request->get('cgu') === 'on' ? $checkCguIsChecked = true : $checkCguIsChecked = false;

        if ($form->isSubmitted()){
            if(count($errors) > 0 || !$checkAvatarIsChecked || $checkCguIsChecked === false){
                 !$checkAvatarIsChecked ? $messageAvatar = "Vous devez sélectionner un avatar" : $messageAvatar = null;
                 !$checkCguIsChecked ? $messageCgu = "Vous devez lire et accepter les CGU" : $messageCgu = null;

                return $this->render('users/register.html.twig', [
                    "errors" => $errors,
                    'avatars'=>$avatars,
                    'messageAvatar' => $messageAvatar,
                    'messageCgu' => $messageCgu,
                    "form" => $form->createView(),
                    'cgu' => $cgu
                ]);
            }
            else{
                $avatarId = intval($request->request->get('avatar'));
                $avatar = $avatarsRepository->findOneBy(["id" => $avatarId]);

                $passwordEncrypt = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($passwordEncrypt)
                    ->setRoles("ROLE_USER")
                    ->setAvatar($avatar);
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute("login");
            }
        }

        return $this->render('users/register.html.twig', [
            'avatars'=>$avatars,
            "form" => $form->createView(),
            'cgu' => $cgu
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
     *  @Route("/edituser/{id}", name="edituser", methods={"GET", "POST"})
     */
    public function edituser(Request $request, UsersRepository $usersRepository, Security $security, $id, AvatarsRepository $avatarsRepository)
    {
        $user = $usersRepository->findOneBy(["id" => $security->getUser()]);
        $avatars = $avatarsRepository->findAll();

        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatarId = intval($request->request->get('avatar'));
            $avatar = $avatarsRepository->findOneBy(["id" => $avatarId]);
            $user->setAvatar($avatar);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile');
        }

        return $this->render('users/useredit.html.twig', [
            'user' => $user,
            'avatars' => $avatars,
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
