<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            ->add('photo', FileType::class , [
                'label' => 'Votre image de profile (des fichiers images uniquement)',
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image.',
                    ])
                ],
            ])
            ->add('Valider',SubmitType::class)
        ;

        // Ajouter les champs nom_boutique et description_boutique uniquement si l'utilisateur a le rôle de vendeur
        if (in_array('ROLE_VENDEUR', $options['data']->getRoles())) {
            $builder
                ->add('nom_boutique', TextType::class);
                
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
        $resolver->setRequired('data');
    }
}
