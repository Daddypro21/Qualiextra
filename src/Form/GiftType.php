<?php

namespace App\Form;

use App\Entity\Gift;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class GiftType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('kindOfDonation', ChoiceType::class,[
                'label'=>'Type de don','attr'=>['class'=>'border_fild'],
                'choices' => [
                    'Nourriture' => "NOURRITURE",
                    'Fourniture' => "FOURNITURE", 
                    'Nourriture et Fourniture'=>" NOURRITURE ET FOURNITURE",
                    'Autres'=>"AUTRES",
                ],
            ])
        ->add('description', TextareaType::class,[
        'label' => 'Faites une description détaillé du don','attr'=>['class'=>'border_fild']])
        ->add('recoveryDate')
        ->add('time')
        ->add('availablity')
       
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gift::class,
        ]);
    }
}
