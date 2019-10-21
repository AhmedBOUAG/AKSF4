<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index()
    {
        $aImagesCarousel = array(
            'image1' => 'https://www.pets4homes.co.uk/images/articles/3160/large/do-all-cats-hate-dogs-55d06e4c12564.jpg',
            'image2' => 'https://www.westpac.co.nz/assets/Red-News/_resampled/FillWyI3NjgiLCIzODQiXQ/Kitten.jpg',
            'image3' => 'https://img.huffingtonpost.com/asset/5cd371422400005500a9647d.jpeg?ops=scalefit_630_noupscale',
            'image4' => 'http://img.over-blog-kiwi.com/1020x765/1/02/87/07/20170627/ob_d81ac6_141204-u79zy-rci-catanddog-sn635.jpg',
            'image5' => 'https://file1.topsante.com/var/topsante/storage/images/1/3/1/6/1316006/la-salive-des-chiens-chats-dangereuse-pour-homme.jpg'
        );
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'images' => $aImagesCarousel
        ]);
    }
}
