<?php

namespace App\Controller;

use App\Entity\Etablissement;
use App\Entity\Secteur;
use App\Repository\EtablissementRepository;
use App\Repository\SecteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class SecteursController extends AbstractController
{
    /**
     * @Route("/secteurs", name="app_secteurs")
     */
    public function index(): Response
    {
        return $this->render('secteurs/index.html.twig', [
            'controller_name' => 'SecteursController',
        ]);
    }

    /**
     * @Route("/secteurs/{slug}", name="app_secteurs_show", methods="GET")
     */
    public function show(Request $request, Secteur $secteur, PaginatorInterface $paginator, EtablissementRepository $etablissementRepository): Response
    {

        $page = $request->query->getInt('page', 1);

        $query = $etablissementRepository->createAllEtablissementBySecteurQuery($secteur);

        $pagination = $paginator->paginate(
            $query,
            $page,
            Etablissement::NUM_ITEMS_PER_PAGE
        );

        return $this->render('secteurs/show.html.twig', compact('secteur', 'pagination'));
    }
}
