<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('slug')
            ->add('titre')
            ->add('description')
            ->add('video',FileType::class,[
                'label' => 'Vidéo',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('photo',FileType::class,[
                'label' => 'Image',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('category',EntityType::class, [
                'class' => Category::class,
                "choice_label" => "name",
                'multiple'=> true,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
