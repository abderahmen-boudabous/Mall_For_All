<?php

namespace App\Form;

use App\Entity\Rec;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\RecT;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as ChoiceTypeBase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class updateRType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('contenu', TextareaType::class, [
            'disabled' => true,
            'attr' => ['rows' => 10, 'cols' => 40]
        ])

            ->add('reponse', TextareaType::class, [
                'attr' => ['rows' => 10, 'cols' => 40]
            ])
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Solved' => 'solved',
                    'Not Solved' => 'not solved',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            
            ->add('save',SubmitType::class);
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rec::class,
        ]);
    }
    

}