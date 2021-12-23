<?php

namespace App\Controller;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ActualiteHelper;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Document;
use App\Repository\DocumentRepository;
use App\Entity\Actualite;
use App\Repository\ActualiteRepository;
use App\Service\DocumentsHelper;
use Doctrine\ORM\EntityManagerInterface;

class DocumentController extends AbstractController {

    /**
     *
     * @param Request $request
     * @Route("/ajax/snippet/image/send", name="ajax_snippet_image_send")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function ajaxSnippetImageSend(Request $request): Response {
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
     * @param DocumentHelper $docHelper
     * @Route("/ajax/snippet/image/delete", name="ajax_snippet_image_delete")
     * @IsGranted("ROLE_ADMIN")
     * @return response
     */
    public function ajaxSnippetImageDelete(Request $request, DocumentsHelper $docHelper): Response {

        $docHelper->deleteOneImageByName($request->get('name'));
        return $request->get('ListThumbnailsUploadeds') ? $this->redirectToRoute('get_images_uploaded', $request->query->all()) : new Response('Image Supprimée');
    }

    /**
     * @param request
     * @Route("/album", name="get_images_uploaded")
     * @IsGranted("ROLE_ADMIN")
     * @return response
     */
    public function getImagesUploaded(DocumentsHelper $docHelper): Response {
        $returnedValues = $docHelper->getAllUploadedImages();
        return $this->render('document/index.html.twig', [
                    'images' => $returnedValues['aImages'],
                    'status' => $returnedValues['status']
        ]);
    }

}
        
