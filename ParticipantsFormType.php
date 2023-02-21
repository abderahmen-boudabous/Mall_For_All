<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\ParticipantsEvent;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Participants;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as ChoiceTypeBase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ParticipantsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomShop')
            ->add('TypeEvent',EntityType::class,
              ['class'=>Event::class,
              'choice_label'=>'DesEvent',
              'multiple'=>false])
            ->add('save',SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParticipantsEvent::class,
        ]);
    }
}
