<?php

namespace App\Service;

class ActualiteHelper {
    
    const PATHDEFAULTIMAGE = "/uploads/documents/slide_news_without_image.png";
    
    public function getPlainTextActualite(array $articles) {
        $aImagesCarousel = array();
        foreach ($articles as $article) {
                preg_match('/(<img[^>]+>)/i', $article->getMessage(), $match);
                if (!empty($match[0])) {
                    $doc = new \DOMDocument();
                    $doc->loadHTML($match[0]);
                    $xpath = new \DOMXPath($doc);
                    $src = $xpath->evaluate("string(//img/@src)");
                }
                $image['src'] = empty($match[0]) ? self::PATHDEFAULTIMAGE : $src;
                $image['title'] = html_entity_decode(substr($article->getTitre(), 0, 500));
                $image['sub-title'] = html_entity_decode(substr(strip_tags($article->getMessage()), 0, 500));
                $image['id-article'] = $article->getId();

                array_push($aImagesCarousel, $image);
            }
            return $aImagesCarousel;
    }
}

