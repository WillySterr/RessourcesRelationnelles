<?php

namespace App\Form;

use App\Entity\Photos;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PhotosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('published')
            ->add('slug')
            ->add('titre')
            ->add('description')
            /*->add('createdAt')
            ->add('updatedAt')*/
            ->add('image', FileType::class,[
                'label' => 'Image',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            //->add('user')
            ->add('category', EntityType::class, [
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
            'data_class' => Photos::class,
        ]);
    }
}
