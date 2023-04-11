<?php

namespace App\Controller\Admin;

use App\Entity\Banner;
use App\Entity\Contact;
use App\Entity\DocumentEtablissement;
use App\Entity\Etablissement;
use App\Entity\News;
use App\Entity\PackNormal;
use App\Entity\PackSpecial;
use App\Entity\Secteur;
use App\Entity\Tags;
use App\Entity\User;
use App\Entity\Ville;
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
            ->setTitle('ADMINSTRATION');
    }

    public function configureMenuItems(): iterable
    {


        yield MenuItem::section();
        yield MenuItem::linkToUrl('Visit public website', 'fa fa-home', '/');
        yield MenuItem::section("Etablissemnt");
        yield MenuItem::linkToCrud('Etablissements', 'fas fa-graduation-cap', Etablissement::class);
        yield MenuItem::linkToCrud('Docs. Etablissement', 'fas fa-folder-open', DocumentEtablissement::class);
        yield MenuItem::section("Actualités");
        yield MenuItem::linkToCrud('Actualités', 'fas fa-newspaper', News::class);
        yield MenuItem::section("Utilisateur");
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-users', User::class)->setPermission("ROLE_SUPER_ADMIN");
        yield MenuItem::section("Bannières / configuration");
        yield MenuItem::linkToCrud('Bannières', 'fas fa-tv', Banner::class)->setPermission("ROLE_ADMIN");
        yield MenuItem::linkToCrud('Secteurs', 'fas fa-list', Secteur::class)->setPermission("ROLE_ADMIN");
        yield MenuItem::linkToCrud('Villes', 'fas fa-city', Ville::class)->setPermission("ROLE_ADMIN");
        yield MenuItem::linkToCrud('Tags', 'fas fa-tags', Tags::class)->setPermission("ROLE_ADMIN");

        yield MenuItem::section("Inscription / Contact")->setPermission("ROLE_ADMIN");
        yield MenuItem::linkToCrud('Contact', 'fas fa-envelope', Contact::class)->setPermission("ROLE_ADMIN");
        yield MenuItem::linkToCrud('Packs Normal', 'fas fa-star-half', PackNormal::class)->setPermission("ROLE_ADMIN");
        yield MenuItem::linkToCrud('PacKs Special', 'fas fa-star', PackSpecial::class)->setPermission("ROLE_ADMIN");
    }
}
