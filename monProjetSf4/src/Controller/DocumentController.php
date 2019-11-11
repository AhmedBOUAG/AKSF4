<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping as ORM;
//use AppBundle\Entity\Document;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Document;

class DocumentController extends AbstractController
{
    /**
     *
     * @Route("/ajax/snippet/image/send", name="ajax_snippet_image_send")
     */
    public function ajaxSnippetImageSend(Request $request): Response
    {
        //$em = $this->container->get("doctrine.orm.default_entity_manager");
        $em = $this->getDoctrine()->getManager();
        //dump($request->files->get('file'));die;
        $document = new Document();
        $media = $request->files->get('file');
        //dump($document->getUploadRootDir());
        $document->setFile($media);
        $document->setName($media->getClientOriginalName());
        $document->setPath($document->getWebPath());
        $document->upload();
        $em->persist($document);
        $em->flush();

        //infos sur le document envoyé
        //var_dump($request->files->get('file'));die;
        return new Response('succes');
    }
}
