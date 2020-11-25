<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Informations;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InformationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('heure')
            ->add('published')
            ->add('titre')
            ->add('description')
            ->add('contenu')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                "choice_label" => "name",
                'multiple'=> true,
                'expanded' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Informations::class,
        ]);
    }
}
