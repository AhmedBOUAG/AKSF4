<?php

namespace App\Controller;

use App\Entity\Resume;
use App\Form\ResumeType;
use App\Repository\ResumeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LocalityMapRepository;

/**
 * @Route("/resume")
 */
class ResumeController extends AbstractController {

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
        $form = $this->createForm(ResumeType::class, $resume);

        if ($request->isMethod('POST')) {

            $this->constructJsonCoordinates($request);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($resume);
                $entityManager->flush();

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
        $form = $this->createForm(ResumeType::class, $resume);

        if ($request->isMethod('POST')) {

            $this->constructJsonCoordinates($request);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($resume);
            $entityManager->flush();
        }

        return $this->redirectToRoute('resume_index');
    }

    private function constructJsonCoordinates($request) {
        $tabParent = 'resume';
        $subTab = 'limites';
        $aRequest = $request->request->get($tabParent);
        $data = $aRequest[$subTab];
        unset($aRequest[$subTab]);
        $json_limites = explode(",", $data);
        $xandy = '';
        $aNewData = array();

        $i = 0;
        foreach ($json_limites as $value) {
            $i++;
            if ($i === 2) {
                $aNewData[] = array($xandy, $value);
                $i = 0;
            } else {
                $xandy = $value;
            }
        }

        $aRequest[$subTab] = json_encode($aNewData);
        return $request->request->set($tabParent, $aRequest);
        
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