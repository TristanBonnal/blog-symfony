<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PostType extends AbstractType
{
    //make:form
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('image')
            ->add('user', EntityType::class, [ 
                'class' => User::class,         //Permet un choix parmi les relations d'objets
                'choice_label' => 'firstname'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
