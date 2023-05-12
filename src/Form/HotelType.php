<?php

namespace App\Form;

use App\Entity\Hotel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class HotelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name',TextType::class,['label'=>'Le nom de l\' Hotel','attr' => ['placeholder'=>'Ex : Madison Palace','class'=>'form-control border_fild']])
            ->add('email',EmailType::class,['label'=>'L\'email de l\' Hotel','attr' => ['placeholder'=>' Ex :Madison@madison.com','class'=>' form-control border_fild']])
           
            ->add('address',TextType::class,['label'=>'L\' adresse de l\' Hotel','attr' => ['placeholder'=>' Ex : 59 Bd de Belleville','class'=>' form-control border_fild']])
            ->add('contactPhone',TelType::class,['label'=>'Le numero de telephone l\' Hotel','attr' => ['placeholder'=>' Ex : +33 6 82 18 55 71','class'=>' form-control border_fild']])
            ->add('managerName',TextType::class,['label'=>'Le nom de du manager de l\' Hotel','attr' => ['placeholder'=>' Ex : Doe','class'=>' form-control border_fild']])
            ->add('managerFirstName',TextType::class,['label'=>'Le prenom du manager de l\' Hotel','attr' => ['placeholder'=>' Ex : John','class'=>'form-control border_fild']])
            ->add('managerPhone',TelType::class,['label'=>'Le numero de telephone du manager','attr' => ['placeholder'=>' Ex : +33 6 80 10 50 70','class'=>'form-control border_fild']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hotel::class,
        ]);
    }
}
