<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RecRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Rec;
use App\Entity\Message;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\RecType;
use App\Form\MessageFormType;
use App\Form\updateRType;


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
     public function addS(ManagerRegistry $doctrine,Request $request)
                    {$s= new Rec();
                        
     $form=$this->createForm(RecType::class,$s);
                        $form->handleRequest($request);
                        if($form->isSubmitted() && $form->isValid()){
                            $em =$doctrine->getManager() ;
                            $em->persist($s);
                            $em->flush();
                            return $this->redirectToRoute("home");}
                   return $this->renderForm("home/addR.html.twig",
                            array("f"=>$form));
                     }
                     #[Route('/updateR/{id}', name: 'updateR')]
                     public function updateR(Request $request, $id, \Swift_Mailer $mailer)
                     {
                         $rec = $this->getDoctrine()->getRepository(Rec::class)->find($id);
                         $formu = $this->createForm(updateRType::class, $rec);
                         $formu->handleRequest($request);
                     
                         if ($formu->isSubmitted() && $formu->isValid()) {
                             $em = $this->getDoctrine()->getManager();
                             $em->persist($rec);
                             $em->flush();
                     
                             // Debugging: dump the email address and message content
                             dump($rec->getEmail());
                             dump($this->renderView('reclamation/ticket_solved.html.twig', ['rec' => $rec]));
                     
                             // If the ticket status is "solved", send an email notification to the client
                             if ($rec->getEtat() == 'Solved') {
                                 $emailMessage = (new \Swift_Message('Ticket Solved'))
                                     ->setFrom('arafetksiksi7@gmail.com')
                                     ->setTo($rec->getEmail())
                                     ->setBody(
                                         $this->renderView(
                                             'reclamation/ticket_solved.html.twig',
                                             ['rec' => $rec]
                                         ),
                                         'text/html'
                                     );
                                 // Debugging: dump the email message object
                                 dump($emailMessage);
                                 $mailer->send($emailMessage);
                             }
                     
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
                                                {$m= new Message();
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
      
   }

