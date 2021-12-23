<?php

namespace App\Controller;

use App\Entity\ReponseSondage;
use App\Form\ReponseSondageType;
use App\Repository\ReponseSondageRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var ReponseSondageRepository
     */
    private $reponseSondageRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ReponseSondageRepository $reponseSondageRepository, EntityManagerInterface $em)
    {
        $this->reponseSondageRepository = $reponseSondageRepository;
        $this->em = $em;
    }
    /**
     * @Route("/", name="reponse_sondage_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(): Response
    {
        return $this->render('sondage/reponse/index.html.twig', [
                                'reponse_sondages' => $this->reponseSondageRepository->findAll(),
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
            $this->em = $this->getDoctrine()->getManager();
            $this->em->persist($reponseSondage);
            $this->em->flush();

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
    public function show(ReponseSondage $reponseSondage): Response
    {
        return $this->render('sondage/reponse/show.html.twig', [
                    'reponse_sondage' => $reponseSondage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reponse_sondage_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, ReponseSondage $reponseSondage): Response 
    {
        $form = $this->createForm(ReponseSondageType::class, $reponseSondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('reponse_sondage_index');
        }

        return $this->render('sondage/reponse/edit.html.twig', [
                    'reponse_sondage' => $reponseSondage,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reponse_sondage_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, ReponseSondage $reponseSondage): Response 
    {
        if ($this->isCsrfTokenValid('delete' . $reponseSondage->getId(), $request->request->get('_token'))) {
            $this->em->remove($reponseSondage);
            $this->em->flush();
        }

        return $this->redirectToRoute('reponse_sondage_index');
    }

    /**
     * get the actual poll
     * @Route("/actualPoll", name="actual_poll")
     */
    public function actualPoll(): Response 
    {
        $sondages = $this->reponseSondageRepository->getLastPool();
        $sondageFounded = count($sondages) > 0 ? true : false;
        return $this->render('sondage/sondage.html.twig', [
                    'sondages' => $sondages,
                    'sondageFounded' => $sondageFounded,
        ]);
    }

    /**
     *  Send the vote
     *  @Route("/vote", name="submit_vote")
     */
    public function submitedVote(Request $request): Response 
    {
        $session = new Session();
        if(!empty($session->get('alreadyVoted'))) {
            return new Response('KO-AV'); // Already Voted
        }

        $answer_id = $request->request->get('idItemSelected');
        $question_id = $request->request->get('idPoll');
        $answer = $this->reponseSondageRepository->find($answer_id);
        if ($answer->getQuestion()->getID() <=> $question_id) {
            return new Response('KO-NC'); //question does not correspond to the response NoConform!
        }

        $incrementVote = $answer->getNbVote() + 1;
        $answer->setNbVote($incrementVote); 
        $this->em->flush();
        $session->set('alreadyVoted', true);
        return new Response('OK');
    }
    
    /**
     * Show result poll
     * @Route("/show/result", name="show_result_poll", methods={"GET"})
     */
    public function showResultPoll(Request $request): JsonResponse
    {
        $question_id = $request->query->get('idPoll');
        $pollAnswers = $this->reponseSondageRepository->findBy(['question' => $question_id]);
        $totalVote = $this->reponseSondageRepository->getTotalVote($question_id);
        $answers = array();

        foreach ($pollAnswers as $key => $pollAnswer){
            $answers[$key]['itemResponse'] = $pollAnswer->getReponse();
            $answers[$key]['nbVote'] = $pollAnswer->getNbVote();
            $answers[$key]['ratePerCent'] = $pollAnswer->getNbVote() > 0 ? round($pollAnswer->getNbVote() * 100 / $totalVote['totalVote'], 2) : 0;
        }
        return new JsonResponse($answers);
        
    }
}
