<?php

namespace App\Controller;

use App\Entity\Resume;
use App\Form\ResumeType;
use App\Repository\ResumeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/resume")
 */
class ResumeController extends AbstractController {

    /**
     * @Route("/", name="resume_index", methods={"GET"})
     */
    public function index(ResumeRepository $resumeRepository): Response {
        return $this->render('resume/index.html.twig', [
                    'resumes' => $resumeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="resume_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $form = $this->createForm(ResumeType::class, $resume);

        if ($request->isMethod('POST')) {
            $aRequest = $request->request->get('resume');
            $limites = $aRequest['limites'];
            unset($aRequest['limites']);
            $json_limites = explode(",", $limites);
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

            $aRequest['limites'] = json_encode($aNewData);
            $request->request->set('resume', $aRequest);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                //dump($resume);die;
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
     */
    public function show(Resume $resume): Response {
        return $this->render('resume/show.html.twig', [
                    'resume' => $resume,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="resume_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Resume $resume): Response {
        $form = $this->createForm(ResumeType::class, $resume);

        if ($request->isMethod('POST')) {
            $aRequest = $request->request->get('resume');
            $limites = $aRequest['limites'];
            unset($aRequest['limites']);
            $json_limites = explode(",", $limites);
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

            $aRequest['limites'] = json_encode($aNewData);
            $request->request->set('resume', $aRequest);
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
     * @Route("/{id}", name="resume_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Resume $resume): Response {
        if ($this->isCsrfTokenValid('delete' . $resume->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($resume);
            $entityManager->flush();
        }

        return $this->redirectToRoute('resume_index');
    }

}
