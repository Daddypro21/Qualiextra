<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Entity\Benevole;
use App\Entity\Account;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\BenevoleType;
use App\Form\HotelType;
use App\Repository\BenevoleRepository;
use App\Repository\HotelRepository;
use App\Repository\UserRepository;
use App\Service\SendMailService;
use App\Service\JWTService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('benevole/registration', name: 'app_registration_benevole')]

    public function benevoleRegistration(Request $request ,
     EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher,
     BenevoleRepository $benevoleRepo): Response
    {
        $benevole = new Benevole();

        $form = $this->createForm( BenevoleType::class , $benevole);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $hash = $passwordHasher->hashPassword(  $benevole , 
            'azerty');
            $benevole->setPassword($hash);
            $mail =   $benevole->getEmail();
            $em->persist($benevole);
            $em->flush();
            $userBenevole = $benevoleRepo->findOneBy(['email'=> $mail]);
           
            return $this->redirectToRoute('app_register_account',['id'=> $userBenevole->getId()]);

            
        }
        return $this->render('Registration/benevole.registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('hotel/registration', name: 'app_registration_hotel')]

    public function hotelRegistration(Request $request ,
     EntityManagerInterface $em,
      UserPasswordHasherInterface $passwordHasher, HotelRepository $hotelRepo, UserRepository $userRepo): Response
    {
        $hotel = new Hotel();

        $form = $this->createForm( HotelType::class , $hotel);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $hash = $passwordHasher->hashPassword($hotel , 
            '#azertyAZERTYquartyQUARTY#');
            $hotel->setPassword($hash);
            $mail = $hotel->getEmail();
            $em->persist($hotel);
            $em->flush();
            $userHotel = $hotelRepo->findOneBy(['email'=> $mail]);
            return $this->redirectToRoute('app_registration_account',['id'=> $userHotel->getId()]);
        }

        return $this->render('Registration/hotel.registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('account/registration/{id}', name: 'app_registration_account')]

    public function account( Hotel $hotel , Request $request , EntityManagerInterface $em,
    UserPasswordHasherInterface $passwordHasher,
    SendMailService $mail, JWTService $jwt)
    {
       //dd($hotel);

        $account = new Account();

        $form = $this->createForm( AccountType::class,$account);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

            $hash = $passwordHasher->hashPassword($hotel , 
            $account->getPassword());

            $hotel->setPassword($hash);
            $hotel->setRoles(["ROLE_HOTEL"]);
            $em->flush($hotel);

            //token

            $header = [
                'typ'=>'JWT',
                'alg'=>'HS256'
            ];

            $payload = [
                'user_id'=> $hotel->getId()
            ];

            $token = $jwt->generate($header,$payload,
             $this->getParameter('app.jwtsecret'));

             $user = $hotel;

            $mail->send(
                'no-reply@gmail.com',
                $user->getEmail(),
                'Activation de votre compte sur le site Atlas Solution',
                'register',
                compact('user','token')
            );
            return $this->redirectToRoute('app_check_email',['id'=>$user->getId()]);
        }

        return $this->render('Registration/account.html.twig', [
            'form' => $form->createView(),
            'hotel'=> $hotel
        ]);

    }


    #[Route('account/register/{id}', name: 'app_register_account')]

    public function myAccount( Benevole $benevole , Request $request , EntityManagerInterface $em,
    UserPasswordHasherInterface $passwordHasher,SendMailService $mail,
     JWTService $jwt)
    {
       

        $account = new Account();

        $form = $this->createForm( AccountType::class,$account);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

            $hash = $passwordHasher->hashPassword($benevole , 
            $account->getPassword());

            $benevole->setPassword($hash);
            $benevole->setRoles(["ROLE_BENEVOLE"]);
            $em->flush($benevole);

            $header = [
                'typ'=>'JWT',
                'alg'=>'HS256'
            ];

            $payload = [
                'user_id'=> $benevole->getId()
            ];

            $token = $jwt->generate($header,$payload,
             $this->getParameter('app.jwtsecret'));

             $user = $benevole;
            $mail->send(
                'no-reply@gmail.com',
                $user->getEmail(),
                'Activation de votre compte sur le site Atlas Solution',
                'register',
                compact('user','token')
            );


            return $this->redirectToRoute('app_check_email',['id'=>$user->getId()]);
        }

        return $this->render('Registration/account.html.twig', [
            'form' => $form->createView(),
            'benevole'=> $benevole
        ]);

    }


    //Verify User Token

    #[Route('verify/user/{token}', name: 'app_verify_user')]
    public function verifyUser( $token , JWTService $jwt, 
    UserRepository $userRepo , EntityManagerInterface $em):Response
    {

        if($jwt->isValid($token) && !$jwt->isExpired($token)
         && $jwt->check($token , $this->getParameter('app.jwtsecret'))){
            $payload = $jwt->getPayload($token);

            $user = $userRepo->find($payload->user_id);
            
            if($user && !$user->getIsverified()){
               $user->setIsVerified(true);
               $em->flush($user);
               $this->addFlash('success','Utilisateur activé');
               return $this->redirectToRoute('app_login');

            }
         }

         $this->addFlash('danger','le token est invalide ou a expiré');

        return $this->redirectToRoute('app_home');
    }

    #[Route('register/', name: 'app_register')]
    public function Register()
    {
        return $this->render('Registration/register.html.twig', [
        ]);
 
    }

    #[Route('check/emailmessage/{id}', name: 'app_check_email')]
    public function checkEmailMessage( User $user)
    {
        $userEmail = $user->getEmail();
        return $this->render('Registration/checkEmail.html.twig', [
            'user_email' => $userEmail
        ]);
    }
}
