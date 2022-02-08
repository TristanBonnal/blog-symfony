<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/signup', name: 'security_registration')]
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface  $encoder): Response
    {
        $user = new User;

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->hashPassword($user, $user->getPassword()); //Méthode depuis Symfony 5.4
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('login');
        }

        return $this->renderForm('security/registration.html.twig', [
            'title' => 'Inscription',
            'registrationForm' => $form
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('security/login.html.twig', [
            'title' => 'Connexion'
        ]);
    }
}
