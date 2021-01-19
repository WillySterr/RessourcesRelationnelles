<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\Avatars;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName')
            ->add('firstName')
            ->add('age')
            ->add('mail')
            ->add('phone')
            ->add('password', PasswordType::class)
            ->add('passwordVerification', PasswordType::class)
            ->add('description', TextareaType::class)
            ->add('job');
            // ->add('avatar', EntityType::class, [
            //     'class' => Avatars::class,
            //     'choice_label' => function($avatar){
            //         return $avatar;
            //     },
            //     "label" => "Avatar",
            //     'multiple'=> false,
            //     'expanded'=>true
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
