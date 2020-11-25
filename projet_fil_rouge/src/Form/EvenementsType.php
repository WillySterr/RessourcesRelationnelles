<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Evenements;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('heure', TimeType::class, [
                'widget' => 'single_text'
            ])
            //->add('published')
            ->add('slug')
            ->add('titre')
            ->add('description')
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('heureDebut', TimeType::class, [
                'widget' => 'single_text'
            ])
            ->add('heureFin', TimeType::class, [
                'widget' => 'single_text'
            ])
            //->add('user')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                "choice_label" => "name",
                'multiple'=> true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evenements::class,
        ]);
    }
}
