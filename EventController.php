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





class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(): Response
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
    public function afficheEC(EventRepository $event): Response
    {
        $s = $event->findAll();
        return $this->render('event/listEC.html.twig', [
            'events' => $s
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


                    #[route('/delete/{id}', name:'delete')]
                                 public function delete($id,ManagerRegistry $doctrine) {

                                     $event=$doctrine->getRepository(Event::class)->find($id);
                                     $em = $this->getDoctrine()->getManager();
                                     $em->remove($event);
                                     $em->flush();

                                     $this->addFlash('notice', 'delete success');

                                     return $this->redirectToRoute('afficheEC');
                                 }

}