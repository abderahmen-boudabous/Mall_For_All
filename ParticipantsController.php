<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\ParticipantsEvent;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ParticipantsEventRepository;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\EventFormType;
use App\Form\ParticipantsFormType;
use App\Form\UpdateparticipantFormType;



class ParticipantsController extends AbstractController
{
    #[Route('/participants', name: 'app_participants')]
    public function index(): Response
    {
        return $this->render('participants/index.html.twig', [
            'controller_name' => 'ParticipantsController',
        ]);
    }


    #[Route('/afficheP', name: 'afficheP')]
    public function afficheP(ParticipantsEventRepository $participant): Response
                {
     //utiliser la fonction findAll()
        $s=$participant->findAll();
      //utiliser la fonction oderbymail

      return $this->render('participants/list.html.twig', [
        'participants' => $s,
    ]);
                }
                

                #[Route('/addP', name: 'addP')]
                public function addS(ManagerRegistry $doctrine,Request $request)
                               {$s= new ParticipantsEvent();
                $form=$this->createForm(ParticipantsFormType::class,$s);
                                   $form->handleRequest($request);
                                   if($form->isSubmitted()){
                                       $em =$doctrine->getManager() ;
                                       $em->persist($s);
                                       $em->flush();
                                       return $this->redirectToRoute("afficheP");}
                                       return $this->renderForm("participants/addP.html.twig", array("f"=>$form));
                                   }   
                                   
                                   
                                   #[Route('/updateP/{id}', name: 'updateP')]
                                   public function updateP(Request $request, $id)
                                   {
                                   $participant= $this->getDoctrine()->getRepository(ParticipantsEvent::class)->find($id);
                                       $formu=$this->createForm(updateparticipantFormType::class,$participant);
                                       $formu->handleRequest($request);
                                       if($formu->isSubmitted() && $formu->isValid()){
                                           $em = $this->getDoctrine()->getManager() ;
                                           $em->persist($participant);
                                           $em->flush();
                                           return $this->redirectToRoute("afficheP");}
                                           return $this->render('participants/updateP.html.twig',
                                               ['participants' => $participant,'participant'=>$participant, 'formu' => $formu->createView()]);
                                   }
                                
                                   #[route('/deleteP/{id}', name:'deleteP')]
                                   public function delete($id,ManagerRegistry $doctrine) {
  
                                       $event=$doctrine->getRepository(ParticipantsEvent::class)->find($id);
                                       $em = $this->getDoctrine()->getManager();
                                      
                                       $em->remove($event);
                                       $em->flush();
  
                                       $this->addFlash('notice', 'Deleted successfully');
  
                                       return $this->redirectToRoute('afficheP');
                                   }    


}