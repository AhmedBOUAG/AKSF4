<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Contact;
use App\Form\ContactType;

/**
 * @Route("/contact")
 */
class ContactController extends AbstractController {

    
    private $adminEmail;

    public function __construct($adminEmail) {
        $this->adminEmail = $adminEmail;
    }

    /**
     * @Route("/", name="form_contact", methods={"POST", "GET"})
     */
    public function index(Request $request): Response {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $aData = $request->request->get('contact');
            $to = $this->adminEmail;
            $subject = $aData['subject'];
            $message = $aData['message'] . "\n\n Par: " . $aData['name'];
            $headers = array(
                'From' => $aData['email'],
                'X-Mailer' => 'PHP/' . phpversion()
            );

            mail($to, $subject, $message, $headers);
            $this->addFlash('success', 'Le message a été envoyé avec succès.');
            return $this->redirectToRoute('form_contact');
        }
        return $this->render('contact.html.twig', [
                    'form' => $form->createView()
        ]);
    }

}
