<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditRolesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roles',ChoiceType::class, [
                'constraints' => [
                    new NotBlank([],"Ce champs ne peut pas être vide.")
                ],
                'choices'  => [
                    'Utilisateur' => "ROLE_USER",
                    'Administrateur' => "ROLE_ADMIN",
                ],

                    // used to render a select box, check boxes or radios
                    'multiple' => true,
                    'expanded' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
