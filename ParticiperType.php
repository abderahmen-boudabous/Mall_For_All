<?php

namespace App\Form;

use App\Entity\Participants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType ;

class ParticiperType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('participant_id')
            // ->add('event_id')
            ->add('event_id', HiddenType::class, [
                'mapped' => false,
                'attr' => ['class' => 'hidden-field']
            ])
            ->add('Valider_Participation', SubmitType::class)
            ->add('participant_id', HiddenType::class, [
                'mapped' => false,
                'attr' => ['class' => 'hidden-field']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participants::class,
        ]);
    }
}
