<?php

namespace App\Controller\Admin;

use App\Entity\Etablissement;
use App\Entity\News;
use App\Entity\Secteur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(EtablissementCrudController::class));
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Après bac');
    }

    public function configureMenuItems(): iterable
    {

        yield MenuItem::section();
        yield MenuItem::linkToUrl('Visit public website', 'fa fa-home', '/');
        yield MenuItem::linkToCrud('Etablissements', 'fas fa-graduation-cap', Etablissement::class);
        yield MenuItem::linkToCrud('Actualités', 'fas fa-graduation-cap', News::class);
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-users', News::class);
        yield MenuItem::linkToCrud('Secteurs', 'fas fa-list', Secteur::class);
    }
}
