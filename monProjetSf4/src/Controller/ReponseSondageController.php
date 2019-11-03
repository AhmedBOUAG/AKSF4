<?php

namespace App\Controller;

use App\Entity\ReponseSondage;
use App\Form\ReponseSondageType;
use App\Repository\ReponseSondageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reponse/sondage")
 */
class ReponseSondageController extends AbstractController
{
    /**
     * @Route("/", name="reponse_sondage_index", methods={"GET"})
     */
    public function index(ReponseSondageRepository $reponseSondageRepository): Response
    {
        return $this->render('reponse_sondage/index.html.twig', [
            'reponse_sondages' => $reponseSondageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="reponse_sondage_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $reponseSondage = new ReponseSondage();
        $form = $this->createForm(ReponseSondageType::class, $reponseSondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reponseSondage);
            $entityManager->flush();

            return $this->redirectToRoute('reponse_sondage_index');
        }

        return $this->render('reponse_sondage/new.html.twig', [
            'reponse_sondage' => $reponseSondage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reponse_sondage_show", methods={"GET"})
     */
    public function show(ReponseSondage $reponseSondage): Response
    {
        return $this->render('reponse_sondage/show.html.twig', [
            'reponse_sondage' => $reponseSondage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reponse_sondage_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ReponseSondage $reponseSondage): Response
    {
        $form = $this->createForm(ReponseSondageType::class, $reponseSondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reponse_sondage_index');
        }

        return $this->render('reponse_sondage/edit.html.twig', [
            'reponse_sondage' => $reponseSondage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reponse_sondage_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ReponseSondage $reponseSondage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponseSondage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reponseSondage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reponse_sondage_index');
    }
}
