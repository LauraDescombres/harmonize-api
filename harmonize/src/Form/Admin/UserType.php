<?php

namespace App\Form\Admin;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom/Pseudo'
            ])
            ->add('password', null, [
                'label' => 'Mot de passe'
            ])
            ->add('picture', FileType::class, [
                'data_class' => null,
                'required' => false,
                'label' => 'Image',
            ])
            ->add('description', TextareaType::class)
            ->add('profil')
            ->add('email')
            ->add('status')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
