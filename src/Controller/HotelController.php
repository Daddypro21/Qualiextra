<?php

namespace App\Controller;

use App\Repository\GiftRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotelController extends AbstractController
{
    #[Route('Account/hotel/profil', name: 'app_hotel_profil')]
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
    
}
