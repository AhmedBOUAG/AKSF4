<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use \Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/{idArticle}", name="article")
     */
    public function index(Request $request)
    {
        $id = $request->attributes->get('idArticle');
        
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'id_article' => $id,
        ]);
    }
}
