<?php

namespace App\Controller;

use App\Entity\Gift;
use App\Form\GiftType;
use App\Security\Voter\GiftVoter;
use App\Repository\GiftRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GiftController extends AbstractController
{

    #[Route('gift/dashboard',name:'app_hotel_dashboard')]
    public function giftDashboard( GiftRepository $giftRepo)
    {
        $allGift = $giftRepo->findBy(['hotel'=> $this->getUser(),'reserve'=> NULL]);
        $nbGift = $giftRepo-> findAllGiftByUser($this->getUser());
        return $this->render('gift/gift_dashboard.html.twig', [
            'all_gift' => $allGift,
            'nb_gift'=> $nbGift
        ]);
    }
    #[Route('gift/create', name:'app_gift_create')]
    public function createGift(Request $request, EntityManagerInterface $em): Response 
    {
        
        $gift = new Gift();
        $form = $this->createForm(GiftType::class, $gift);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $gift->setHotel($this->getUser());
            $em->persist($gift);
            $em->flush();

            return $this->redirectToRoute('app_hotel_dashboard');
        }

        return $this->render('gift/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/gift/delete/{id}', name:'app_gift_delete')]
    public function deleteGift( Gift $gift, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted(GiftVoter::DELETE_GIFT, $gift) ;
        $em->remove($gift);
        $em->flush();
        
        return $this->redirectToRoute('app_hotel_dashboard');
    }

    #[Route('/gift/edit/{id}', name: 'app_gift_edit')]
    public function editGift(Gift $gift, Request $request,EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted(GiftVoter::EDIT_GIFT, $gift) ;
        $form = $this->createForm(GiftType::class , $gift);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $gift->setHotel($this->getUser());
            $em->flush($gift);
            return $this->redirectToRoute('app_hotel_dashboard');
        }

        return $this->render('gift/edit.html.twig', [
            'gift'=> $gift ,
            'form' => $form->createView(),
        ]);

    }

    #[Route('gift/reserved-gift', name:"app_hotel_reserved_gift")]
    public function reservedGift(GiftRepository $gift, EntityManagerInterface $em)
    {
        $giftReserved = $gift->findBy(['hotel'=> $this->getUser() , 'reserve'=> 1]);

        return $this->render('gift/reserved_gift.html.twig', [
            'reserved_gift'=> $giftReserved ,
            
        ]);

    }

    #[Route('gift/show-reserved-gift/{id}' , name:"app_show_reserved_gift")]
    public function showReservedGift( Gift $gift , GiftRepository $giftRepo) 
    {
        $gift = $giftRepo->findOneBy(['id'=> $gift->getId()]);
        return $this->render('gift/show_reserved_gift.html.twig', [
            'gift' => $gift,
        ]);
    }

    #[Route('gift/gift-show/{id}' , name:"app_gift_show")]
    public function showGift( Gift $gift , GiftRepository $giftRepo) 
    {
        $gift = $giftRepo->findOneBy(['id'=> $gift->getId()]);
        return $this->render('gift/show_gift.html.twig', [
            'gift' => $gift,
        ]);
    }
}
