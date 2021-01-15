<?php

namespace App\Controller\Admin;

use App\Entity\Ressources;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;


class RessourcesCrudController extends AbstractCrudController
{

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
           
        ;
    }

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
       
        $comForm = ArrayField::new('comments');
        $comInd = AssociationField::new('comments');
        $fields = [
            //IdField::new('id')->hideOnIndex(),
            TextField::new('title')->hideOnIndex(),
            AssociationField::new('user')->hideOnIndex(),
            AssociationField::new('article'),
            AssociationField::new('photo'),
            AssociationField::new('video'),
            AssociationField::new('evenement'),
            AssociationField::new('information'),
            BooleanField::new('published'),
            
            //TextEditorField::new('description'),
        ];
        if ($pageName == Crud::PAGE_INDEX){
            $fields[] = $comInd;
        }else{
            $fields[] = $comForm;
        }


        return $fields;
    }
  
}
