<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use App\Entity\Commande;
use App\Form\CommandeType;
use symfony\Component\Validator\Constraints as Assert;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;



class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(): Response
    {
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }
    #[Route('/affichecommande', name: 'affichecommande')]
    public function affichecommande(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/affichecommande.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }




    #[Route('/nouvelleCommande/{id}', name: 'commande_nouvelle')]
    public function nouvelleCommande(Request $request, $id, ProduitRepository $rp ): Response
    {
        $p=$rp->find($id);
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commande = $form->getData();
            $commande->setProduit($p);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('produit_index');
        }

        return $this->render('commande/nouvelleCommande.html.twig', [
            'form' => $form->createView(), 'produits' => $p ,
        ]);
    }
    #[Route('/updateCommande/{id}', name: 'updateCommande')]
    public function updateCommande(CommandeRepository $repository,
    $id,ManagerRegistry $doctrine,Request $request)
    { //récupérer la commande à modifier
        $commande= $repository->find($id);
        $form=$this->createForm(CommandeType::class,$commande);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em =$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute("affichecommande"); }
        return $this->renderForm("commande/updateCommande.html.twig",
            array("f"=>$form));
    }

    #[Route('/suppCommande/{id}', name: 'suppCommande')]
    public function suppCommande($id,CommandeRepository $r,
    ManagerRegistry $doctrine): Response
    {//récupérer la commande à supprimer
    $commande=$r->find($id);
    //Action suppression
     $em =$doctrine->getManager();
     $em->remove($commande);
     $em->flush();
    return $this->redirectToRoute('affichecommande',);
    }
    #[Route('/recherche-commande', name: 'recherche-commande')]
    public function rechercheCommande(Request $request, CommandeRepository $commandeRepository)
{
    $nom = $request->get('nom');

    $commandes = $commandeRepository->findByNom($nom);
    
    

    return $this->render('commande/recherche.html.twig', [
        'commandes' => $commandes,
    ]);
}
    













    
   // +++++++++++++++++++++++++++++++++++++++++++++++ PARTIE JSON ++++++++++++++++++++++++++++++++++++++++++++++++++++//
    #[Route('/showcommande', name: 'showcommande')]
    public function showcommande(CommandeRepository $commandeRepo , SerializerInterface $serializer)
    {   
        $commandes = $commandeRepo -> FindAll();
        
        $json = $serializer->serialize($commandes, 'json', ['groups' => "commandes"]);
        return new Response($json);   
    }
    




    
}

