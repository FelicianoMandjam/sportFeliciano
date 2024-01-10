<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class EditUserPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
         // CurrentPassword ne fait pas partie de l'intity User donc ajouter parametre "mapped"
            ->add('currentPassword', PasswordType::class, [
                "mapped" => false,
                "constraints" => [
                    new UserPassword(['message' => "Le mot de passe actuelle est incorrect."])
                ]
            ])
            ->add('password' , RepeatedType::class,[

                'type' => PasswordType::class,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le mot de passe est obligatoire.',
                        ]),
                        new Length([
                            'min' => 12,
                            'max' => 255,
                            'minMessage' => 'Le mot de passe doit contenir {{ limit }} caractères minimun.',
                            'maxMessage' => 'Le mot de passe ne doit pas depasser {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                        ]),
                        new Regex([
                            "pattern" => "/^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ỳ])(?=.*[0-9])(?=.*[^a-zà-ÿA-ZÀ-Ỳ0-9]).{11,255}$/",
                            "match" => true,
                            "message" => "Le mot de passe doit contentir au moins une lettre miniscule, une majuscule, un chiffre et un caractère spécial.",
                        ])
                    ],

                ],

                'invalid_message' => 'Le mot de passe doit être identique à sa confirmation.',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => User::class,
        ]);
    }
}
