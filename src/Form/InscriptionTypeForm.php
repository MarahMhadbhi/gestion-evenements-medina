<?php

namespace App\Form;

use App\Entity\Inscription;
use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // AJOUTEZ CETTE LIGNE

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomClient', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est obligatoire']),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('prenomClient', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank(['message' => 'Le prénom est obligatoire']),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(['message' => 'L\'email est obligatoire']),
                    new Email(['message' => 'Veuillez entrer un email valide'])
                ]
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'constraints' => [
                    new NotBlank(['message' => 'Le téléphone est obligatoire']),
                    new Length([
                        'min' => 8,
                        'max' => 20,
                        'minMessage' => 'Le téléphone doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le téléphone ne peut pas dépasser {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('dateNaissance', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'constraints' => [
                    new NotBlank(['message' => 'La date de naissance est obligatoire'])
                ]
            ])
            ->add('secteur_Activite', TextType::class, [
                'label' => 'Secteur d\'activité',
                'constraints' => [
                    new NotBlank(['message' => 'Le secteur d\'activité est obligatoire']),
                    new Length([
                        'min' => 3,
                        'max' => 255,
                        'minMessage' => 'Le secteur d\'activité doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le secteur d\'activité ne peut pas dépasser {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Visiteur' => 'Visiteur',
                    'Exposant' => 'Exposant'
                ],
                'expanded' => false,
                'multiple' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Le type est obligatoire']),
                    new Choice([
                        'choices' => ['Visiteur', 'Exposant'],
                        'message' => 'Veuillez choisir un type valide (Visiteur ou Exposant)'
                    ])
                ]
            ])

            ->add('nationalite', TextType::class, [
                'label' => 'Nationalité',
                'constraints' => [
                    new NotBlank(['message' => 'La nationalité est obligatoire']),
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'La nationalité doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'La nationalité ne peut pas dépasser {{ limit }} caractères'
                    ])
                ]
                    ])


            ->add('event', EntityType::class, [
                'class' => Event::class,
                'label' => 'Événement',
                'choice_label' => 'titre',
                'required' => true,
                'placeholder' => 'Choisir un événement',
            ])

            
            ->add('save', SubmitType::class, [
                'label' => $options['submit_label'],
                'attr' => ['class' => 'btn btn-primary']
            ]);

             
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inscription::class,
            'submit_label' => 'Enregistrer', // Label par défaut
        ]);
    }
}