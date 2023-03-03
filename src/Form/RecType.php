<?php

namespace App\Form;

use App\Entity\Rec;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\RecT;
use Symfony\Component\Form\Extension\Core\Type\HiddenType as BaseHiddenType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType ;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class RecType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {$currentDate = new DateTime();
        $currentDateString = $currentDate->format('Y-m-d');

        $builder
        ->add('userR', TextType::class, [
            'constraints' => [
               
            ]
        ])
        ->add('email')

        
            ->add('sujet')
            ->add('contenu', TextareaType::class, [
                'attr' => ['rows' => 10, 'cols' => 40]
            ])          
            ->add('RecT',EntityType::class,
              ['class'=>RecT::class,
              'choice_label'=>'nomT',
              'multiple'=>false])
              ->add('etat', HiddenType::class, [
                'mapped' => false,
                'attr' => ['class' => 'hidden-field'],
                'data' => 'Not solved'
            ])
            ->add('date', HiddenType::class, [
                'attr' => [
                    'class' => 'hidden-field',
                    'value' => (new \DateTime())->format('Y-m-d')
                ]
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
