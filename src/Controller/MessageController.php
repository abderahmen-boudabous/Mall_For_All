<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/message', name: 'app_message')]
    public function index(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }
    #[Route('/afficheM', name: 'afficheM')]
    public function afficheRT(MessageFormRepository $message): Response
                {
        $m=$message->findAll();
   
   return $this->render('message/listeM.html.twig', [
    'messages' => $m,'message'=>$message
                    ]);
     }
}
