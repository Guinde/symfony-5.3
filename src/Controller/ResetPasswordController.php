<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Symfony\Component\Mailer\MailerInterface;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordController extends AbstractController
{
    /**
     * @Route("/reset/password", name="app_reset_password")
     */
    public function index(Request $request, UserRepository $userRespository, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRespository
                ->findOneBy(['email' => $form->get('email')->getData()]);

            if(!$user) {
                $this->redirectToRoute('app_reset_password');
            }

            $token = sha1(mt_rand(3, 7) . microtime());
            $user->setResetPasswordToken($token);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // $email = (new Email())
            //     ->from('johnie.gursital@gmail.com')
            //     ->to($user->getEmail())
            //     ->subject('Time for Symfony Mailer !')
            //     ->html('<a href="' . $token . '">password</a>');

            // $mailer->send($email);
            return $this->redirectToRoute('reset_password', ['token' => $token]);

        }

        return $this->render('reset_password/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reset/password/{token}", name="reset_password")
     */
    public function resetPassword(Request $request, UserRepository $userRespository, string $token, UserPasswordHasherInterface $passwordHasher): Response 
    {
        $user = $userRespository->findOneBy(['resetPasswordToken' => $token]);

        if (!$user) {
            throw $this->createNotFoundException();
        }

        $formBuilder = $this->createFormBuilder();
        $formBuilder->add('password');
        $formBuilder->add('submit', SubmitType::class);
        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );

            $user->setPassword($password);
            $user->setResetPasswordToken(null);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }


        return $this->render('reset_password/reset_form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
