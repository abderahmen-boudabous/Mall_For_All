<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use App\Entity\ParticipantsEvent;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ParticipantsEventRepository;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\EventFormType;
use App\Form\ParticipantsFormType;
use App\Form\UpdateEventFormType;
use TCPDF;
use Dompdf\Dompdf;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Notifier\Notification\Notification;

use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

use Endroid\QrCode\QrCode;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;

use App\Entity\Participants;
use App\Form\ParticiperType;






class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index1(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);

        // public function test(ManagerRegistry $doctrine,Request $request)
        // {$s= new Event();
        //     $form=$this->createForm(EventFormType::class,$s);
        //     $form->handleRequest($request);
        //     if($form->isSubmitted() && $form->isValid()){
        //         $em =$doctrine->getManager() ;
        //         $em->persist($s);
        //         $em->flush();
        //         return $this->redirectToRoute("afficheEC");}
        //         return $this->renderForm("event_details.html.twig", array("f"=>$form));
        // }
    }

    // #[Route('/afficheE', name: 'afficheE')]
    // public function afficheE(EventRepository $event): Response
    // {
    //     $s = $event->findAll();
    //     return $this->render('event/listE.html.twig', [
    //         'events' => $s
    //     ]);
    // }
    #[Route('/afficheE', name: 'afficheE')]
    public function afficheE(EventRepository $event): Response
{
    $liveEvents = $this->getDoctrine()->getRepository(Event::class)->findLiveEvents();
    return $this->render('event/listE.html.twig', [
        'events' => $event->findAll(),
        'liveEvents' => $liveEvents
    ]);
}





    #[Route('/afficheEC', name: 'afficheEC')]
public function afficheEC(EventRepository $event, EntityManagerInterface $entityManager): Response
{
    $events = $entityManager->getRepository(Event::class)
                       ->createQueryBuilder('e')
                       ->orderBy('e.Date', 'ASC')
                       ->getQuery()
                       ->getResult();
    
    return $this->render('event/listEC.html.twig', [
        'events' => $events
    ]);
}



    #[Route('/addE', name: 'addE')]
     public function addS(ManagerRegistry $doctrine,Request $request)
                    {$s= new Event();
     $form=$this->createForm(EventFormType::class,$s);
                        $form->handleRequest($request);
                        if($form->isSubmitted() && $form->isValid()){
                            $em =$doctrine->getManager() ;
                            $em->persist($s);
                            $em->flush();
                            return $this->redirectToRoute("afficheEC");}
                            return $this->renderForm("event/addE.html.twig", array("f"=>$form));
    }


        #[Route('/updateE/{id}', name: 'updateE')]
        public function updateE(Request $request, $id)
        {
        $event= $this->getDoctrine()->getRepository(Event::class)->find($id);
            $forme=$this->createForm(UpdateEventFormType::class,$event);
            $forme->handleRequest($request);
            if($forme->isSubmitted() && $forme->isValid()){
                $em = $this->getDoctrine()->getManager() ;
                $em->persist($event);
                $em->flush();
                return $this->redirectToRoute("afficheEC");}
                return $this->render('event/updateE.html.twig',
                    ['events' => $event,'event'=>$event, 'forme' => $forme->createView()]);
        }


        #[Route('/delete/{id}', name:'delete')]
        public function delete($id, ManagerRegistry $doctrine, SessionInterface $session)
        {
            $event = $doctrine->getRepository(Event::class)->find($id);
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush();
        
            $eventName = $event->getNom();
            
            $user = $security->getUser();
            if ($user && $rec->getEmail() === $user->getEmail()) {
                // Add a flash message to notify the user that their ticket has been solved
                $session->getFlashBag()->add('success', [
                    'message' => 'Event has been cancelled :c ',
                    'dismissable' => true,
                ]);       }
            // $session->getFlashBag()->add('success', $eventName . ' Event has been deleted.');
        
            return $this->redirectToRoute('afficheEC');
        }

                      



                                 #[Route('/event/pdf', name: 'event_pdf')]
                                 public function pdf(): Response
                                 {
                                     $c = $this->getDoctrine()->getRepository(Event::class)->findAll();
                                 
                                     $dompdf = new Dompdf();
                                     $dompdf->setPaper('A4', 'portrait');
                                 
                                     $html = $this->renderView('event/pdf.html.twig', [
                                         'event' => $c,
                                     ]);
                                 
                                     $dompdf->loadHtml($html);
                                 
                                     $dompdf->render();
                                 
                                     $output = $dompdf->output();
                                 
                                     $response = new Response($output);
                                     $response->headers->set('Content-Type', 'application/pdf');
                                     $response->headers->set('Content-Disposition', 'attachment; filename="event.pdf"');
                                 
                                     return $response;
                                 }
                                 
              
                                 
                                 
                                /**
                                 * @Route("/event/{id}", name="event_details")
                                 */
                                public function details($id, Request $request)
                                {
                                    $s = new Participants();
                                    $user = $this->getUser();
                                    $s->setParticipantId($user->getId());
                                    $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
                                    $s->setEventId($event->getId());
                                    $form = $this->createForm(ParticiperType::class, $s);
                                    $form->handleRequest($request);
                                
                                    if ($form->isSubmitted() && $form->isValid()) {
                                        $em = $this->getDoctrine()->getManager();
                                        $em->persist($s);
                                        $em->flush();
                                
                                        // retrieve the event data from the database based on the given ID
                                        
                                
                                        // render the event details template with the event data
                                        return $this->redirectToRoute("afficheEC");
                                    }
                                
                                    // if the form is not submitted or is not valid, render the form and event details template
                                    $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
                                    return $this->render('event_details.html.twig', [
                                        'event' => $event,
                                        'f' => $form->createView(),
                                    ]);
                                }
                                


