<?php

namespace App\Controller;

use App\Entity\RecT;
use App\Repository\RecTRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class MobileRController extends AbstractController
{
    #[Route('/mobile/r', name: 'app_mobile_r')]
    public function index(): Response
    {
        return $this->render('mobile_r/index.html.twig', [
            'controller_name' => 'MobileRController',
        ]);
    }
    #[Route('/afficheRj', name: 'afficheRj')]
                 public function afficheRj(RecTRepository $rect , SerializerInterface $serializer): Response
                             {
                                $a=$rect->findAll();
                                $json = $serializer->serialize($a, 'json', ['groups' => "rects"]);

                                return new Response($json);
                             }
}
