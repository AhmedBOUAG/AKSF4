<?php

namespace App\Controller;

use App\Entity\LocalityMap;
use App\Event\LocalityMapEvent;
use App\Form\LocalityMapType;
use App\Repository\LocalityMapRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/locality/map")
 */
class LocalityMapController extends AbstractController 
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var EventDispatcherInterface */
    private $eventDispatcher;
    /** @var ValidatorInterface */
    private $validator;

    public function __construct(
        EntityManagerInterface $em, 
        EventDispatcherInterface $eventDispatcher, 
        ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->validator = $validator;
    }

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
        $localityMapEvent = new LocalityMapEvent($localityMap);
        $form = $this->createForm(LocalityMapType::class, $localityMap);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->eventDispatcher->dispatch($localityMapEvent);
                $this->em->persist($localityMap);
                $this->em->flush();

                return $this->redirectToRoute('locality_map_index');
            }
        }
        return $this->render('locality_map/new.html.twig', [
                    'locality_map' => $localityMap,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="locality_map_show", methods={"GET"})
     */
    public function show(LocalityMap $localityMap): Response 
    {
        return $this->render('locality_map/show.html.twig', [
                    'locality_map' => $localityMap,
        ]);
    }

    /**
     * @Route("/{id<\d+>}/edit", name="locality_map_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LocalityMap $localityMap): Response 
    {
        /** @var LocalityMapEvent $localityMapEvent */
        $localityMapEvent = new LocalityMapEvent($localityMap);

        //dd($localityMap,$errors);
        $form = $this->createForm(LocalityMapType::class, $localityMap);
        $form->handleRequest($request);
        //$errors = $this->validator->validate($localityMap);      
        
        if ($form->isSubmitted() && $form->isValid()) {

            $this->eventDispatcher->dispatch($localityMapEvent);
            $this->em->flush();
            return $this->redirectToRoute('locality_map_index');
        }

        return $this->render('locality_map/edit.html.twig', [
                    'locality_map' => $localityMap,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="locality_map_delete", methods={"DELETE"})
     */
    public function delete(Request $request, LocalityMap $localityMap): Response 
    {
        if ($this->isCsrfTokenValid('delete' . $localityMap->getId(), $request->request->get('_token'))) {
            $this->em->remove($localityMap);
            $this->em->flush();
        }

        return $this->redirectToRoute('locality_map_index');
    }

}
