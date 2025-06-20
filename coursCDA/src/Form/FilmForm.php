<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Classification;
use App\Entity\Film;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom :'
            ])
            ->add('duration', TimeType::class, [
                'label' => 'Durée :'
            ])
            ->add('urlAffiche', UrlType::class, [
                'label' => 'Url de l\'Affiche :'
            ])
            ->add('urlTrailer', UrlType::class, [
                'label' => 'Url du trailer :'
            ])
            ->add('resume', TextareaType::class, [
                'label' => 'Résumé :'
            ])
            ->add('dateSortie', DateType::class, [
                'label' => 'Date de sortie :'
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
            ->add('classification', EntityType::class, [
                'class' => Classification::class,
                'choice_label' => 'name',
            ])
            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
