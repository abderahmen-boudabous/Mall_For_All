<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Favori;
use App\Entity\Panier;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'produit_index')]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }
    #[Route('/showproduit', name: 'produit_show')]
    public function showProduit(Produit $produit): Response
    {
        return $this->render('produit/showProduit.html.twig', [
            'produit' => $produit,
        ]);
    }
    #[Route('/addfavori/{id}/favori', name: 'produit_favori')]
    public function addFavori(Produit $produit, Request $request): Response
    {
        $favori = new Favori();
       
        $favori->setProduit($produit);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($favori);
        $entityManager->flush();

        return $this->redirectToRoute('produit_index');
    }
    #[Route('/favori', name: 'favori')]
    public function Favori(Produit $produit, Request $request): Response
    {
        $favori = new Favori();
       
        $favori->setProduit($produit);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($favori);
        $entityManager->flush();

        return $this->redirectToRoute('produit_index');
    }


    #[Route('/addpanier/{id}/panier', name: 'produit_panier')]
    public function addpanier(Produit $produit, Request $request): Response
    {
        $panier = new Panier();
       
        $panier->setProduit($produit);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($panier);
        $entityManager->flush();

        return $this->redirectToRoute('produit_index');
    }
    #[Route('/panier', name: 'panier')]
    public function Panier(Produit $produit, Request $request): Response
    {
        $panier  = new Panier();
       
        $panier->setProduit($produit);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($panier);
        $entityManager->flush();

        return $this->redirectToRoute('produit_index');
    }



   

}
