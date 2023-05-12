<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Form\HotelType;
use App\Repository\GiftRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('Account/')]
class HotelController extends AbstractController
{
    #[Route('/hotel/profil', name: 'app_hotel_profil')]
    public function profil(GiftRepository $giftRepo): Response
    {
        $reservedGift = $giftRepo->findBy(['hotel'=>$this->getUser() , 'reserve'=> 1]);
        $allGift = $giftRepo->findBy(['hotel'=>$this->getUser() , 'reserve'=> NULL]);
        $oneGift = $giftRepo->findOneGiftByUser($this->getUser());
        $one = "";
        if(!empty($oneGift)){
            $one = $oneGift[0];
        }

        return $this->render('hotel/profil.html.twig', [
            'one_gift'=> $one,
            'all_gift'=>$allGift,
            'reserved_gift'=> $reservedGift
        ]);
    }
    #[Route('hotel/edit', name :'app_hotel_edit')]
    public function editHotel( Hotel $hotel , Request $request ,
    EntityManagerInterface $em,UserPasswordHasherInterface $passwordHasher)
    {
        
     
        $form = $this->createForm( HotelType::class,$hotel);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush($hotel);
            dd($hotel);
            return $this->redirectToRoute('app_hotel_profil');
        }

        return $this->render('hotel/edit.html.twig',['form'=>$form->createView()]);
    }
    
}
