<?php

namespace App\Controller;

use App\Repository\GiftRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotelController extends AbstractController
{
    #[Route('/hotel/profil', name: 'app_hotel_profil')]
    public function profil(GiftRepository $giftRepo): Response
    {
        $count_gift = $giftRepo->findAllGiftByUser($this->getUser());
        $oneGift = $giftRepo->findOneGiftByUser($this->getUser());
        return $this->render('hotel/profil.html.twig', [
            'one_gift'=> $oneGift[0],
            'all_gift'=>$count_gift
        ]);
    }
}
