<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\FournisseurType;
use App\Repository\FournisseurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
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

    #[Route('/SendEmail/{email}', name: 'email')]
    public function emailSupplier($email): Response
    {
        return $this->render('fournisseur/emailFournisseur.html.twig', ['email' => $email,]);
    }

    #[Route('/search/', name: 'search')]
    public function searchAction(request $request, FournisseurRepository $fournisseurRepository) : JsonResponse
    {
        return new JsonResponse($fournisseurRepository->search($request->request->get("search")));
    }


//-----------------------------------------CRUD pour sprint mobile (utilistation de JSON )------------------------------//

    #[Route("/AllSuppliers", name: "AllSuppliers")]
    public function AllSuppliers(FournisseurRepository   $repo, SerializerInterface $serializer, NormalizerInterface $normalizer)
    {
        $suppliers = $repo->findAll();
        //* Nous utilisons la fonction normalize qui transforme le tableau d'objets categories en  tableau associatif simple.
        //$categoriesNormalises = $normalizer->normalize($suppliers, 'json', ['groups' => "suppliers"]);

        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
        //$json = json_encode($categoriesNormalises);

         $json = $serializer->serialize($suppliers, 'json', ['groups' => "suppliers"]);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
    }

    #[Route("AddSupplier/new", name: "AddSupplier")]
    public function AddSupplier(Request $req,   NormalizerInterface $Normalizer, SluggerInterface $slugger)
    {

        $em = $this->getDoctrine()->getManager();
        $supplier = new Fournisseur();
        $supplier->setnom($req->get('nom'));
        $supplier->setCategorie(($req->get('categorie')));
        $supplier->setTel($req->get('tel'));
        $supplier->setAddress($req->get('address'));
        $supplier->setEmail($req->get('email'));
        $supplier->setWebsite($req->get('website'));
        //image
        $img = $req->get('img')->getData();
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
            $supplier->setImg($newFilename);
        $em->persist($supplier);
        $em->flush();

        $jsonContent = $Normalizer->normalize($supplier, 'json', ['groups' => 'suppliers']);
        return new Response(json_encode($jsonContent));
    }
    }
    #[Route("UpdateSupplier/{id}", name: "UpdateSupplier")]
    public function UpdateSupplier(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $supplier = $em->getRepository(Fournisseur::class)->find($id);
        $supplier->setnom($req->get('nom'));
        $supplier->setCategorie(($req->get('categorie')));
        $supplier->setTel($req->get('tel'));
        $supplier->setAddress($req->get('address'));
        $supplier->setEmail($req->get('email'));
        $supplier->setWebsite($req->get('website'));
        $supplier->setImg($req->get('img'));

        $em->flush();
        $jsonContent = $Normalizer->normalize($supplier, 'json', ['groups' => 'suppliers']);
        return new Response("supplier updated successfully " . json_encode($jsonContent));
    }

    #[Route("deletesupplier/{id}", name: "deletesupplier")]
    public function deletesupplier(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $supplier = $em->getRepository(Fournisseur::class)->find($id);
        $em->remove($supplier);
        $em->flush();
        $jsonContent = $Normalizer->normalize($supplier, 'json', ['groups' => 'suppliers']);
        return new Response("supplier deleted successfully " . json_encode($jsonContent));
    }
    #[Route("detailsSupplier/{id}", name: "detailsSupplier")]
    public function detailsSupplier(Request $req, $id, SerializerInterface  $serializer)
    {

        $em = $this->getDoctrine()->getManager();
        $supplier = $em->getRepository(Fournisseur::class)->find($id);

        $json = $serializer->serialize($supplier, 'json', ['groups' => "suppliers"]);
        return new Response($json);}



}
