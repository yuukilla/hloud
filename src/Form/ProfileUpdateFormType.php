<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\File;

class ProfileUpdateFormType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $builder
            ->add('username', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control rounded-3',
                    'placeholder' => 'Username',
                    'autocomplete' => 'none',
                    'value' => $user->getUsername()
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control rounded-3',
                    'placeholder' => 'Email',
                    'autocomplete' => 'none',
                    'value' => $user->getEmail()
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control rounded-3',
                    'placeholder' => 'First name',
                    'autocomplete' => 'none',
                    'value' => $user->getFirstName()
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control rounded-3',
                    'placeholder' => 'Last name',
                    'autocomplete' => 'none',
                    'value' => $user->getLastName()
                ]
            ])
            ->add('photo', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control rounded-3'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '12288k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file [png or jpeg]',
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
