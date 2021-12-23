<?php

namespace App\Service;

use App\Repository\ActualiteRepository;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;


class DocumentsHelper 
{
    private $absolutePathFolderDocuments = DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'documents';

    const PATHDOCUMENTS = 'uploads/documents/';
    const DEFAULTIMAGENEWS = 'slide_news_without_image';

    private DocumentRepository $docRepo;
    private ActualiteRepository $actualiteRepository;
    private ActualiteHelper $actualiteHelper;
    private EntityManagerInterface $em;

    public function __construct(DocumentRepository $docRepo, ActualiteRepository $actualiteRepository, ActualiteHelper $actualiteHelper, EntityManagerInterface $em)
    {
        $this->docRepo =$docRepo;
        $this->actualiteRepository = $actualiteRepository;
        $this->actualiteHelper = $actualiteHelper;
        $this->em = $em;
    }

    /**
     * Retrieve all images posted in the Actualites
     * @return array
     */
    public function getAllUploadedImages() : array
    {
        $finder = new Finder;
        $aImages = $aImgActualite = array();
        $status = 'OK';
        $allActualites = $this->actualiteRepository->findAll();
        $actualiteImages = $this->actualiteHelper->getPlainTextActualite($allActualites);
        
        foreach ($actualiteImages as $img) {
            array_push($aImgActualite, substr($img['src'], 1));
        }
        $aExtensionImages = array('jpg', 'jpeg', 'gif', 'png');
        $finder->files()->in(dirname(dirname(__DIR__)) . $this->absolutePathFolderDocuments);

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
                $fileLink['src'] = self::PATHDOCUMENTS . $fileNameWithExtension;
                $fileLink['isLocked'] = in_array($fileLink['src'], $aImgActualite) ? true : false;
                array_push($aImages, $fileLink);
            }
        }

        return compact("aImages", "status");
    }

    /**
     * Delete an image by its name
     * Retrieve the image from the "documents" folder and delete it from the DB and and the folder, if it exists.
     * @param string $imageName
     * @return void
     */
    public function deleteOneImageByName(string $imageName) : void
    {
        
        $doc_name = str_replace(self::PATHDOCUMENTS, '', $imageName);
        $filesystem = new Filesystem();
        $document = $this->docRepo->findBy(['name' => $doc_name])[0];
        $file = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . $document->getPath();
        if ($filesystem->exists($file)) {
            $filesystem->remove($file);
        }
        $this->em->remove($document);
        $this->em->flush();
    }
}