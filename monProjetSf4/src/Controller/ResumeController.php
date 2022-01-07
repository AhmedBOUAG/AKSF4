<?php

namespace App\Controller;

use App\Entity\Resume;
use App\Event\LocalityMapEvent;
use App\Form\ResumeType;
use App\Repository\LocalityMapRepository;
use App\Repository\ResumeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @Route("/resume")
 */
class ResumeController extends AbstractController {

    /** @var EntityManagerInterface */
    private $em;

    private $eventDispatcher;
    public function __construct(
        EntityManagerInterface $em, 
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }
    /**
     * @Route("/", name="resume_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(ResumeRepository $resumeRepository): Response {
        return $this->render('resume/index.html.twig', [
                    'resumes' => $resumeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="resume_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response {
        $resume = new Resume();
        $localityMapEvent = new LocalityMapEvent($resume);
        $form = $this->createForm(ResumeType::class, $resume);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->eventDispatcher->dispatch($localityMapEvent);
                $this->em->persist($resume);
                $this->em->flush();

                return $this->redirectToRoute('resume_index');
            }
        }
        return $this->render('resume/new.html.twig', [
                    'resume' => $resume,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="resume_show", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(Resume $resume): Response {
        return $this->render('resume/show.html.twig', [
                    'resume' => $resume,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="resume_edit", requirements={"id":"\d+"}, methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Resume $resume): Response {
        $localityMapEvent = new LocalityMapEvent($resume);
        $form = $this->createForm(ResumeType::class, $resume);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $this->eventDispatcher->dispatch($localityMapEvent);
                $this->em->flush();
                return $this->redirectToRoute('resume_index');
            }
        }
        return $this->render('resume/edit.html.twig', [
                    'resume' => $resume,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="resume_delete",  requirements={"id":"\d+"}, methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Resume $resume): Response {
        if ($this->isCsrfTokenValid('delete' . $resume->getId(), $request->request->get('_token'))) {
            $this->em->remove($resume);
            $this->em->flush();
        }

        return $this->redirectToRoute('resume_index');
    }
    
    /**
     * @Route("/about/aitkermoune", name="about_aitkermoune", methods={"GET"})
     */
    public function aboutAitkermoune(ResumeRepository $resumeRepository, LocalityMapRepository $lmr) : Response{
        $pointsLimite =  $resumeRepository->findAll()[0];
        $locality = $lmr->findAll();
         return $this->render('resume/about.html.twig', [
            'resume' => $pointsLimite,
            'locality' => $locality,
        ]);
    }
}