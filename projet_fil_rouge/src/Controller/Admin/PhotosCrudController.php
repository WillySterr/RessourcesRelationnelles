<?php

namespace App\Controller\Admin;

use App\Entity\Photos;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class PhotosCrudController extends AbstractCrudController
{
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
        ;
    }

    public static function getEntityFqcn(): string
    {
        return Photos::class;
    }

   
    public function configureFields(string $pageName): iterable
    {

        $catForm = AssociationField::new('category', "Catégories");
            
        $catInd = ArrayField::new('category', "Catégories");

        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextEditorField::new('description'),//TextEditorField
            BooleanField::new('published', "Publié"),
            DateTimeField::new('createdAt', 'Publié le')->hideOnForm(),
            TextField::new('vichFile', "Sélectionnez votre photo")->setFormType(VichImageType::class)->hideOnIndex(),
            ImageField::new('image')->setBasePath('/images/vichFiles')->hideOnForm(),
            //DateTimeField::new('updatedAt'),
            /*->setUploadDir('public\uploads'),*/
        ];
        
        if ($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL){
            $fields[] = $catInd;
        }else{
            $fields[] = $catForm;
        }

        return $fields;
    }
    
}
