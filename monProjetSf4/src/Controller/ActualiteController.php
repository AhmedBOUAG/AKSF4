<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Form\ActualiteType;
use App\Repository\ActualiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/actualite")
 */
class ActualiteController extends AbstractController
{
    /**
     * @Route("/", name="actualite_index", methods={"GET"})
     */
    public function index(ActualiteRepository $actualiteRepository): Response
    {
        return $this->render('actualite/index.html.twig', [
            'actualites' => $actualiteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="actualite_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $actualite = new Actualite();
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($actualite);
            $entityManager->flush();

            return $this->redirectToRoute('actualite_index');
        }

        return $this->render('actualite/new.html.twig', [
            'actualite' => $actualite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{titre}", name="actualite_show", methods={"GET"})
     */
    public function show(Actualite $actualite): Response
    {
        return $this->render('actualite/show.html.twig', [
            'actualite' => $actualite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="actualite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Actualite $actualite): Response
    {
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('actualite_index');
        }

        return $this->render('actualite/edit.html.twig', [
            'actualite' => $actualite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="actualite_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Actualite $actualite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$actualite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($actualite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('actualite_index');
    }
    /**
     * @param request
     * @Route("ajax/change/statut/actualite", name="ajax_change_statut_actualite")
     * @return response 
     */
    public function ajaxChangeStatutActualite(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $actualite_id = $request->get('id');
        $actualite = $em->getRepository(Actualite::class)->find($actualite_id);
        $old_statut = $actualite->getApprobation();
        $new_statut = !$old_statut;
        $actualiteModified = $actualite->setApprobation($new_statut);
        $em->persist($actualiteModified);
        $em->flush();
        return new Response('OK');
    }
}
