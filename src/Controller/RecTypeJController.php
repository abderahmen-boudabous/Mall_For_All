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

    


    
    #[Route('/afficheRTJ', name: 'afficheRTJ')]
    public function listCategories(RecTRepository $rectRepository, NormalizerInterface $normalizer): JsonResponse
    {
        $rects = $rectRepository->findAll();
        $rectsNormalized = $normalizer->normalize($rects, 'json', ['groups' => "rects"]);
        $json = json_encode($rectsNormalized);
        return new JsonResponse($json, 200, [], true);
    }


    #[Route('/afficheJSON', name: 'afficheJSON')]
public function afficheEC(EventRepository $eventRepository, NormalizerInterface $normalizer): JsonResponse
{
    $events = $eventRepository->findBy([], ['Date' => 'ASC']);
    $eventsNormalized = $normalizer->normalize($events, 'json', ['groups' => 'events']);
    $json = json_encode($eventsNormalized);
    return new JsonResponse($json, 200, [], true);
}


#[Route("addJSON/new",name: "addJSON")]
public function addCategory(Request $req, NormalizerInterface $normalizer)
{
$em = $this->getDoctrine()->getManager() ;
$Category = new Category();
$titre = $req->get('titre');
if ($titre !== null) {
    $Category->setTitre($titre);
}

$Category ->setTitre($req->get('titre'));
$Category ->setDescription($req->get('description'));

$em->persist($Category);

$em->flush();

$jsonContent = $normalizer->normalize($Category, 'json', ['groups' => 'Category' ]);

return new Response(json_encode($jsonContent));
}



#[Route("/deleteCategoryJSON/{id}", name: "deleteCategoryJSON")]
public function deleteCategoryJSON(Request $req, $id, NormalizerInterface $normalizer)
{
    $em = $this->getDoctrine()->getManager();
    $Category = $em->getRepository(Category::class)->find($id);
    $em->remove($Category);
    $em->flush();

    $jsonContent = $normalizer->normalize($Category, 'json', ['groups' => 'Category']);
    return new Response("Category deleted successfully " . json_encode($jsonContent));
}


#[Route("updateCategoryJSON/{id}", name: "updateCategoryJSON")]
public function updateCategoryJSON(Request $req, $id, NormalizerInterface $Normalizer )
{
$em = $this->getDoctrine()->getManager() ;
$Category = $em->getRepository(Category::class)->find($id);
$Category ->setTitre($req->get('titre'));
$Category ->setDescription($req->get( 'description' ));

$em->flush();

$jsonContent = $Normalizer->normalize($Category, 'json', ['groups' => 'Category']);
return new Response("Student updated successfully " . json_encode($jsonContent) ) ;

}


}