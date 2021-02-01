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
       
    
        yield MenuItem::linkToDashboard('Accueil', 'fa fa-home');
        //yield MenuItem::linkToLogout('Logout', 'fa fa-exit');
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPERADMIN')) {
            yield MenuItem::section('Ressources');
        }
           
        /*yield MenuItem::linkToCrud('Ressources', 'fa fa-book', Ressources::class)
            ->setController(RessourcesCrudController::class);
        */

        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPERADMIN')) {
            yield MenuItem::linkToCrud('Evènements', 'fa fa-calendar', Evenements::class)
            ->setController(EvenementsCrudController::class);
        }

        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPERADMIN')) {
            yield MenuItem::linkToCrud('Informations', 'fa fa-book', Informations::class)
            ->setController(InformationsCrudController::class);
        }

        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPERADMIN')) {
            yield MenuItem::linkToCrud('Photos', 'fa fa-photo', Photos::class)
            ->setController(PhotosCrudController::class);
        }

        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPERADMIN')) {
            yield MenuItem::linkToCrud('Videos', 'fa fa-video', Videos::class)
            ->setController(VideosCrudController::class);
        }

        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPERADMIN')) {
            yield MenuItem::linkToCrud('Articles', 'fa fa-file-word-o', Articles::class)
            ->setController(ArticlesCrudController::class);
        }

        yield MenuItem::section('Commentaires');
        yield MenuItem::linkToCrud('Commentaires', 'fa fa-comment', Comments::class)
            ->setController(CommentsCrudController::class);

        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPERADMIN')) {
            yield MenuItem::section('Catégories');
        }

        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPERADMIN')) {
            yield MenuItem::linkToCrud('Catégories', 'fa fa-book', Category::class)
            ->setController(CategoryCrudController::class);
        }

        if ($this->isGranted('ROLE_SUPERADMIN')) {
            yield MenuItem::section('Gestion des Utilisateurs');
        }

        if ($this->isGranted('ROLE_SUPERADMIN')) {
            yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', Users::class)
            ->setController(UsersCrudController::class);
        }
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPERADMIN')) {
            yield MenuItem::linkToCrud('Avatars', 'fa fa-icon', Avatars::class)
            ->setController(AvatarsCrudController::class);
        }

      

        yield MenuItem::section('Fil d\'actualité');
        yield MenuItem::linkToRoute('Fil d\'actualité', 'fa fa-cube', 'news_feed');

        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPERADMIN')) {
            yield MenuItem::section('Statistiques');
            yield MenuItem::linkToUrl('Analytics', 'fa fa-chart-line', 'https://analytics.google.com/analytics/web/?authuser=2#/p259765856/reports/defaulthome?params=_u..nav%3Ddefault%26_u..pageSize%3D25%26_u..comparisons%3D%5B%7B%22name%22:%22Tous%20les%20utilisateurs%22,%22filters%22:%5B%7B%22isCaseSensitive%22:true,%22expression%22:%220%22,%22fieldName%22:%22audience%22%7D%5D%7D%5D');
        }
        ;

       
        
    }
}
