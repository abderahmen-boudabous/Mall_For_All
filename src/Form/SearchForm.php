<?php

namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


use App\Data\SearchData;
use App\Entity\Shop;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SearchForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('q', TextType::class, [
            'label' => false,
            'required' => false,
            'attr' => [ 
                'placeholder' => 'Search for a product'
             ]
        ])
        
        ->add('shop', EntityType::class, [
            'label' => false,
            'required' => false,
            'class' => Shop::class,
            'expanded' => true,
            'multiple' => true,     
        ])
        
        ->add('min', NumberType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'Price Minimum'
            ] 
        ])
        
        ->add('max', NumberType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'Price Maximum'
            ] 
        ])
        
        ->add('stock', CheckboxType::class, [
            'label' => 'In Stock',
            'required' => false,
        ]);
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }


}