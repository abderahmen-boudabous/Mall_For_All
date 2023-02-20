<?php

namespace App\Form;

use App\Entity\Shop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Product;
use Symfony\Component\Validator\Constraints\File;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\HiddenType as BaseHiddenType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType ;

class ShopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', null, ['label' => 'Shop Name : '])
        ->add('description', null, ['label' => 'Shop description : '])
        ->add('email', null, ['label' => 'Shop E-mail : '])
        ->add('date', HiddenType::class, [
            'attr' => [
                'class' => 'hidden-field',
                'value' => (new \DateTime())->format('Y-m-d')
            ]
        ])
        ->add('photo', FileType::class, [
            'label' => 'Select Logo : ',
            // unmapped means that this field is not associated to any entity property
            'mapped' => false,
            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,
            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                        'image/jpg',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid image',
                ])
            ],
        ])
    
        ->add('save',SubmitType::class);
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shop::class,
        ]);
    }
}
