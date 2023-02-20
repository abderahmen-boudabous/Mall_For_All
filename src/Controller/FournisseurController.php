<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\FournisseurType;
use App\Repository\FournisseurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class FournisseurController extends AbstractController
{
    #[Route('/fournisseur', name: 'app_founisseur')]
    public function index(): Response
    {
        return $this->render('fournisseur/index.html.twig', [
            'controller_name' => 'FournisseurController',
        ]);
    }
    #[Route('/SuppliersForAdmin', name: 'afficheF')]
    public function afficheF(FournisseurRepository $r): Response
    {
        $f=$r->findAll();
        return $this->render('fournisseur/fournisseurAdmin.html.twig', [
            'fournisseurs' => $f,
        ]);
    }
    #[Route('/SuppliersForSeller', name: 'afficheFV')]
    public function afficheFV(FournisseurRepository $r): Response
    {
        $f=$r->findAll();
        return $this->render('fournisseur/fournisseurVendeur.html.twig', [
            'fournisseurs' => $f,
        ]);
    }
    #[Route('/SuppliersForClient', name: 'afficheFC')]
    public function afficheFC(FournisseurRepository $r): Response
    {
        $f=$r->findAll();
        return $this->render('fournisseur/fournisseurClient.html.twig', [
            'fournisseurs' => $f,
        ]);
    }

    #[Route('/Add_Supplier', name: 'addF')]
    public function addF(ManagerRegistry $doctrine , request $request, SluggerInterface $slugger): Response
    {
        $fournisseur = new Fournisseur();
        $form=$this->createForm(FournisseurType::class,$fournisseur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $img = $form->get('img')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($img) {
                $originalFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$img->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $img->move(
                        $this->getParameter('fournisseur_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $fournisseur->setImg($newFilename);
            }
            $em=$doctrine->getManager();
            $em->persist($fournisseur);
            $em->flush();
            return $this->redirectToRoute('afficheF',);
        }
        return $this->renderForm('fournisseur/addf.html.twig',array("f"=>$form));
    }

    #[Route('/Delete_Supplier/{id}', name: 'suppF')]
    public function SuppF(FournisseurRepository $r, ManagerRegistry $doctrine,$id): Response
    {
        //recuperer le fournisseur a supprimer
        $f=$r->find($id);
        // action suppression
        $em = $doctrine->getManager();
        $em->remove($f);
        $em->flush();
        return $this->redirectToRoute('afficheF',);
    }

    #[Route('/Update_Supplier/{id}', name: 'updateF')]
    public function updateF(FournisseurRepository  $r,ManagerRegistry $doctrine , request $request,$id): Response
    {
        $f=$r->find($id);

        $form=$this->createForm(FournisseurType::class,$f);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->persist($f);
            $em->flush();
            return $this->redirectToRoute('afficheF',);
        }
        return $this->renderForm('fournisseur/updatef.html.twig',array("f"=>$form));
    }

    #[Route('/detail/{id}', name: 'detailF')]
    public function detail(FournisseurRepository  $r, $id): Response
    {
        $f=$r->find($id);
        return $this->render('fournisseur/detailf.html.twig', [ 'f' => $f,]);
    }
    #[Route('/detailForSeller/{id}', name: 'detailFV')]
    public function detailV(FournisseurRepository  $r, $id): Response
    {
        $f=$r->find($id);
        return $this->render('fournisseur/detailfVendeur.html.twig', [ 'f' => $f,]);
    }
}
