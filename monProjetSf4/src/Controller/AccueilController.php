<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Actualite;
use App\Service\ActualiteHelper;

class AccueilController extends AbstractController {

    /**
     * @Route("/", name="accueil_home")
     */
    public function index(Request $request) {
        return $this->render('accueil/index.html.twig', [
                    'images' => $this->lastFiveArticles()
        ]);
    }

    public function AlerteInfo() {
        return $this->render('accueil/alerte-info.html.twig', [
                    'lastFiveInfos' => $this->lastFiveArticles()
        ]);
    }

    public function lastFiveArticles() {
        $actualiteHelper = new ActualiteHelper();
        $em = $this->getDoctrine()->getManager();
        $lastFiveArticles = $em->getRepository(Actualite::class)->getLastFiveArticles();
        $aLastFiveInfos = $actualiteHelper->getPlainTextActualite($lastFiveArticles);
        return $aLastFiveInfos;
    }
    
    /**
     * @param request 
     * @Route("/developpement", name="page_taqafa")
     * @return Response
     */
    public function pageTaqafa(Request $request)
    {
        return $this->render('accueil/page-taqafa.html.twig');
    }

    /**
     * @param request 
     * @Route("/services", name="page_services")
     * @return Response
     */
    public function pageServices(Request $request)
    {
        return $this->render('accueil/page-services.html.twig');
    }
}
