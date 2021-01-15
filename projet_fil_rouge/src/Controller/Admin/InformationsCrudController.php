<?php

namespace App\Controller\Admin;

use App\Entity\Informations;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class InformationsCrudController extends AbstractCrudController
{
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Crud::PAGE_EDIT, Action::EDIT)
        ;
    }

    public static function getEntityFqcn(): string
    {
        return Informations::class;
    }

   
    public function configureFields(string $pageName): iterable
    {
        $catForm = AssociationField::new('category');
            
        $catInd = ArrayField::new('category');

        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextEditorField::new('description'),
            TextEditorField::new('contenu'),
            DateTimeField::new('createdAt')->hideOnForm(),
            DateTimeField::new('updatedAt')->hideOnForm(),
            BooleanField::new('published'),
            AssociationField::new('category'),

        ];
        if ($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL){
            $fields[] = $catInd;
        }else{
            $fields[] = $catForm;
        }
        return $fields;
    }
  
}
