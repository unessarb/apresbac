<?php

namespace App\Controller;

use App\Repository\BannerRepository;
use App\Repository\PartenaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        return $this->renderForm('home/index.html.twig');
    }

    public function carousel(BannerRepository $bannerRepository): Response
    {
        $banners = $bannerRepository->getAcitveBanners();
        return $this->render('layouts/partials/_carousel.html.twig', compact('banners'));
    }

    public function partenaires(PartenaireRepository $partenaireRepository): Response
    {
        $partenaires = $partenaireRepository->findAll();
        return $this->render('layouts/partials/_partenaires.html.twig', compact('partenaires'));
    }

    /**
     * @Route("/connection", name="app_connection")
     */
    public function connection(): Response
    {
        return $this->render('home/connection.html.twig');
    }
}
