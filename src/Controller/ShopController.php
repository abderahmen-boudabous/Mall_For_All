<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Repository\ShopRepository;
use App\Entity\Product;
use App\Entity\Shop;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\ShopType;
use App\Form\UpdateBType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ShopController extends AbstractController
{
    #[Route('/shop', name: 'app_shop')]
    public function index(): Response
    {
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
        ]);
    }

    #[Route('/afficheB', name: 'afficheB')]
    public function afficheB(ShopRepository $Shop): Response
                {
    
        $s=$Shop->findAll();
   return $this->render('shop/list.html.twig', [
    'Shops' => $s,'Shop'=>$Shop
                    ]);
     }

     #[Route('/afficheBd', name: 'afficheBd')]
    public function afficheBd(ShopRepository $Shop): Response
                {
        $s=$Shop->findAll();
   return $this->render('shop/listd.html.twig', [
    'Shops' => $s,'Shop'=>$Shop
   ]);
     }

     #[Route('/afficheBB', name: 'afficheBB')]
     public function addB(ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger)
                    {$b= new Shop();
     $form=$this->createForm(ShopType::class,$b);
                        $form->handleRequest($request);
                        if($form->isSubmitted() && $form->isValid()){
                            $photo = $form->get('photo')->getData();
                            if ($photo) {
                                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                                $safeFilename = $slugger->slug($originalFilename);
                                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
                                try {
                                    $photo->move(
                                        $this->getParameter('Shop_directory'),
                                        $newFilename
                                    );
                                } catch (FileException $e) {
                                }
                                $b->setImg($newFilename);
                            }      
                            $em =$doctrine->getManager() ;
                            $em->persist($b);
                            $em->flush();
                            return $this->redirectToRoute("afficheBd");}
                   return $this->renderForm("shop/addB.html.twig",
                            array("f"=>$form));
                     }
                     
                     #[Route('/updateB/{id}', name: 'updateB')]
                     public function updateB(Request $request, $id,SluggerInterface $slugger)
                     {
                        $Shop= $this->getDoctrine()->getRepository(Shop::class)->find($id);
      $formu=$this->createForm(updateBType::class,$Shop);
                         $formu->handleRequest($request);
                         if($formu->isSubmitted() && $formu->isValid()){
                            $photo = $formu->get('photo')->getData();
                if ($photo) {
                    $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
                    try {
                        $photo->move(
                            $this->getParameter('Shop_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                    }
                    $Shop->setImg($newFilename);
                }
                             $em = $this->getDoctrine()->getManager() ;
                             $em->persist($Shop);
                             $em->flush();
                             return $this->redirectToRoute("afficheBd");}
                    return $this->render('shop/updateB.html.twig',
                             ['Shops' => $Shop,'Shop'=>$Shop, 'formu' => $formu->createView()]);
                                     }

                                     #[route('/delete/{id}', name:'delete')]
                                     public function delete($id,ManagerRegistry $doctrine) {
                                 $Shop=$doctrine->getRepository(Shop::class)->find($id);
                                  $em = $this->getDoctrine()->getManager();
                                  $em->remove($Shop);
                                  $em->flush();
                                   $this->addFlash('notice', 'delete success');
                                  return $this->redirectToRoute('afficheBd');
                                                        }

                                                        #[Route('/confirm_delete/{id}', name: 'confirm_delete', methods: ['GET'])]
                                                        public function confirmDelete($id, ShopRepository $Shop, Request $request)
                                                        {
                                                        $Shop = $Shop->find($id);
                                                        $form = $this->createFormBuilder()
                                                        ->add('confirm', SubmitType::class, ['label' => 'Delete', 'attr' => ['class' => 'btn btn-danger']])
                                                        ->getForm();
                                        
                                                        $form->handleRequest($request);
                                                    
                                                        if ($form->isSubmitted() && $form->isValid()) {
                                                        $em = $this->getDoctrine()->getManager();
                                                        $em->remove($Shop);
                                                        $em->flush();        
                                                        $this->addFlash('success', 'Record deleted successfully!');
                                                    return $this->redirectToRoute('afficheBd');
                                                       }
                                                       return $this->render('Shop/confirm_delete.html.twig', [
                                                        'Shop'=>$Shop,
                                                        'form' => $form->createView(),
                                                    ]);
                                          }
                     
}
