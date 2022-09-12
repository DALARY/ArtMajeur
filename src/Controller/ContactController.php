<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    // Formulaire de demande
    #[Route('artmajeur/contact', name: 'app_contact')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $user = $this->getUser();
        $entityManager = $doctrine->getManager();
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setName($form['name']->getData());
            $contact->setEmail($form['email']->getData());
            $contact->setQuestion($form['question']->getData());
            $contact->setValide(0);
            $contact->setUser($user);

            $entityManager->persist($contact);
            $entityManager->flush();
            return $this->redirectToRoute('app_messages');
        }
        

        return $this->renderForm('contact/index.html.twig', [
            'form' => $form,
            'controller_name' => 'ContactController',
        ]);
    }
}
