<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EdituserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
        ->add('email', EmailType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Merci de saisir une adresse email'
                ])
            ],
            'required' => true,
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('roles', ChoiceType::class, [
            'choices' => [
                'Utilisateur' => 'ROLE_USER',
                'vendeur' => 'ROLE_EDITOR',
                
               
            ],
            // case a cocher
            'expanded' => true,
            'multiple' => true,
            'label' => 'Rôles'
        ])
            ->add('nom')
            ->add('prenom')
            ->add('Valider', SubmitType::class)
                                                        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
