<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\LoginFormType;
use App\Form\RegisterType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;






#[Route('/TP4', name: "TP4")]
class AuthController extends AbstractController
{

    #[Route('/Inscription', name: "Inscription")]
    public function new(Request $request, TranslatorInterface $translator,ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();


        $user = new User();
        $message = $translator->trans('Minimum eight characters, at least one letter and one number');

        $errorState = false;
        $errorMessage = $translator->trans('it is not the same password');

        $form = $this->createFormBuilder($user,array('csrf_protection' => false))
            ->add('email', TextType::class)
            ->add('password', TextType::class, [
                'constraints' => new Assert\Regex([
                    'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
                    'message' => $message
                ]),
            ])
            ->add('password2', TextType::class, [
                'label' => 'Confirm password',
            ])
            ->add('save', SubmitType::class, ['label' => 'Create User'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($form["password"]->getData() === $form["password2"]->getData()) {

                $user->setEmail($form["email"]->getData());
                $user->setPassword($form["password"]->getData());
                $user->setPassword2($form["password2"]->getData());

                // tell Doctrine you want to (eventually) save the Product (no queries yet)
                $entityManager->persist($user);

                // actually executes the queries (i.e. the INSERT query)
                $entityManager->flush();


                return $this->redirectToRoute('TP4Enregistrer');

            } else {
                $errorState = true;
                // $errorMessage = "Attention les mots de passes renseignés ne sont pas identiques !";
            }
        }

        return $this->render('login.html.twig', [
            'loginForm' => $form->createView(),
            'erreur' => $errorState,
            'errorMessage' => $errorMessage
        ]);
    }

    #[Route('/Enregistrer', name: "Enregistrer")]
    public function registration_success(Request $request): Response
    {
        return $this->render('registrationSuccess.html.twig');
    }

    #[Route('/Success', name: "Success")]
    public function success(Request $request): Response
    {
        return $this->render('success.html.twig');
    }


    #[Route('/Login', name: "Login")]
    public function login(Request $request, TranslatorInterface $translator): Response
    {
        $user = new User();
        $errorState = false;
        $errorMessage = $translator->trans('error in password or email');

        $form = $this->createForm(LoginFormType::class, $user);

        function checkValid($email, $password)
        {
            if ($email == "harmonie@gmail.com" && $password == "Harmonie0123") {
                return true;
            } else {
                return false;
            }
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if (checkValid($form["email"]->getData(), $form["password"]->getData())) {
                return $this->redirectToRoute('TP4Success');
            } else {
                $errorState = true;
                // $errorMessage = "Mot de passe ou email renseigné invalid";
            }

        }

        return $this->render('login.html.twig', [
            'loginForm' => $form->createView(),
            'erreur' => $errorState,
            'errorMessage' => $errorMessage
        ]);
    }


   //Tp5 ORM pour la base de donnée
    #[Route('/Register', name: "Register")]
    public function register(Request $request, TranslatorInterface $translator,UserPasswordHasherInterface $userPasswordHasher,ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $utilisateur = new Utilisateur();
        $form = $this->createForm(RegisterType::class,$utilisateur);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($form["password"]->getData()) {

                $utilisateur->setEmail($form["email"]->getData());

                $userPasswordHasher->hashPassword( $utilisateur, $form->get('password')->getData());

                $entityManager->persist($utilisateur);
                $entityManager->flush();


                return $this->redirectToRoute('TP4Enregistrer');

            } else {
                $errorState = true;
            }
        }

        return $this->render('register.html.twig', [
            'registerForm' => $form->createView(),
        ]);
    }

    #[Route('/Liste/Utilisateur', name: "liste")]
    public function liste(ManagerRegistry $doctrine): Response
    {
        $user = $doctrine->getRepository(Utilisateur::class)->findAll();


        return $this->render('liste.html.twig',['user'=>$user]);
    }
}
