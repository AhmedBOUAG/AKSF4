<?php

namespace App\Controller;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Document;

class DocumentController extends AbstractController {

    private $absolutePathFolderDocuments = DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'documents';

    /**
     *
     * @Route("/ajax/snippet/image/send", name="ajax_snippet_image_send")
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
     * @Route("/ajax/snippet/image/delete", name="ajax_snippet_image_delete")
     * @return response
     */
    public function ajaxSnippetImageDelete(Request $request): Response {
        $em = $this->getDoctrine()->getManager();
        $doc_name = $request->get('name');
        $filesystem = new Filesystem();
        $document = $em->getRepository(Document::class)->findBy(['name' => $doc_name])[0];
        $file = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . $document->getPath();
        if ($filesystem->exists($file)) {
            $filesystem->remove($file);
        }
        $em->remove($document);
        $em->flush();

        return new Response('Supprimé avec succès');
    }

    /**
     * @param request
     * @Route("/images/album", name="get_images_uploaded")
     * @return response
     */
    public function getImagesUploaded(Request $request): Response {
        $aImages = array();
        $finder = new Finder();

        $aExtensionImages = array('jpg', 'jpeg', 'gif', 'png');
        $finder->files()->in(dirname(dirname(__DIR__)) . $this->absolutePathFolderDocuments);

        // si aucun resultats n'est retourné
        if (!$finder->hasResults()) {
            return new Response('KO');
        }
        foreach ($finder as $file) {
            $explodeFileName = explode('.', $file->getRelativePathname());
            $extension = strtolower(end($explodeFileName));
            if (in_array($extension, $aExtensionImages)) {
                $absoluteFilePath = $file->getRealPath();
                $fileNameWithExtension = $file->getRelativePathname();
                array_push($aImages, $fileNameWithExtension);
            }
        }
        return $this->render('document/index.html.twig', [
                    'images' => $aImages
        ]);
    }

}
