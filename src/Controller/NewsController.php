<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * @Route("/news", name="app_news")
     */
    public function index(Request $request, NewsRepository $newsRepository, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', 1);

        $query = $newsRepository->createAllActiveNewsQuery();

        $pagination = $paginator->paginate(
            $query,
            $page,
            News::NUM_ITEMS_PER_PAGE
        );

        return $this->render('news/index.html.twig', compact('pagination'));
    }
}
