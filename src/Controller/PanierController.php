<?php

namespace App\Controller;
use App\Entity\Panier;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier_index')]
    public function index(PanierRepository $panierRepository): Response
    {
        return $this->render('panier/index.html.twig', [
            'paniers' => $panierRepository->findAll(),
        ]);
    }

    #[Route('/deletepanier/{id}', name: 'panier_delete')]
    public function delete(Panier $panier): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($panier);
        $entityManager->flush();

        return $this->redirectToRoute('panier_index');
    }

}
