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








class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index1(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    #[Route('/afficheE', name: 'afficheE')]
    public function afficheE(EventRepository $event): Response
    {
        $s = $event->findAll();
        return $this->render('event/listE.html.twig', [
            'events' => $s
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
            
        
            $session->getFlashBag()->add('success', $eventName . ' has been deleted.');
        
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
public function details($id)
{
    // retrieve the event data from the database based on the given ID
    $event = $this->getDoctrine()->getRepository(Event::class)->find($id);

    // render the event details template with the event data
    return $this->render('event_details.html.twig', [
        'event' => $event
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






}