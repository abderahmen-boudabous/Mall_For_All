<?php

namespace App\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType as BaseHiddenType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


use App\Entity\Product;

class CommentFormType extends AbstractType
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('comment')
        ->add('product', EntityType::class, [
            'class' => Product::class,
            'choices' => $this->entityManager->getRepository(Product::class)->findBy(['id' => $options['id']]),
            'choice_label' => 'id',
            'attr' => [
                'hidden' => true,
                ],
            'label' => false,
        ])
        
        
        ->add('save', SubmitType::class);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('id');
    }

    
}
