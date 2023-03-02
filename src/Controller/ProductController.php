<?php

namespace App\Controller;



use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Entity\Comment;
use App\Entity\Shop;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\ProductType;
use App\Form\UpdatePType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CommentFormType;



use Symfony\Component\String\Slugger\SluggerInterface;



class ProductController extends AbstractController
{
    

    #[Route('/afficheP', name: 'afficheP')]
     public function afficheP(ProductRepository $product): Response
                 {

                    
                     $a=$product->findAll();
                     
                     return $this->render('product/listp.html.twig', [
                     'products' => $a,'product'=>$product,
 
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
            $photo2 = $form->get('photo2')->getData();
            if ($photo2) {
                $originalFilename = pathinfo($photo2->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo2->guessExtension();
                try {
                    $photo2->move(
                        $this->getParameter('Product_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $Product->setPhoto2($newFilename);
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
            $photo2 = $formp->get('photo2')->getData();
            if ($photo2) {
                $originalFilename = pathinfo($photo2->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo2->guessExtension();
                try {
                    $photo2->move(
                        $this->getParameter('Product_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $Product->setPhoto2($newFilename);
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

    #[Route('/Detailproduct/{id}', name: 'Detailproduct')]
        public function plans($id)
        {

            $product = $this->getDoctrine()->getRepository(Product::class)->findBy([
                'id' => $id,]);
           $comments = $this->getDoctrine()->getRepository(Comment::class)->findBy(['product' => $product]);
            return $this->render('product/detailprod.html.twig', [
                'products' => $product,
                'comments' => $comments
                
            ]);
        }
        public function afficheM(CommentRepository $comment): Response
            {
                $comments = $comment->findAll();

                return $this->render('product/comment.html.twig', [
                    'comments' => $comments,
                    'comment' => $comment
                ]);
            }


            #[Route('/addcomment', name: 'addcomment')]
               public function addM(ManagerRegistry $doctrine,Request $request, EntityManagerInterface $entityManager)
           {$m= new Comment();
                 $id = $request->query->get('id');
           $formc = $this->createForm(CommentFormType::class, $m, [
              'id' => $request->query->get('id'),
         ]);
            $formc->handleRequest($request);
        if($formc->isSubmitted() && $formc->isValid()){
        $em =$doctrine->getManager() ;
         $em->persist($m);
         $em->flush();
      return $this->redirectToRoute("Detailproduct", ['id' => $id]);}
        return $this->renderForm("product/comment.html.twig",
       array("formc"=>$formc));
          }

}

       

                           
                                                  
                           
        


