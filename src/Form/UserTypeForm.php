<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('email', EmailType::class)
            ->add('telephone', TelType::class)
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',])
            ->add('role', ChoiceType::class, [ 
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Responsable' => 'ROLE_RESPONSABLE',
                    'Agent de saisie' => 'ROLE_AGENT',
                ]
            ]);
        if ($options['is_edit'] ?? false) {
            $builder->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Nouveau mot de passe',
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'max' => 4096,
                    ]),
                ],
            ]);
        } else {
            $builder->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6]),
                ],
            ]);
        }
    }
     public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false,
        ]);
    }
}
