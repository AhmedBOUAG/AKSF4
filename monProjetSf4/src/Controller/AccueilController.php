<?php

namespace App\Controller;

use App\Repository\ActualiteRepository;
use App\Service\ActualiteHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController 
{

    private ActualiteRepository $actualiteRepository;
    private ActualiteHelper $actualiteHelper;

    public function __construct(
        ActualiteHelper $actualiteHelper, 
        ActualiteRepository $actualiteRepository
        )
    {
        $this->actualiteRepository = $actualiteRepository;
        $this->actualiteHelper = $actualiteHelper;
    }
    /**
     * @Route("/", name="accueil_home")
     */
    public function index() {
         
        $response = new Response($this->render('accueil/index.html.twig', [
                    'images' => $this->lastFiveArticles(),
        ])->getContent());
        $response->setSharedMaxAge(3600);
        return $response;
    }

    public function AlerteInfo() {

        $response =  new Response($this->render('accueil/_alerte-info.html.twig', [
                    'lastFiveInfos' => $this->lastFiveArticles()
        ])->getContent());

        $response->setSharedMaxAge(3600);

        return $response;
    }

    public function lastFiveArticles() {
        $aLastFiveArticles = $this->actualiteHelper->getPlainTextActualite($this->actualiteRepository->getLastFiveArticles());

        return $aLastFiveArticles;
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
