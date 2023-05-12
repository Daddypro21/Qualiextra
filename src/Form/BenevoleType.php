<?php

namespace App\Form;

use App\Entity\Benevole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BenevoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,['label'=>'Votre email', 'attr'=>['class'=>'from-control border_fild']])
            ->add('name',TextType::class,['label'=>'Votre nom','attr'=>['class'=>'from-control border_fild']])
            ->add('firstName',TextType::class,['label'=>'Votre prenom','attr'=>['class'=>'from-control border_fild']])
            ->add('numberPhone',TelType::class,['label'=>'Numero de telephone',"attr"=>['placeholder'=>'+33', 'class'=>'form-control border_fild']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Benevole::class,
        ]);
    }
}
