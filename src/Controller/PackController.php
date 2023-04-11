<?php

namespace App\Controller;

use App\Entity\PackNormal;
use App\Entity\PackSpecial;
use App\Form\PackNormalType;
use App\Form\PackSpecialType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;

class PackController extends AbstractController
{
    /**
     * @Route("/pack/normal", name="app_pack_normal")
     */
    public function normal(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        // just set up a fresh $pack_normal object (remove the example data)
        $pack_normal = new PackNormal();

        $form = $this->createForm(PackNormalType::class, $pack_normal);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$pack_normal` variable has also been updated
            $pack_normal = $form->getData();
            $entityManager->persist($pack_normal);
            $entityManager->flush();
            $this->addFlash('success', '🚀 Votre inscription a été envoyé avec succès!');
            $email = (new TemplatedEmail())
                ->from("no-reply@apresbac.ma")
                ->to("pack@apresbac.ma")
                ->cc("apresbac1800@gmail.com")
                ->subject('Message reçu depuis la page pack normal')
                ->htmlTemplate('pack/email.html.twig')
                ->context([
                    'pack' => $pack_normal,
                    'page' => 'Pack Normal'
                ]);

            $mailer->send($email);

            return $this->redirectToRoute('app_pack_normal');
        }

        return $this->renderForm('pack/normal.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/pack/special", name="app_pack_special")
     */
    public function special(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        // just set up a fresh $pack_special object (remove the example data)
        $pack_special = new PackSpecial();

        $form = $this->createForm(PackSpecialType::class, $pack_special);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$pack_special` variable has also been updated
            $pack_special = $form->getData();
            $entityManager->persist($pack_special);
            $entityManager->flush();
            $this->addFlash('success', '🚀 Votre inscription a été envoyé avec succès!');
            $email = (new TemplatedEmail())
                ->from("pack@apresbac.ma")
                ->to("pack@apresbac.ma")
                ->cc("apresbac1800@gmail.com")
                ->subject('Message reçu depuis la page pack special')
                ->htmlTemplate('pack/email.html.twig')
                ->context([
                    'pack' => $pack_special,
                    'page' => 'Pack Spacial'
                ]);

            $mailer->send($email);

            return $this->redirectToRoute('app_pack_special');
        }

        return $this->renderForm('pack/special.html.twig', [
            'form' => $form,
        ]);
    }
}
