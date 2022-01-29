<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, ['label' => 'Pseudonyme'])
            ->add('email',EmailType::class, [
                'attr' => [
                    'placeholder' => 'exemple@gmail.com'
                ]
            ])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe'])
            // ->add('confirmPassword',PasswordType::class, ['label' => 'Confirmez mot de passe'])
            ->add('Inscription', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
