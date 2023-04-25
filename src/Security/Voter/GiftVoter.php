<?php

namespace App\Security\Voter;

use App\Entity\Gift;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class GiftVoter extends Voter
{
    public const EDIT_GIFT = 'editGift';
    public const DELETE_GIFT = 'deleteGift';

    private $security;

    public function __construct( Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $gift): bool
    {
        
        return in_array($attribute, [self::EDIT_GIFT, self::DELETE_GIFT])
            && $gift instanceof \App\Entity\Gift;
    }

    protected function voteOnAttribute(string $attribute, mixed $gift, TokenInterface $token): bool
    {
        $user = $token->getUser();

         
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        //On verifie si l'utilisateur est Admin

        if( $this->security->isGranted("ROLE_ADMIN")) return true;

        //On verifie si le gift a été créé par un utilisateur( hotel)

        if(null === $gift->getHotel()){

            return false;

        } 
        
        switch ($attribute) {
            case self::EDIT_GIFT:

                //On verifie si l'utilisateur( hotel)  peut editer 
                return $this->canEdit($gift, $user);
                
                break;
            case self::DELETE_GIFT:

                 //On verifie si l'utilisateur(hotel) peut supprimer 
                 return $this->canDelete($gift, $user);
                break;
        }

        return false;
    }

    private function canEdit( Gift $gift, User $user)
    {
        
        //le proprietaire du gift peut modifier
        return $user === $gift->getHotel();
    }

    private function canDelete(Gift $gift, User $user)
    {
        //le proprietaire du gift peut supprimer
        return $user === $gift->getHotel();
    }
    
}
