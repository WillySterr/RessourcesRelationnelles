<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

 
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', "Nom"),
            TextEditorField::new('description'),
            AssociationField::new('articles')->hideOnForm(),
            AssociationField::new('evenements', "Evènements")->hideOnForm(),
            AssociationField::new('informations')->hideOnForm(),
            AssociationField::new('photos')->hideOnForm(),
            AssociationField::new('videos')->hideOnForm(),
        ];
    }
 
}
