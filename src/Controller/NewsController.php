<?php

namespace App\Controller;

use App\Entity\DocNews;
use App\Entity\News;
use App\Repository\NewsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\File\File;
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

    /**
     * @Route("/doc_news/{id}", name="app_news_doc", requirements={"id":"\d+"})
     */
    public function getDocument(DocNews $docNews, Packages $manager)
    {
        // Get the PDF file path.
        $pdfPath = 'uploads/news_docs/' . $docNews->getFile();
        // Create a new modal.
        $modal = new Response();
        $modal->setStatusCode(200);
        $modal->headers->set('Content-Type', $this->getMimeTypeFromPath($pdfPath));
        $modal->setContent(file_get_contents($pdfPath));

        // Return the modal.
        return $modal;
    }

    public function getMimeTypeFromPath(string $path)
    {
        // Create a new File object from the path.
        $file = new File($path);

        // Get the mime type from the file.
        $mimeType = $file->getMimeType();

        // Return the mime type.
        return $mimeType;
    }
}
