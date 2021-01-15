<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use App\Entity\Ressources;

class EasyAdminController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(RessourcesCrudController::class)->generateUrl());
        // return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Ressources Relationnelles');
    }

    //setPermission(string $permission), sets the Symfony security permission that the user must have to see this menu item. Read the menu security reference for more details.


    public function configureMenuItems(): iterable
    {
        return [
        MenuItem::linkToDashboard('Accueil', 'fa fa-home'),
        //MenuItem::linkToLogout('Logout', 'fa fa-exit'),
        MenuItem::section('Ressources et Commentaires'),

        /*MenuItem::linkToCrud('Ressources', 'fa fa-book', Ressources::class)
            ->setController(RessourcesCrudController::class),
        */

        MenuItem::linkToCrud('Evènements', 'fa fa-calendar', Evenements::class)
            ->setController(EvenementsCrudController::class),

        MenuItem::linkToCrud('Informations', 'fa fa-book', Informations::class)
            ->setController(InformationsCrudController::class),

        MenuItem::linkToCrud('Photos', 'fa fa-photo', Photos::class)
            ->setController(PhotosCrudController::class),

        MenuItem::linkToCrud('Videos', 'fa fa-video', Videos::class)
            ->setController(VideosCrudController::class),

        MenuItem::linkToCrud('Articles', 'fa fa-file-word-o', Articles::class)
            ->setController(ArticlesCrudController::class),

        MenuItem::linkToCrud('Commentaires', 'fa fa-comment', Comments::class)
            ->setController(CommentsCrudController::class),

        MenuItem::section('Catégories'),
        MenuItem::linkToCrud('Catégories', 'fa fa-book', Category::class)
            ->setController(CategoryCrudController::class),

        MenuItem::section('Fil d\'actualité'),

        MenuItem::linkToRoute('Fil d\'actu', 'fa fa-cube', 'news_feed'),
        ];
    }
}
