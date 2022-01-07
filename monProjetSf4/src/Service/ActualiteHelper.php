<?php

namespace App\Service;
use App\Entity\Actualite;
use Doctrine\ORM\EntityManagerInterface;
use DOMXPath;
use Exception;
use UnexpectedValueException;

class ActualiteHelper {
    
    const PATHDEFAULTIMAGE = "/uploads/documents/slide_news_without_image.png";
    /** @var EntityManagerInterface */
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function getPlainTextActualite(array $articles) : array
    {
        $aArticlesCarousel = $match = array();
        foreach ($articles as $article) {
            preg_match('/(<img[^>]+>)/i', $article->getMessage(), $match);
            if (!empty($match[0])) {
                $doc = new \DOMDocument();
                $doc->loadHTML($match[0]);
                $xpath = new DOMXPath($doc);
                $src = $xpath->evaluate("string(//img/@src)");
            }
            $art['src'] = empty($match[0]) ? self::PATHDEFAULTIMAGE : $src;
            $art['title'] = html_entity_decode(substr($article->getTitre(), 0, 500));
            $art['sub-title'] = html_entity_decode(substr(strip_tags($article->getMessage()), 0, 500));
            $art['id-article'] = $article->getId();
            $art['slug'] = $article->getSlug();

            array_push($aArticlesCarousel, $art);
        }
        return $aArticlesCarousel;
    }

    public function processChangeStatusActualite(int $actualite_id)
    {            
        $actualite = $this->em->getRepository(Actualite::class)->find($actualite_id);
        if(!$actualite) {
            throw new UnexpectedValueException("L'identifiant de l'objet transmis n'est pas valide!");
        }
        try {
            $old_statut = $actualite->getApprobation();
            $new_statut = !$old_statut;
            $actualite->setApprobation($new_statut);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new Exception('Le processus de changement de statut a échoué');
        }
    }
}