<?php

namespace App\Controller;

use App\Entity\Etablissement;
use App\Form\SearchEtablissementType;
use App\Repository\BannerRepository;
use App\Repository\EtablissementRepository;
use App\Repository\NewsRepository;
use App\Repository\SecteurRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(
        Request $request,
        BannerRepository $bannerRepository,
        SecteurRepository $secteurRepository,
        NewsRepository $newsRepository,
        EtablissementRepository $etablissementRepository,
        PaginatorInterface $paginator
    ): Response {

        $banners = $bannerRepository->getAcitveBanners();
        $secteurs = $secteurRepository->getActiveSecteurs(7);
        $news = $newsRepository->getActiveNews(4);

        $form = $this->createForm(SearchEtablissementType::class, null, ['method' => 'GET']);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {


            $data = $form->getData();
            $mots = $data["mots"];

            $page = $request->query->getInt('page', 1);

            $query = $etablissementRepository->createSearchEtablissementQuery($mots);

            $pagination = $paginator->paginate(
                $query,
                $page,
                Etablissement::NUM_ITEMS_PER_PAGE
            );

            return $this->renderForm('search/index.html.twig', compact('form', 'pagination', 'mots'));
        }
        return $this->renderForm('home/index.html.twig', compact('banners', 'secteurs', 'news', 'form'));
    }


    /**
     * @Route("/search", name="app_search")
     */
    public function sarch(
        Request $request,
        BannerRepository $bannerRepository,
        SecteurRepository $secteurRepository,
        NewsRepository $newsRepository,
        EtablissementRepository $etablissementRepository,
        PaginatorInterface $paginator
    ): Response {

        $form = $this->createForm(SearchEtablissementType::class, null, ['method' => 'GET']);
        $form->handleRequest($request);

        $query = $etablissementRepository->createAllEtablissementQuery();
        $page = $request->query->getInt('page', 1);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $query = $etablissementRepository->createSearchEtablissementQuery($data["mots"]);
        }

        $pagination = $paginator->paginate(
            $query,
            $page,
            Etablissement::NUM_ITEMS_PER_PAGE
        );

        return $this->renderForm('search/index.html.twig', compact('form', 'pagination'));
    }


    public function liensUtiles(SecteurRepository $secteurRepository): Response
    {
        $secteurs = $secteurRepository->getActiveSecteurs(7);
        return $this->render('layouts/partials/_liens_utiles.html.twig', compact('secteurs'));
    }
}
