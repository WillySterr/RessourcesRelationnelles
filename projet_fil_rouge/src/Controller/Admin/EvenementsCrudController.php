<?php

namespace App\Controller\Admin;

use App\Entity\Evenements;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class EvenementsCrudController extends AbstractCrudController
{

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
        ;
    }

    public static function getEntityFqcn(): string
    {
        return Evenements::class;
    }

   
    public function configureFields(string $pageName): iterable
    {

        $catForm = AssociationField::new('category', "Catégories");
            
        $catInd = ArrayField::new('category', "Catégories");

        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextEditorField::new('description'),
            DateField::new('dateDebut', "Date de début"),
            DateField::new('dateFin', "Date de fin"),
            TimeField::new('heureDebut', "Heure de début"),
            TimeField::new('heureFin', "Heure de fin"),
            DateTimeField::new('CreatedAt', 'Publié le')->hideOnForm(),
            BooleanField::new('published', "Publié"),

        ];
        if ($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL){
            $fields[] = $catInd;
        }else{
            $fields[] = $catForm;
        }
        return $fields;
    }
  
}
