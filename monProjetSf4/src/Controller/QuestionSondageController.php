<?php

namespace App\Controller;

use App\Entity\QuestionSondage;
use App\Form\QuestionSondageType;
use App\Repository\QuestionSondageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/question/sondage")
 * @IsGranted("ROLE_ADMIN")
 */
class QuestionSondageController extends AbstractController
{
    /**
     * @Route("/", name="question_sondage_index", methods={"GET"})
     */
    public function index(QuestionSondageRepository $questionSondageRepository): Response
    {
        return $this->render('question_sondage/index.html.twig', [
            'question_sondages' => $questionSondageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="question_sondage_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $questionSondage = new QuestionSondage();
        $form = $this->createForm(QuestionSondageType::class, $questionSondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($questionSondage);
            $entityManager->flush();

            return $this->redirectToRoute('question_sondage_index');
        }

        return $this->render('question_sondage/new.html.twig', [
            'question_sondage' => $questionSondage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="question_sondage_show", methods={"GET"})
     */
    public function show(QuestionSondage $questionSondage): Response
    {
        return $this->render('question_sondage/show.html.twig', [
            'question_sondage' => $questionSondage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="question_sondage_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, QuestionSondage $questionSondage): Response
    {
        $form = $this->createForm(QuestionSondageType::class, $questionSondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('question_sondage_index');
        }

        return $this->render('question_sondage/edit.html.twig', [
            'question_sondage' => $questionSondage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="question_sondage_delete", methods={"DELETE"})
     */
    public function delete(Request $request, QuestionSondage $questionSondage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$questionSondage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($questionSondage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('question_sondage_index');
    }
    /**
     * @param request
     * @Route("ajax/change/statut/question", name="ajax_change_statut_question")
     * @IsGranted("ROLE_ADMIN")
     * @return response 
     */
    public function ajaxChangeStatutQuestion(Request $request): Response {
        $em = $this->getDoctrine()->getManager();
        $question_id = $request->get('id');
        $question = $em->getRepository(QuestionSondage::class)->find($question_id);
        $old_statut = $question->getApproval();
        $new_statut = !$old_statut;
        $questionModified = $question->setApproval($new_statut);
        $em->persist($questionModified);
        $em->flush();
        return new Response('OK');
    }
}
