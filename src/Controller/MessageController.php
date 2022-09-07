<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Vu;
use App\Form\VuType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/artmajeur/messages', name: 'app_messages')]
    public function messages(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $vu = new Vu();
        $form = $this->createForm(VuType::class, $vu);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $vu->setValide($form['Valide']->getData());

            $entityManager->persist($vu);
            $entityManager->flush();
        }
        if ($this->isGranted('ROLE_ADMIN')) {
            $entityManager = $doctrine->getManager();
            $messages = $entityManager->getRepository(Contact::class)->findAll();

        } elseif ($this->isGranted('ROLE_USER')) {
            $user = $this->getUser();
            $entityManager = $doctrine->getManager();
            $messages = $entityManager->getRepository(Contact::class)->findBy(array('user' => $user));
        }

        return $this->render('messages/index.html.twig', [
            'controller_name' => 'MessagesController',
            'messages' => $messages,
            'form' => $form->createView(),
        ]);
        
    }    
}
