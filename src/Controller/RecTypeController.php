<?php

namespace App\Controller;
use App\Controller\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RecTRepository;
use App\Entity\RecT;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\TypeRecType;
use App\Form\UpdateRTFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use FOS\UserBundle\Form\Type\RegistrationFormType; 
use FOS\UserBundle\Form\Type\RegistrationFormType as FOSRegistrationFormType;

class RecTypeController extends AbstractController
{
   
    #[Route('/afficheRT', name: 'afficheRT')]
    public function afficheRT(RecTRepository $rect): Response
                {
        $s=$rect->findAll();
   
   return $this->render('rec_type/listeRT.html.twig', [
    'recst' => $s,'rect'=>$rect
                    ]);
     }
     #[Route('/addRT', name: 'addRT')]
     public function addRT(ManagerRegistry $doctrine,Request $request)
                    {$s= new RecT();
     $form=$this->createForm(TypeRecType::class,$s);
                        $form->handleRequest($request);
                        if($form->isSubmitted() && $form->isValid()){
                            $em =$doctrine->getManager() ;
                            $em->persist($s);
                            $em->flush();
                            return $this->redirectToRoute("afficheRT");}
                   return $this->renderForm("home/addRT.html.twig",
                            array("formt"=>$form));
                     }
                 #[Route('/updateRT/{id}', name: 'updateRT')]
                 public function updateRT(Request $request, $id)
                 {
                    $rect= $this->getDoctrine()->getRepository(RecT::class)->find($id);
  $formrt=$this->createForm(updateRTFormType::class,$rect);
                     $formrt->handleRequest($request);
                     if($formrt->isSubmitted() && $formrt->isValid()){
                         $em = $this->getDoctrine()->getManager() ;
                         $em->persist($rect);
                         $em->flush();
                         return $this->redirectToRoute("afficheRT");}
                return $this->render('reclamation/updateRT.html.twig',
                         ['recst' => $rect,'rect'=>$rect, 'formrt' => $formrt->createView()]);
                                 }
                                 #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
                                 public function delete($id,ManagerRegistry $doctrine) {
                                 
                                    $rect=$doctrine->getRepository(RecT::class)->find($id);
                                    $em = $this->getDoctrine()->getManager();
                                    $em->remove($rect);
                                    $em->flush();
                                
                                    $this->addFlash('notice', 'delete success');
                                
                                    return $this->redirectToRoute('afficheRT');
                                }
                                #[Route('/confirm_delete/{id}', name: 'confirm_delete', methods: ['GET'])]
                                public function confirmDelete($id, RecTRepository $rect, Request $request)
                                {
                                    $rect = $rect->find($id);
                                    
                                    $form = $this->createFormBuilder()
                                        ->add('confirm', SubmitType::class, ['label' => 'Delete', 'attr' => ['class' => 'btn btn-danger']])
                                        ->getForm();
                                    
                                    $form->handleRequest($request);
                                    
                                    if ($form->isSubmitted() && $form->isValid()) {
                                        $em = $this->getDoctrine()->getManager();
                                        $em->remove($rect);
                                        $em->flush();
                                        
                                        $this->addFlash('success', 'Record deleted successfully!');
                                        
                                        return $this->redirectToRoute('afficheRT');
                                    }
                                    
                                    return $this->render('rec_type/confirm_delete.html.twig', [
                                        'rect' => $rect,
                                        'form' => $form->createView(),
                                    ]);
                                }
}
