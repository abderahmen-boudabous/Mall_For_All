<?php

namespace App\Form;

use App\Entity\Rec;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Message;
use Symfony\Component\Form\Extension\Core\Type\HiddenType as BaseHiddenType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType ;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;

class MessageFormType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sender', TextType::class,['disabled' => true])
            ->add('contenum', TextareaType::class)
            ->add('recm', EntityType::class, [
                'class' => Rec::class,
                'choices' => $this->entityManager->getRepository(Rec::class)->findBy(['id' => $options['id']]),
                'choice_label' => 'id',
                'attr' => [
                    'hidden' => 'hidden',
                ],'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('id');
    }
}