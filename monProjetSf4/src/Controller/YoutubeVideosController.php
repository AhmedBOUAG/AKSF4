<?php

namespace App\Controller;

use App\Entity\YoutubeVideos;
use App\Form\YoutubeVideosType;
use App\Repository\YoutubeVideosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/youtube/videos")
 */
class YoutubeVideosController extends AbstractController {

    /**
     * @Route("/", name="youtube_videos_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(YoutubeVideosRepository $youtubeVideosRepository): Response {
        return $this->render('youtube_videos/index.html.twig', [
                    'youtube_videos' => $youtubeVideosRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="youtube_videos_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response {
        $youtubeVideo = new YoutubeVideos();
        $form = $this->createForm(YoutubeVideosType::class, $youtubeVideo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $only_id_video = substr(stristr($youtubeVideo->getLinkYoutube(), '='), 1, 11);
            $youtubeVideo->setLinkYoutube($only_id_video);
            $entityManager->persist($youtubeVideo);
            $entityManager->flush();

            return $this->redirectToRoute('youtube_videos_index');
        }

        return $this->render('youtube_videos/new.html.twig', [
                    'youtube_video' => $youtubeVideo,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="youtube_videos_show", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(YoutubeVideos $youtubeVideo): Response {
        return $this->render('youtube_videos/show.html.twig', [
                    'youtube_video' => $youtubeVideo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="youtube_videos_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, YoutubeVideos $youtubeVideo): Response {
        $form = $this->createForm(YoutubeVideosType::class, $youtubeVideo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('youtube_videos_index');
        }

        return $this->render('youtube_videos/edit.html.twig', [
                    'youtube_video' => $youtubeVideo,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("del/{id}", name="youtube_videos_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, YoutubeVideos $youtubeVideo): Response {
        if ($this->isCsrfTokenValid('delete' . $youtubeVideo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($youtubeVideo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('youtube_videos_index');
    }
    
    /**
     * @param Request $request
     * @Route("/watch/{id}", name="watch_video", methods={"GET"})
     */
    public function watch(Request $request, YoutubeVideos $youtubeVideo) {
        return $this->render('youtube_videos/watch.html.twig', [
            'video' => $youtubeVideo
        ]);
    }
    public function ThumbnailsVideos(Request $request){
        $em = $this->getDoctrine()->getManager();
        $lastVideos = $em->getRepository(YoutubeVideos::class)->findLastThreeVideos();
        
        return $this->render('youtube_videos/thumbnailsVideos.html.twig', [
            'youtubeVideos' => $lastVideos
        ]);
    }
    

}
