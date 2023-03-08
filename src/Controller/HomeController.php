<?php

namespace App\Controller;

use App\Repository\BannerRepository;
use App\Repository\NewsRepository;
use App\Repository\SecteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(
        BannerRepository $bannerRepository,
        SecteurRepository $secteurRepository,
        NewsRepository $newsRepository
    ): Response {

        $banners = $bannerRepository->getAcitveBanners();
        $secteurs = $secteurRepository->getActiveSecteurs(7);
        $news = $newsRepository->getActiveNews(4);
        return $this->render('home/index.html.twig', compact('banners', 'secteurs', 'news'));
    }

    public function liensUtiles(SecteurRepository $secteurRepository): Response
    {
        $secteurs = $secteurRepository->getActiveSecteurs(7);
        return $this->render('layouts/partials/_liens_utiles.html.twig', compact('secteurs'));
    }
}
