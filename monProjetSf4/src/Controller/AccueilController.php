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
    public function index(Request $request, ActualiteHelper $actualiteHelper) {
        $em = $this->getDoctrine()->getManager();
        $lastFiveArticles = $em->getRepository(Actualite::class)->getLastFiveArticles();
        $aImagesCarousel = $actualiteHelper->getPlainTextActualite($lastFiveArticles);
        
        return $this->render('accueil/index.html.twig', [
                    'images' => $aImagesCarousel
        ]);
    }
}
