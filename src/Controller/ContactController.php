<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Form\ContactFormType;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = (new Email())
                ->from('no-reply@siteclient')
                ->replyTo($data['email'])
                ->to('admin@example.com') // A changer avec mail du client
                ->subject($data['subject'])
                ->text(
                    "Name: ".$data['name']."\n".
                    "Email: ".$data['email']."\n".
                    "Message: \n".$data['message']
                );

            $mailer->send($email);

            $this->addFlash('success', 'Your message has been sent!');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
