<?php

namespace App\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('_username', EmailType::class, [
                'label' => 'Username',
                'attr'  => [
                    'value' => $options['data']['last_username']
                ]
            ])
            ->add('_password', PasswordType::class, [
                'label' => 'Password'
            ])
            ->add('_target_path', HiddenType::class, [
                'attr' => [
                    'value' => 'dashboard'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Login'
            ]);
    }
}