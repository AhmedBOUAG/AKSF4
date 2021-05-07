<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Form\ActualiteType;
use App\Repository\ActualiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\ActualiteHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/actualite")
 */
class ActualiteController extends AbstractController {

    /**
     * @Route("/", name="actualite_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request, PaginatorInterface $paginator, ActualiteRepository $actualiteRepository): Response {
        $allNews = $actualiteRepository->findAll();
        $blocsNews = $paginator->paginate(
            $allNews,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('actualite/index.html.twig', [
            'actualites' => $blocsNews,
        ]);
    }

    /**
     * @Route("/new", name="actualite_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
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
     * @Route("/{id}", name="actualite_show", methods={"GET"})
     * @ParamConverter("actualite", options={"mapping":{"id":"id"}})
     */
    public function show(Actualite $actualite, ActualiteHelper $actualiteHelper): Response {

        $textWithoutHTML = $actualiteHelper->getPlainTextActualite(array($actualite));
        return $this->render('actualite/show.html.twig', [
                    'description' => $textWithoutHTML,
                    'actualite' => $actualite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="actualite_edit", requirements={"id":"\d+"}, methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Actualite $actualite): Response {
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
     * @Route("delete/{id}", name="actualite_delete", requirements={"id":"\d+"}, methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Actualite $actualite): Response {
        if ($this->isCsrfTokenValid('delete' . $actualite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($actualite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('actualite_index');
    }

    /**
     * @param request
     * @Route("ajax/change/statut/actualite", name="ajax_change_statut_actualite")
     * @IsGranted("ROLE_ADMIN")
     * @return response 
     */
    public function ajaxChangeStatutActualite(Request $request): Response {
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

    /**
     * @param request
     * @Route("_locale", name="actualite_locale")
     */
    public function paginateBlocNews(Request $request, PaginatorInterface $paginator, ActualiteRepository $actualiteRepository, ActualiteHelper $actualiteHelper)
    { 
        $allNews = $actualiteRepository->getAllApprovedNews();
        $allNewsApproved = $actualiteHelper->getPlainTextActualite($allNews);
        $blocsNews = $paginator->paginate(
                $allNewsApproved, 
                $request->query->getInt('page', 1),
                10
        );

        return $this->render('actualite/actualite_locale.html.twig', [
                    'blocs' => $blocsNews,
        ]);
    }

}