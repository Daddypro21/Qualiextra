<?php

namespace App\Controller;

use App\Entity\Gift;
use App\Form\GiftType;
use App\Repository\GiftRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GiftController extends AbstractController
{

    #[Route('gift/dashboard',name:'app_hotel_dashboard')]
    public function giftDashboard( GiftRepository $giftRepo)
    {
        $allGift = $giftRepo->findBy(['hotel'=> $this->getUser()]);
        $nbGift = $giftRepo-> findAllGiftByUser($this->getUser());
        return $this->render('gift/gift_dashboard.html.twig', [
            'all_gift' => $allGift,
            'nb_gift'=> $nbGift
        ]);
    }
    #[Route('gift/create', name:'app_gift_create')]
    public function create(Request $request, EntityManagerInterface $em): Response 
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
        $em->remove($gift);
        $em->flush();
        
        return $this->redirectToRoute('app_hotel_dashboard');
    }

    #[Route('/gift/edit/{id}', name: 'app_gift_edit')]
    public function editGift(Gift $gift, Request $request,EntityManagerInterface $em)
    {
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

    #[Route('gift/edit/one/{id}', name:" app_gift_eidt_one")]
    public function editOne(Gift $gift, EntityManagerInterface $em)
    {
        
    }
}
