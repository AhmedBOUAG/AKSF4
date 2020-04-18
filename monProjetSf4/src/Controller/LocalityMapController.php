<?php

namespace App\Controller;

use App\Entity\LocalityMap;
use App\Form\LocalityMapType;
use App\Repository\LocalityMapRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/locality/map")
 */
class LocalityMapController extends AbstractController
{
    /**
     * @Route("/", name="locality_map_index", methods={"GET"})
     */
    public function index(LocalityMapRepository $localityMapRepository): Response
    {
        return $this->render('locality_map/index.html.twig', [
            'locality_maps' => $localityMapRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="locality_map_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $localityMap = new LocalityMap();
        $form = $this->createForm(LocalityMapType::class, $localityMap);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($localityMap);
            $entityManager->flush();

            return $this->redirectToRoute('locality_map_index');
        }

        return $this->render('locality_map/new.html.twig', [
            'locality_map' => $localityMap,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="locality_map_show", methods={"GET"})
     */
    public function show(LocalityMap $localityMap): Response
    {
        return $this->render('locality_map/show.html.twig', [
            'locality_map' => $localityMap,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="locality_map_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LocalityMap $localityMap): Response
    {
        $form = $this->createForm(LocalityMapType::class, $localityMap);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('locality_map_index');
        }

        return $this->render('locality_map/edit.html.twig', [
            'locality_map' => $localityMap,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="locality_map_delete", methods={"DELETE"})
     */
    public function delete(Request $request, LocalityMap $localityMap): Response
    {
        if ($this->isCsrfTokenValid('delete'.$localityMap->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($localityMap);
            $entityManager->flush();
        }

        return $this->redirectToRoute('locality_map_index');
    }
}
