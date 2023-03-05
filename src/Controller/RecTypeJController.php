<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Repository\RecTRepository;
use App\Repository\RecRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\RecT;




class RecTypeJController extends AbstractController
{
    #[Route('/mobile', name: 'app_mobile')]
    public function index(): Response
    {
        return $this->render('mobile/index.html.twig', [
            'controller_name' => 'MobileController',
        ]);
    }

    


    
    #[Route('/afficheRTj', name: 'afficheRTj')]
    public function listCategories(RecTRepository $rectRepository, NormalizerInterface $normalizer): JsonResponse
    {
        $rects = $rectRepository->findAll();
        $rectsNormalized = $normalizer->normalize($rects, 'json', ['groups' => "rects"]);
        $json = json_encode($rectsNormalized);
        return new JsonResponse($json, 200, [], true);
    }


    #[Route('/addRTj', name: 'addRTj')]
    public function addRJ(Request $req, NormalizerInterface $Normalizer): Response
    {$em =$this->getDoctrine()->getManager();
        $rect = new RecT();
        $rect->setNomT($req->get('nomT'));
        $em->persist($rect);
        $em->flush();
        $jsonContent = $Normalizer->normalize($rect, 'json', ['groups' => 'rects']);
        return new Response(json_encode($jsonContent));
    
    
    
    }
    #[Route('/updateRTj/{id}', name: 'updateRTj')]
    public function updateRJ(Request $req,$id, NormalizerInterface $Normalizer): Response
    {
     $em = $this->getDoctrine()->getManager();
     $rect = $em->getRepository(RecT::class)->find($id);
     $rect->setNomT($req->get('nomT'));
     $em->flush();
    
        $jsonContent = $Normalizer->normalize($rect, 'json', ['groups' => 'rects']);
        return new Response(" RecT updated succesfully".json_encode($jsonContent));
    
    }
    #[Route('/deleteRTj/{id}', name: 'deleteRj')]
    public function deleteRJ(Request $req, $id, NormalizerInterface $Normalizer): Response
    {    $em = $this->getDoctrine()->getManager();
        $rect = $em->getRepository(RecT::class)->find($id);
        $em->remove($rect);
        $em->flush();
        $jsonContent = $Normalizer->normalize($rect, 'json', ['groups' => 'rects']);
        return new Response(" RecT deleted succesfully".json_encode($jsonContent));
    
    
    }

}