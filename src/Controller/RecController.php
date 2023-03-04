<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RecRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Rec;
use App\Entity\Message;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\RecType;
use App\Form\MessageFormType;
use App\Form\MessageHFormType;
use App\Form\updateRType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Component\Security\Core\Security;

class RecController extends AbstractController
{   
    

    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'RecController',
        ]);
    }
    #[Route('/dashboard', name: 'dashboard')]
    public function index2(): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'RecController',
        ]);
    }
    #[Route('/afficheR', name: 'afficheR')]
    public function afficheR(RecRepository $rec): Response
                {
        $s=$rec->findAll();
   
   return $this->render('reclamation/listeR.html.twig', [
    'recs' => $s,'rec'=>$rec
                    ]);
     }
     #[Route('/addR', name: 'addR')]
     public function addR(ManagerRegistry $doctrine, Request $request): Response
     {
         $user = $this->getUser();
         $rec = new Rec();
         $rec->setEmail($user->getUsername());
         $rec->setUserR($user->getNom()); // set the current user as the userR value
         
         $form = $this->createForm(RecType::class, $rec);
         $form->handleRequest($request);
         
         if ($form->isSubmitted() && $form->isValid()) {
             $entityManager = $doctrine->getManager();
             $entityManager->persist($rec);
             $entityManager->flush();
     
             return $this->redirectToRoute('home');
         }
     
         return $this->render('home/addR.html.twig', [
             'f' => $form->createView(),
         ]);
     }
                     #[Route('/updateR/{id}', name: 'updateR')]
                     public function updateR(Request $request, $id, Security $security, SessionInterface $session)
{
    $rec = $this->getDoctrine()->getRepository(Rec::class)->find($id);
    $formu = $this->createForm(updateRType::class, $rec);
    $formu->handleRequest($request);

    if ($formu->isSubmitted() && $formu->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($rec);
        $em->flush();

        // Check if the logged-in user has the same email as the ticket that has been solved
        $user = $security->getUser();
        if ($user && $rec->getEmail() === $user->getEmail()) {
            // Add a flash message to notify the user that their ticket has been solved
            $session->getFlashBag()->add('success', [
                'message' => 'Your ticket has been solved successfully.',
                'dismissable' => true,
            ]);       }

        return $this->redirectToRoute("afficheR");
    }

    $messages = $this->getDoctrine()->getRepository(Message::class)->findBy(['recm' => $rec]);

    return $this->render('reclamation/updateR.html.twig', [
        'recs' => $rec,
        'rec' => $rec,
        'recm' => $rec,
        'formu' => $formu->createView(),
        'messages' => $messages
    ]);
}           
         #[route('/deleteR/{id}', name:'deleteR')]
                                 public function deleteR($id,ManagerRegistry $doctrine) {
                                 
                                     $rec=$doctrine->getRepository(Rec::class)->find($id);
                                     $em = $this->getDoctrine()->getManager();
                                     $em->remove($rec);
                                     $em->flush();
                                 
                                     $this->addFlash('notice', 'delete success');
                                 
                                     return $this->redirectToRoute('afficheR');
                                 }
                                 #[Route('/addMessage', name: 'addMessage')]
                                 public function addM(ManagerRegistry $doctrine,Request $request, EntityManagerInterface $entityManager)
                                                {$user = $this->getUser();
                                                    $m = new Message();
                                                    $m->setSender($user->getNom());
                                                    $id = $request->query->get('id');
                                                    
                                                    $form = $this->createForm(MessageFormType::class, $m, [
                                                        'id' => $request->query->get('id'),
                                                    ]);
                                                    $form->handleRequest($request);
                                                    if($form->isSubmitted() && $form->isValid()){
                                                        $em =$doctrine->getManager() ;
                                                        $em->persist($m);
                                                        $em->flush();
                                                        return $this->redirectToRoute("updateR", ['id' => $id]);}
                                               return $this->renderForm("reclamation/addMessage.html.twig",
                                                        array("form"=>$form));
                                                 }
                                                 #[Route('/mytickets', name: 'mytickets')]
                                                 public function myTickets(Request $request)
                                                 {
                                                     // Get the current user's email
                                                     $email = $this->getUser()->getEmail();
                                                 
                                                     // Fetch the tickets with the same email as the current user
                                                     $tickets = $this->getDoctrine()->getRepository(Rec::class)
                                                         ->findBy(['email' => $email]);
                                                 
                                                     return $this->render('/reclamation/mytickets.html.twig', [
                                                         'tickets' => $tickets,
                                                     ]);
                                                 }
                                                 #[Route('/updateRH/{id}', name: 'updateRH')]
                                                 public function updateRH(Request $request, $id)
                                                 {
                                                     $rec = $this->getDoctrine()->getRepository(Rec::class)->find($id);
                                                 
                                                     $messages = $this->getDoctrine()->getRepository(Message::class)->findBy(['recm' => $rec]);
                                                 
                                                     return $this->render('reclamation/updateRH.html.twig', [
                                                         'recs' => $rec,
                                                         'rec' => $rec,
                                                         'recm' => $rec,
                                                         'messages' => $messages
                                                     ]);
                                                 }
                                                 
                                                 public function afficheMH(MessageRepository $message): Response
                                                 {
                                                     $messages = $message->findAll();
                                                 
                                                     return $this->render('reclamation/listeM.html.twig', [
                                                         'messages' => $messages,
                                                         'message' => $message
                                                     ]);
                                                 }       
                                                 #[Route('/addMessageH', name: 'addMessageH')]
                                 public function addMH(ManagerRegistry $doctrine,Request $request, EntityManagerInterface $entityManager)
                                                {$user = $this->getUser();
                                                    $m = new Message();
                                                    $m->setSender($user->getNom());
                                                    $id = $request->query->get('id');
                                                    
                                                    $form = $this->createForm(MessageHFormType::class, $m, [
                                                        'id' => $request->query->get('id'),
                                                    ]);
                                                    $form->handleRequest($request);
                                                    if($form->isSubmitted() && $form->isValid()){
                                                        $em =$doctrine->getManager() ;
                                                        $em->persist($m);
                                                        $em->flush();
                                                        return $this->redirectToRoute("updateRH", ['id' => $id]);}
                                               return $this->renderForm("reclamation/addMessageH.html.twig",
                                                        array("form"=>$form));
                                                 }         
   }

