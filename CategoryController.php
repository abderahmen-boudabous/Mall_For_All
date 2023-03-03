<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use App\Entity\Category;
use App\Entity\Event;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\CategoryFormType;
use App\Form\UpdateCategoryFormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }




    #[Route('/afficheC', name: 'afficheC')]
    public function afficheC(CategoryRepository $category): Response
    {
        $s = $category->findAll();
        return $this->render('category/listC.html.twig', [
            'categorys' => $s
        ]);
    }


    #[Route('/addC', name: 'addC')]
    public function addS(ManagerRegistry $doctrine,Request $request)
                   {$s= new Category();
    $form=$this->createForm(CategoryFormType::class,$s);
                       $form->handleRequest($request);
                       if($form->isSubmitted() && $form->isValid()){
                           $em =$doctrine->getManager() ;
                           $em->persist($s);
                           $em->flush();
                           return $this->redirectToRoute("afficheC");}
                           return $this->renderForm("category/addC.html.twig", array("f"=>$form));
                       }   


                       #[route('/deleteC/{id}', name:'deleteC')]
                       public function delete($id,ManagerRegistry $doctrine) {

                           $category=$doctrine->getRepository(Category::class)->find($id);
                           $em = $this->getDoctrine()->getManager();
                           $em->remove($category);
                           $em->flush();

                           $this->addFlash('notice', 'delete success');

                           return $this->redirectToRoute('afficheC');
                       }

       
                       
     
                       #[Route('/updateC/{id}', name: 'updateC')]
                       public function updateC(Request $request, $id)
                       {
                       $category= $this->getDoctrine()->getRepository(Category::class)->find($id);
                           $forme=$this->createForm(UpdateCategoryFormType::class,$category);
                           $forme->handleRequest($request);
                           if($forme->isSubmitted() && $forme->isValid()){
                               $em = $this->getDoctrine()->getManager() ;
                               $em->persist($category);
                               $em->flush();
                               return $this->redirectToRoute("afficheC");}
                               return $this->render('category/updateC.html.twig',
                                   ['categorys' => $category,'category'=>$category, 'forme' => $forme->createView()]);
                       }       
                       
                       

                       #[Route('/confirm_delete/{id}', name: 'confirm_delete', methods: ['GET'])]
                                public function confirmDelete($id, CategoryRepository $category, Request $request)
                                {
                                    $category = $category->find($id);

                                    $form = $this->createFormBuilder()
                                        ->add('confirm', SubmitType::class, ['label' => 'Delete', 'attr' => ['class' => 'btn btn-danger']])
                                        ->getForm();

                                    $form->handleRequest($request);

                                    if ($form->isSubmitted() && $form->isValid()) {
                                        $em = $this->getDoctrine()->getManager();
                                        $em->remove($category);
                                        $em->flush();

                                        $this->addFlash('success', 'Record deleted successfully!');

                                        return $this->redirectToRoute('afficheC');
                                    }

                                    return $this->render('category/confirm_delete.html.twig', [
                                        'category' => $category,
                                        'form' => $form->createView(),
                                    ]);
                                }


                                



}
