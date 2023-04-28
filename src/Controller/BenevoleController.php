<?php

namespace App\Controller;

use App\Entity\Gift;
use App\Form\GiftType;
use App\Repository\GiftRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('Account/')]
class BenevoleController extends AbstractController
{
    #[Route('benevole/dashboard', name: 'app_benevole_dashboard')]
    public function dashboard( GiftRepository $giftRepo): Response
    {
        $gifts = $giftRepo->findBy(['reserve'=> NULL]);

        return $this->render('benevole/dashboard.html.twig', [
            'gifts' => $gifts,
        ]);
    }

    #[Route('benevole/show-gift/{id}', name: 'app_show_gift')]
    public function showGift(Gift $gift ,GiftRepository $giftRepo)
    {
        $gift = $giftRepo->findOneBy(['id'=> $gift->getId()]);
        return $this->render('benevole/show_gift.html.twig', [
            'gift' => $gift,
        ]);

    }

    #[Route('benevole/reserve-gift/{id}', name: 'app_reserve_gift')]
    public function reserveGift(Gift $gift, EntityManagerInterface $em)
    {
       $gift->setReserve(true);
       $gift->setBenevole($this->getUser());
       $em->flush($gift);
       return $this->redirectToRoute('app_benevole_dashboard');


    }
    #[Route('benevole/reserved-gift', name:'app_reserved_gift')] 
    public function reservedGift(GiftRepository $giftRepo)
    {
        $gifts = $giftRepo->findBy(['reserve'=> 1, 'benevole'=>$this->getUser()]);

        return $this->render('benevole/reserved_gift.html.twig', [
            'gifts' => $gifts,
        ]);
    }
}
