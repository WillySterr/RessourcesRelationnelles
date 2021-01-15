<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\CategoryRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class ArticlesCrudController extends AbstractCrudController
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
        return Articles::class;
    }

    public function __construct(CategoryRepository $categoryRepository){
        
        $this->categoryRepository = $categoryRepository;

    }
   
    public function configureFields(string $pageName): iterable
    {

      $allCat = $this->categoryRepository->findAll();
      $test = [];
      for ($i=0 ; $i<count($allCat) ; $i++){
        array_push($test, $allCat[$i]);
      }
      //dd($allCat[0]->getName());
        $catForm = AssociationField::new('category');
            
        $catInd = ArrayField::new('category');

        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextEditorField::new('description'),//TextEditorField
            BooleanField::new('published'),
            DateTimeField::new('createdAt', 'Publié le :')->hideOnForm(),
            TextField::new('photoFile')->setFormType(VichImageType::class)->hideOnIndex(),
            ImageField::new('photo')->setBasePath('/images/vichFiles')->hideOnForm(),
            TextField::new('videoFile')->setFormType(VichImageType::class)->hideOnIndex(),
            ImageField::new('video')->setBasePath('/images/vichFiles')->hideOnForm(),
            
           
            //DateTimeField::new('updatedAt'),
            /*->setUploadDir('public\uploads'),*/
        ];

        if ($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL ){
            $fields[] = $catInd;
        }else{
            $fields[] = $catForm;
        }
        return $fields;
    }
    
}
