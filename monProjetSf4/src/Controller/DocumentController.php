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

class DocumentController extends AbstractController {

    private $absolutePathFolderDocuments = DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'documents';

    const PATHDOCUMENTS = 'uploads/documents/';
    const DEFAULTIMAGENEWS = 'slide_news_without_image';

    /**
     *
     * @Route("/ajax/snippet/image/send", name="ajax_snippet_image_send")
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
     * @return response
     */
    public function ajaxSnippetImageDelete(Request $request, DocumentRepository $DocRepo): Response {
        $em = $this->getDoctrine()->getManager();
        $doc_name = str_replace(DocumentController::PATHDOCUMENTS, '', $request->get('name'));
        $filesystem = new Filesystem();
        $document = $DocRepo->findBy(['name' => $doc_name])[0];
        $file = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . $document->getPath();
        if ($filesystem->exists($file)) {
            $filesystem->remove($file);
        }
        $em->remove($document);
        $em->flush();

        return $request->get('ListThumbnailsUploadeds') ? $this->redirectToRoute('get_images_uploaded', $request->query->all()) : new Response('Image Supprimées');
    }

    /**
     * @param request
     * @Route("/album", name="get_images_uploaded")
     * @IsGranted("ROLE_ADMIN")
     * @return response
     */
    public function getImagesUploaded(ActualiteHelper $actualiteHelper): Response {
        $aImages = $aImgActualite = array();
        $finder = new Finder();
        $status = 'OK';
        $em = $this->getDoctrine()->getManager();
        $allActualites = $em->getRepository(Actualite::class)->findAll();
        $actualiteImages = $actualiteHelper->getPlainTextActualite($allActualites);
        
        foreach ($actualiteImages as $img) {
            array_push($aImgActualite, substr($img['src'], 1));
        }
        $aExtensionImages = array('jpg', 'jpeg', 'gif', 'png');
        $finder->files()->in(dirname(dirname(__DIR__)) . $this->absolutePathFolderDocuments);

        // si aucun resultats n'est retourné
        if (!$finder->hasResults()) {
            $status = 'KO';
        }
        foreach ($finder as $file) {
            $explodeFileName = explode('.', $file->getRelativePathname());
            $extension = strtolower(end($explodeFileName));
            if (in_array($extension, $aExtensionImages) &&
                    reset($explodeFileName) !== self::DEFAULTIMAGENEWS) {
                $absoluteFilePath = $file->getRealPath();
                $fileNameWithExtension = $file->getRelativePathname();
                $fileLink['src'] = DocumentController::PATHDOCUMENTS . $fileNameWithExtension;
                $fileLink['isLocked'] = in_array($fileLink['src'], $aImgActualite) ? true : false;
                array_push($aImages, $fileLink);
            }
        }
        return $this->render('document/index.html.twig', [
                    'images' => $aImages,
                    'status' => $status
        ]);
    }

}
