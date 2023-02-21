<?php

namespace App\Controller;

use App\Entity\CategorieF;
use App\Form\CategorieFType;
use App\Repository\CategorieFRepository;
use App\Repository\FournisseurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieFController extends AbstractController
{
    #[Route('/categorief', name: 'app_categorie_f')]
    public function index(): Response
    {
        return $this->render('categorie_f/index.html.twig', [
            'controller_name' => 'CategorieFController',
        ]);
    }

    #[Route('/Categories', name: 'afficheCF')]
    public function afficheCF(CategorieFRepository $r): Response
    {
        $cf=$r->findAll();
        return $this->render('categorie_f/categorief.html.twig', [
            'categories' => $cf,
        ]);
    }

    #[Route('/add_Categorie', name: 'addCF')]
    public function addCF(ManagerRegistry $doctrine , request $request): Response
    {
        $categorie = new CategorieF();
        $form=$this->createForm(CategorieFType::class,$categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('afficheCF',);
        }
        return $this->renderForm('categorie_f/Addcf.html.twig',array("f"=>$form));
    }

    #[Route('/delete_Categorie/{id}', name: 'suppCF')]
    public function SuppCF(CategorieFRepository $r, FournisseurRepository $f, ManagerRegistry $doctrine,$id): Response
    {
        //recuperer la categorie a supprimer
        $cf=$r->find($id);
       /* // action suppression
        $em = $doctrine->getManager();
        $em->remove($cf);
        $em->flush();
        return $this->redirectToRoute('afficheCF',);*/
        if ($f->findOneBy(['categorie' => $cf->getId()])) {
            $this->addFlash('alert', 'ALERT! YOU CAN NOT DELETE THIS CATEGORY! SOME SUPPLIERS ARE USING IT !');
            return $this->redirectToRoute('afficheCF',);
        } else {
            // action suppression
            $em = $doctrine->getManager();
            $em->remove($cf);
            $em->flush();
            return $this->redirectToRoute('afficheCF',);
        }

    }

    #[Route('/update_Categorie/{id}', name: 'updateCF')]
    public function updateCF(CategorieFRepository $r,ManagerRegistry $doctrine , request $request,$id): Response
    {
        $c=$r->find($id);

        $form=$this->createForm(CategorieFType::class,$c);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $em=$doctrine->getManager();
            $em->persist($c);
            $em->flush();
            return $this->redirectToRoute('afficheCF',);
        }
        return $this->renderForm('categorie_f/updateC.html.twig',array("f"=>$form));
    }
}
