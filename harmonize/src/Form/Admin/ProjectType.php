<?php

namespace App\Form\Admin;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('user')
            ->add('music_genre')
            ->add('description')
            ->add('picture', FileType::class, [
                'data_class' => null,
                'required' => false,
            ])
            ->add('audio_url', FileType::class, [
                'data_class' => null,
                'required' => false,
            ])
            ->add('audio_url_ext')
            ->add('status')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
