<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ValideType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    // Affichage de tout les messages en partie admin et affichage de tout les messages de la personne concerner
    #[Route('/artmajeur/messages', name: 'app_messages')]
    public function messages(ManagerRegistry $doctrine): Response
    {
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
        ]);
    }

    // Aperçu des messages uniques avec validation d'un admin
    #[Route('/artmajeur/messages/show/{id}', name: 'app_messages_show')]
    public function messages_show(ManagerRegistry $doctrine, Request $request, $id): Response
    {   
        $entityManager = $doctrine->getManager();
        $show = $entityManager->getRepository(Contact::class)->find($id);

        $form = $this->createForm(ValideType::class, $show);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $show->setValide(1);

            $entityManager->persist($show);
            $entityManager->flush();
            return $this->redirectToRoute('app_messages');
        }
        
        return $this->render('messages/show.html.twig', [
            'controller_name' => 'MessagesController',
            'show' => $show,
            'form' => $form->createView(),
        ]);
    }

    // Création du fichier JSON
    #[Route('/artmajeur/messages/show/{id}/json', name: 'app_messages_json')]
    public function messages_json(ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManager();
        $show = $entityManager->getRepository(Contact::class)->find($id);

        $data = array();
        $data['user'] = $id;
        $data['name'] = $show->getName();
        $data['email'] = $show->getEmail();
        $data['question'] = $show->getQuestion();

        $json = json_encode($data);
        if (!is_dir('../public/json')) {
            mkdir("../public/json");
            file_put_contents("../public/json/".rand().".json", $json);
            return $this->redirectToRoute('app_messages');
        } else {
            file_put_contents("../public/json/".rand().".json", $json);
            return $this->redirectToRoute('app_messages');
        }
        
        return $this->render('messages/show.html.twig', [
            'controller_name' => 'MessagesController',
            'show' => $show,
        ]);
    }

}
