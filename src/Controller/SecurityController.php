<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $manager;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    /**
     * @Route("/register", name="security_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        //Analyse de la requête par le formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //traitement des données reçues du formulaire

            //hashage du mot de passe
            $password_hash = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password_hash);

            $this->manager->persist($user);
            $this->manager->flush();

            return $this->redirectToRoute('security_login');
        }
        $valid = false;
        return $this->render('security/index.html.twig', [
            'controller_name' => 'Inscription',
            'form' => $form->createView(),
            'valid' => $valid
        ]);
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('security/login.html.twig', [
            'controller_name' => 'Connexion',
            'error'=>$error
        ]);
    }
}
