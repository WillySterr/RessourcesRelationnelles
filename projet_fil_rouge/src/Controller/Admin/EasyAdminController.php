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
        MenuItem::linkToDashboard('Home', 'fa fa-home'),
        //MenuItem::linkToLogout('Logout', 'fa fa-exit'),
        MenuItem::section('Ressources et catégories'),
        MenuItem::linkToCrud('Ressources', 'fa fa-book', Ressources::class)
            ->setController(RessourcesCrudController::class),
        MenuItem::linkToCrud('Catégories', 'fa fa-book', Category::class)
            ->setController(CategoryCrudController::class),
        MenuItem::linkToCrud('Comments', 'fa fa-book', Category::class)
            ->setController(CommentsCrudController::class),

        MenuItem::section('Fil d\'actualité'),

        MenuItem::linkToRoute('Fil d\'actu', 'fa fa-cube', 'news_feed'),
        ];
    }
}
