<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {

        // just set up a fresh $task object (remove the example data)
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$contact` variable has also been updated
            $contact = $form->getData();
            $entityManager->persist($contact);
            $entityManager->flush();
            $this->addFlash('success', '🚀 Votre message a été envoyé avec succès!');
            $email = (new TemplatedEmail())
                ->from("no-reply@apresbac.ma")
                ->to("contact@apresbac.ma")
                ->cc("apresbac1800@gmail.com")
                ->subject('Message reçu depuis la page contact')
                ->htmlTemplate('contact/email.html.twig')
                ->context([
                    'contact' => $contact,
                ]);

            $mailer->send($email);

            return $this->redirectToRoute('app_contact');
        }

        return $this->renderForm('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
