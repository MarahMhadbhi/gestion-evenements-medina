<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType; // Ajouté
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de l\'événement',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'rows' => 5]
            ])
            ->add('dateDebut', DateTimeType::class, [
                'label' => 'Date et heure de début',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'form-control datetimepicker']
            ])
            ->add('dateFin', DateTimeType::class, [
                'label' => 'Date et heure de fin (optionnel)',
                'required' => false,
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'form-control datetimepicker']
            ])
            ->add('lieu', TextType::class, [
                'label' => 'Lieu',
                'attr' => ['class' => 'form-control']
            ])
            ->add('logoFile', FileType::class, [
                'label' => 'Logo (image)',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control-file']
            ])

             ->add('sponsorLogosFiles', FileType::class, [
                'label' => 'Logos sponsors',
                'multiple' => true,
                'required' => false,
                'mapped' => false,
            ]);
            
    

      


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}