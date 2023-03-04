<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('codepostale')
            ->add('numtel')
            ->add('ville')
            ->add('nom_boutique')
            ->add('photo',FileType::class , [
                'label'=>'votre image de profile(des fichiers images uniquement)',
                'mapped' => false,
                'constraints'=>[
                    new File([
                        'maxSize'=>'1024k',
                        'mimeTypes'=>[
                            'image/gif',
                            'image/jpeg',
                            'image/jpg',
                            'image/png',

                        ],
                        'mimeTypesMessage'=>'please upload a valid image ',
                    ])
                    ],
            ])

            ->add('Valider',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
