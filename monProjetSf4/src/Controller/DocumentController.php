<?php

namespace App\Controller;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping as ORM;
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
        $em = $this->getDoctrine()->getManager();
        
        $document = new Document();
        $media = $request->files->get('file');
        $document->setFile($media);
        $document->setName($media->getClientOriginalName());
        $document->setPath($document->getWebPath());
        $document->upload();
        $em->persist($document);
        $em->flush();


        return new Response('succes');
    }
    
    /**
     * @param request
     * @Route("/ajax/snippet/image/delete", name="ajax_snippet_image_delete")
     * @return response
     */    
    public function ajaxSnippetImageDelete(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $doc_name = $request->get('name');
        $filesystem = new Filesystem();
        $document = $em->getRepository(Document::class)->findBy(['name' => $doc_name])[0];
        $file = dirname(dirname(__DIR__)). DIRECTORY_SEPARATOR . $document->getPath();
        if($filesystem->exists($file)){
            $filesystem->remove($file);
        }
        $em->remove($document);
        $em->flush();
        
        return new Response('Supprimé avec succès');
        
    }
}
