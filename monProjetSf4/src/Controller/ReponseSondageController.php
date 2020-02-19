<?php

namespace App\Controller;

use App\Entity\ReponseSondage;
use App\Entity\QuestionSondage;
use App\Form\ReponseSondageType;
use App\Repository\ReponseSondageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/reponse/sondage")
 */
class ReponseSondageController extends AbstractController {

    /**
     * @Route("/", name="reponse_sondage_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(ReponseSondageRepository $reponseSondageRepository): Response {
        return $this->render('reponse_sondage/index.html.twig', [
        'reponse_sondages' => $reponseSondageRepository->findAll(),
        ]);
        }

        /**
         * @Route("/new", name="reponse_sondage_new", methods={"GET","POST"})
         * @IsGranted("ROLE_ADMIN")
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
     * @Route("/{id}", name="reponse_sondage_show", requirements={"id":"\d+"},  methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(ReponseSondage $reponseSondage): Response {
        return $this->render('reponse_sondage/show.html.twig', [
                    'reponse_sondage' => $reponseSondage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reponse_sondage_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, ReponseSondage $reponseSondage): Response {
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
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, ReponseSondage $reponseSondage): Response {
        if ($this->isCsrfTokenValid('delete' . $reponseSondage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reponseSondage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reponse_sondage_index');
    }

    /**
     * get the actual poll
     * @Route("/actualPoll", name="actual_poll")
     */
    public function actualPoll(Request $request): Response {
        $em = $this->getDoctrine()->getManager();
        $sondages = $em->getRepository(ReponseSondage::class)->getLastPool();
        $sondageFounded = count($sondages) > 0 ? true : false;
        return $this->render('reponse_sondage/sondage.html.twig', [
                    'sondages' => $sondages,
                    'sondageFounded' => $sondageFounded,
        ]);
    }

    /**
     *  Send the vote
     *  @Route("/vote", name="submit_vote")
     */
    public function submitedVote(Request $request): Response {
        $em = $this->getDoctrine()->getManager();
        $answer_id = $request->request->get('idItemSelected');
        $question_id = $request->request->get('idPoll');
        $answer = $em->getRepository(ReponseSondage::class)->find($answer_id);
        $session = new Session();
        if ($answer->getQuestion()->getID() <=> $question_id) {
            return new Response('KO-NC'); //question does not correspond to the response NoConform!
        }
        if(!empty($session->get('alreadyVoted'))) {
            return new Response('KO-AV'); // Already Voted
        }
        $incrementVote = $answer->getNbVote() + 1;
        $em->persist($answer->setNbVote($incrementVote));
        $em->flush();
        $session->set('alreadyVoted', true);
        return new Response('OK');
    }
    
    /**
     * Show result poll
     * @Route("/show/result", name="show_result_poll", methods={"GET"})
     */
    public function showResultPoll(Request $request): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $question_id = $request->query->get('idPoll');
        $pollAnswers = $em->getRepository(ReponseSondage::class)->findBy(['question' => $question_id]);
        $totalVote = $em->getRepository(ReponseSondage::class)->getTotalVote($question_id);
        //$nbAnswers = count($pollAnswers);
        $answers = array();
        foreach ($pollAnswers as $key => $pollAnswer){
            $answers[$key]['intituleReponse'] = $pollAnswer->getReponse();
            $answers[$key]['nbVote'] = $pollAnswer->getNbVote();
            $answers[$key]['ratePerCent'] = $pollAnswer->getNbVote() > 0 ? round($pollAnswer->getNbVote() * 100 / $totalVote['totalVote'], 2) : 0;
        }
        //$answers['totalVote'] = $totalVote['totalVote'];
        return new JsonResponse($answers);
        
    }
}
