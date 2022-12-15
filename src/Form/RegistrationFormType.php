<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => false,
                'attr' => [
                    'autocomplete' => 'username',
                    'class' => 'form-control rounded-3',
                    'placeholder' => 'Username'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'autocomplete' => 'email',
                    'class' => 'form-control rounded-3',
                    'placeholder' => 'Email'
                ]
            ])
            ->add('first_name', TextType::class, [
                'label' => false,
                'attr' => [
                    'autocomplete' => 'none',
                    'class' => 'form-control rounded-3',
                    'placeholder' => 'First name'
                ]
            ])
            ->add('last_name', TextType::class, [
                'label' => false,
                'attr' => [
                    'autocomplete' => 'none',
                    'class' => 'form-control rounded-3',
                    'placeholder' => 'Last name'
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'mapped' => false,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control rounded-3',
                    ]
                ],
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Password',
                        'class' => 'form-control rounded-3'
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Repeat password',
                        'class' => 'form-control rounded-3'
                    ]
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter password.'
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Password should be at least {{ limit }} characters.',
                        'max' => 4069
                    ])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
