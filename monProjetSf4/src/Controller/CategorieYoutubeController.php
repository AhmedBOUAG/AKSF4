<?php

namespace App\Controller;

use App\Entity\CategorieYoutube;
use App\Form\CategorieYoutubeType;
use App\Repository\CategorieYoutubeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categorie/youtube")
 */
class CategorieYoutubeController extends AbstractController
{
    /**
     * @Route("/", name="categorie_youtube_index", methods={"GET"})
     */
    public function index(CategorieYoutubeRepository $categorieYoutubeRepository): Response
    {
        return $this->render('categorie_youtube/index.html.twig', [
            'categorie_youtubes' => $categorieYoutubeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="categorie_youtube_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $categorieYoutube = new CategorieYoutube();
        $form = $this->createForm(CategorieYoutubeType::class, $categorieYoutube);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorieYoutube);
            $entityManager->flush();

            return $this->redirectToRoute('categorie_youtube_index');
        }

        return $this->render('categorie_youtube/new.html.twig', [
            'categorie_youtube' => $categorieYoutube,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_youtube_show", methods={"GET"})
     */
    public function show(CategorieYoutube $categorieYoutube): Response
    {
        return $this->render('categorie_youtube/show.html.twig', [
            'categorie_youtube' => $categorieYoutube,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categorie_youtube_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CategorieYoutube $categorieYoutube): Response
    {
        $form = $this->createForm(CategorieYoutubeType::class, $categorieYoutube);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categorie_youtube_index');
        }

        return $this->render('categorie_youtube/edit.html.twig', [
            'categorie_youtube' => $categorieYoutube,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_youtube_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CategorieYoutube $categorieYoutube): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieYoutube->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categorieYoutube);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_youtube_index');
    }
}
