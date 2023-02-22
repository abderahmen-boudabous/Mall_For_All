<?php

namespace App\Controller;

use App\Entity\Favori;
use App\Repository\FavoriRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriController extends AbstractController
{
    #[Route('/favori', name: 'favori_index')]
    public function index(FavoriRepository $favoriRepository): Response
    {
        

        return $this->render('favori/index.html.twig', [
            'favoris' => $favoriRepository->findAll(),
        ]);
    }
    #[Route('/deletefavori/{id}', name: 'favori_delete')]
    public function delete(Favori $favori): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($favori);
        $entityManager->flush();

        return $this->redirectToRoute('favori_index');
    }
}
