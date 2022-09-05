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
    #[Route('/contact', name: 'app_contact')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setName($form['name']->getData());
            $contact->setEmail($form['email']->getData());
            $contact->setQuestion($form['question']->getData());

            $entityManager->persist($contact);
            $entityManager->flush();
            return $this->redirectToRoute('app_contact');
        }
        

        return $this->renderForm('contact/index.html.twig', [
            'form' => $form,
            'controller_name' => 'ContactController',
        ]);
    }
}