/**
 * @Route("/event/{id}/participate", name="event_participate")
 */
public function participate(Request $request, $id)
{
    $entityManager = $this->getDoctrine()->getManager();

    $event = $entityManager->getRepository(Event::class)->find($id);

    if (!$event) {
        throw $this->createNotFoundException(
            'No event found for id '.$id
        );
    }

    $currentSpotValue = $event->getSpot();

    if ($currentSpotValue > 0) {
        $newSpotValue = $currentSpotValue - 1;
        $event->setSpot($newSpotValue);

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'new_spot_value' => $newSpotValue
        ]);
    } else {
        return $this->json([
            'success' => false,
            'message' => 'No more spots available for this event'
        ]);
    }
}



/**
 * @Route("/event/{id}/unparticipate", name="event_unparticipate")
 */
public function unparticipate(Request $request, $id)
{
    $entityManager = $this->getDoctrine()->getManager();

    $event = $entityManager->getRepository(Event::class)->find($id);

    if (!$event) {
        throw $this->createNotFoundException(
            'No event found for id '.$id
        );
    }

    $currentSpotValue = $event->getSpot();

    if ($currentSpotValue > 0) {
        $newSpotValue = $currentSpotValue + 1;
        $event->setSpot($newSpotValue);

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'new_spot_value' => $newSpotValue
        ]);
    }
}




                        /**
                         * @Route("/event/{id}/test", name="event_test")
                         */
                        // public function test(Request $request, $id)
                        // {
                        //     $entityManager = $this->getDoctrine()->getManager();

                        //     $event = $entityManager->getRepository(Event::class)->find($id);

                        //     if (!$event) {
                        //         throw $this->createNotFoundException(
                        //             'No event found for id '.$id
                        //         );
                        //     }

                        //     // Create a new participant object and set its properties
                        //     $participant = new Participants();
                        //     $participant->setParticipantId($this->getUser()->getId());
                        //     $participant->setEventId($event->getId());

                        //     // Persist the participant object to the database
                        //     $entityManager->persist($participant);
                        //     $entityManager->flush();

                        //     return $this->json([
                        //         'success' => true
                        //     ]);
                        // }


                        // #[Route('/addE', name: 'addE')]
                        
                        // #[Route('/test/{id}', name:'test')]
                    //     public function test(ManagerRegistry $doctrine,Request $request)
                    //     {$s= new Event();
                    //         $user = $this->getUser();
                    //         $s->setParticipantId($user->getId());    
                    //         $form=$this->createForm(EventFormType::class,$s);
                    //         $form->handleRequest($request);
                    //         if($form->isSubmitted() && $form->isValid()){
       
                    //             $em =$doctrine->getManager() ;
                    //             $em->persist($s);
                    //             $em->flush();
                    //             return $this->redirectToRoute("afficheEC");}

                    //             ]);
                    //    }







}