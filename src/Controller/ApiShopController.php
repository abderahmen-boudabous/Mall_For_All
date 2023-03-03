<?php

namespace App\Controller;

use App\Entity\Shop;
use App\Repository\ShopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;


class ApiShopController extends AbstractController
{
    #[Route('/api/shop', name: 'app_api_shop')]
    public function index(): Response
    {
        return $this->render('api_shop/index.html.twig', [
            'controller_name' => 'ApiShopController',
        ]);
    }

   
      #[Route('/afficheBdj', name: 'afficheBdj')]
     public function afficheBdj(ShopRepository $shop , SerializerInterface $serializer): Response
                 {

                  $a=$shop->findAll();
                  $json = $serializer->serialize($a, 'json', ['groups' => "shops"]);
                     
                   return new Response($json);
                 }

                 #[Route('/afficheBj', name: 'afficheBj')]
                 public function afficheBj(ShopRepository $shop , SerializerInterface $serializer): Response
                             {
                                $a=$shop->findAll();
                                $json = $serializer->serialize($a, 'json', ['groups' => "shops"]);
                                   
                                return new Response($json);
                             }
            

      #[Route('/afficheBBj', name: 'afficheBBj')]
      public function addBj(Request $request,NormalizerInterface $Normalizer): Response
      {
        $em = $this->getDoctrine()->getManager();
        $shop = new Shop();
        $shop->setName($request->get('Name'));
        $shop->setDescription($request->get('Description'));
        $shop->setEmail($request->get('Email'));
        $shop->setDate($request->get('Date'));

        $em->persist($shop);
        $em->flush();

        $jsonContent = $Normalizer->normalize($shop, 'json', ['groups' => 'shops']);
        return new Response(json_encode($jsonContent));
      }

      #[Route("/shop/{id}", name: "shop")]
    public function shopId($id, NormalizerInterface $normalizer, ShopRepository $repo)
    {
        $shop = $repo->find($id);
        $shopNormalises = $normalizer->normalize($shop, 'json', ['groups' => "shops"]);
        return new Response(json_encode($shopNormalises));
    }

    #[Route("updateBj/{id}", name: "updateBj")]
    public function updateProductJSON(Request $request, $id, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $shop = $em->getRepository(Shop::class)->find($id);
        $shop->setName($request->get('Name'));
        $shop->setDescription($request->get('Description'));
        $shop->setEmail($request->get('Email'));

        $em->flush();

        $jsonContent = $Normalizer->normalize($shop, 'json', ['groups' => 'shops']);
        return new Response("Shop updated successfully " . json_encode($jsonContent));
    }

    #[Route("deleteBj/{id}", name: "deleteBj")]
    public function deleteProductJSON($id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $shop = $em->getRepository(Shop::class)->find($id);
        $em->remove($shop);
        $em->flush();
        $jsonContent = $Normalizer->normalize($shop, 'json', ['groups' => 'shops']);
        return new Response("Shop deleted successfully " . json_encode($jsonContent));
    }



}
