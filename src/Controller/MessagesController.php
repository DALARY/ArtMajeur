<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{
    #[Route('/artmajeur/messages', name: 'app_messages')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $contact = $entityManager->getRepository(Contact::class)->findAll();
        return $this->render('messages/index.html.twig', [
            'controller_name' => 'MessagesController',
            'contact' => $contact,
        ]);
    }
}
