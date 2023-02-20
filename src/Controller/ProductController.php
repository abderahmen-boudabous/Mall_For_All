<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Entity\Shop;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\ProductType;
use App\Form\UpdatePType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/afficheP', name: 'afficheP')]
    public function afficheP(ProductRepository $product): Response
                {
        $a=$product->findAll();
   return $this->render('product/listp.html.twig', [
    'products' => $a,'product'=>$product
                    ]);
     }

     #[Route('/affichePd', name: 'affichePd')]
     public function affichePd(ProductRepository $product): Response
                 {
         $a=$product->findAll();
    return $this->render('product/listpd.html.twig', [
     'products' => $a,'product'=>$product
                     ]);
      }

      #[Route('/affichePP', name: 'affichePP')]
      public function addS(Request $request,ManagerRegistry $doctrine,SluggerInterface $slugger): Response
      {
          $Product = new Product();
          $form = $this->createForm(ProductType::class, $Product);
          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
                try {
                    $photo->move(
                        $this->getParameter('Product_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $Product->setPhoto($newFilename);
            }
            $em=$doctrine->getManager();
            $em->persist($Product);
            $em->flush();
              return $this->redirectToRoute('affichePd');
          }
          return $this->render('product/addP.html.twig', [
              'fp' => $form->createView(),
          ]);
      }

      #[Route('/updateP/{id}', name: 'updateP')]
                     public function updateP(Request $request, $id,SluggerInterface $slugger)
                     {
                        $Product= $this->getDoctrine()->getRepository(Product::class)->find($id);
      $formp=$this->createForm(updatePType::class,$Product);
                         $formp->handleRequest($request);
                         if($formp->isSubmitted() && $formp->isValid()){
                            $photo = $formp->get('photo')->getData();
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
                try {
                    $photo->move(
                        $this->getParameter('Product_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $Product->setPhoto($newFilename);
            }
                             $em = $this->getDoctrine()->getManager() ;
                             $em->persist($Product);
                             $em->flush();
                             return $this->redirectToRoute("affichePd");}
                    return $this->render('product/updateP.html.twig',
                             ['Products' => $Product,'Product'=>$Product, 'formp' => $formp->createView()]);
                         }

        #[route('/deleteP/{id}', name:'deleteP')]
        public function deleteP($id,ManagerRegistry $doctrine) {
    $Product=$doctrine->getRepository(Product::class)->find($id);
     $em = $this->getDoctrine()->getManager();
     $em->remove($Product);
     $em->flush();
      $this->addFlash('notice', 'delete success');
     return $this->redirectToRoute('affichePd');
                           }  
        
}
