<?php

namespace App\Controller;

use App\Entity\DocumentEtablissement;
use App\Entity\Etablissement;
use App\Form\SearchEtablissementType;
use App\Repository\EtablissementRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EtablissementController extends AbstractController
{

    /**
     * @Route("/etablissement", name="app_etablissement")
     */
    public function index(
        Request $request,
        EtablissementRepository $etablissementRepository,
        PaginatorInterface $paginator
    ): Response {

        $form = $this->createForm(SearchEtablissementType::class, null, ['method' => 'GET']);
        $form->handleRequest($request);
        $mots = "";
        $isEtranger = false;

        $query = $etablissementRepository->createAllEtablissementQuery($isEtranger);
        $page = $request->query->getInt('page', 1);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $mots = $data["mots"];
            $query = $etablissementRepository->createSearchEtablissementQuery($mots, $isEtranger);
        }

        $pagination = $paginator->paginate(
            $query,
            $page,
            Etablissement::NUM_ITEMS_PER_PAGE
        );

        return $this->renderForm('etablissement/index.html.twig', compact('form', 'pagination', 'mots', 'isEtranger'));
    }

    /**
     * @Route("/etudier_etranger", name="app_etablissement_etudier_etranger")
     */
    public function etranger(
        Request $request,
        EtablissementRepository $etablissementRepository,
        PaginatorInterface $paginator
    ): Response {

        $form = $this->createForm(SearchEtablissementType::class, null, ['method' => 'GET']);
        $form->handleRequest($request);
        $mots = "";
        $isEtranger = true;

        $query = $etablissementRepository->createAllEtablissementQuery($isEtranger);
        $page = $request->query->getInt('page', 1);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $mots = $data["mots"];
            $query = $etablissementRepository->createSearchEtablissementQuery($mots, $isEtranger);
        }

        $pagination = $paginator->paginate(
            $query,
            $page,
            Etablissement::NUM_ITEMS_PER_PAGE
        );

        return $this->renderForm('etablissement/index.html.twig', compact('form', 'pagination', 'mots', 'isEtranger'));
    }

    /**
     * @Route("/doc_etablissement/{id}", name="app_etablissement_doc", requirements={"id":"\d+"})
     */
    public function getDocument(DocumentEtablissement $documentEtablissement, Packages $manager)
    {
        // Get the PDF file path.
        $pdfPath = 'uploads/etablissement_docs/' . $documentEtablissement->getFile();
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
