<?php

namespace App\Controller\Admin;

use App\Entity\Ressources;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;


class RessourcesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ressources::class;
    }


    // public function configureCrud(Crud $crud): Crud
    // {
    //     return $crud
    //         // ...

    //         // don't forget to add EasyAdmin's form theme at the end of the list
    //         // (otherwise you'll lose all the styles for the rest of form fields)
    //         ->setFormThemes(['news_feed\index.html.twig', '@EasyAdmin/crud/form_theme.html.twig'])
    //     ;
    // }
 
    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id')->hideOnIndex(),
            TextField::new('title'),
            AssociationField::new('user')->hideOnForm(),
            AssociationField::new('article'),
            //ImageField::new('photo.image')->setFormType(PhotoType::class),
            AssociationField::new('video'),
            //AssociationField::new('evenement'),
            AssociationField::new('article'),
            BooleanField::new('published'),
            AssociationField::new('comments'),
            //TextEditorField::new('description'),
        ];
    }
  
}
