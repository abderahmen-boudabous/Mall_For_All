<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;



class ApiproductController extends AbstractController
{
    #[Route('/apiproduct', name: 'app_apiproduct')]
    public function index(): Response
    {
        return $this->render('apiproduct/index.html.twig', [
            'controller_name' => 'ApiproductController',
        ]);
    }

    #[Route('/affichePj', name: 'affichePj')]
     public function affichePj(ProductRepository $product , SerializerInterface $serializer): Response
                 {
                    
                     $a=$product->findAll();
                     $json = $serializer->serialize($a, 'json', ['groups' => "products"]);
                    
                     return new Response($json);
      }

      #[Route('/affichePdj', name: 'affichePdj')]
     public function affichePdj(ProductRepository $product , SerializerInterface $serializer): Response
                 {
         $a=$product->findAll();
         $json = $serializer->serialize($a, 'json', ['groups' => "products"]);
                     
         return new Response($json);
      }

      #[Route('/affichePPj', name: 'affichePPj')]
      public function addBj(Request $request,NormalizerInterface $Normalizer): Response
      {
        $em = $this->getDoctrine()->getManager();
        $product = new Product();
        $product->setName($request->get('Name'));
        $product->setPrice($request->get('Price'));
        $product->setType($request->get('Type'));
        $product->setShop($request->get('Shop'));
        $product->setStock($request->get('Stock'));
        
        
        $em->persist($product);
        $em->flush();

        $jsonContent = $Normalizer->normalize($product, 'json', ['groups' => 'products']);
        return new Response(json_encode($jsonContent));
      }

      #[Route("/product/{id}", name: "product")]
    public function productId($id, NormalizerInterface $normalizer, ProductRepository $repo)
    {
        $product = $repo->find($id);
        $productNormalises = $normalizer->normalize($product, 'json', ['groups' => "products"]);
        return new Response(json_encode($productNormalises));
    }

    #[Route("updatePj/{id}", name: "updatePj")]
    public function updateProductJSON(Request $request, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);
        $product->setName($request->get('Name'));
        $product->setPrice($request->get('Price'));
        $product->setType($request->get('Type'));
        $product->setPhoto($request->get('Photo'));
        $product->setStock($request->get('Stock'));
        $product->setShop($request->get('Shop'));
        $product->setPhoto2($request->get('Photo2'));

        $em->flush();

        $jsonContent = $Normalizer->normalize($product, 'json', ['groups' => 'products']);
        return new Response("Product updated successfully " . json_encode($jsonContent));
    }

    #[Route("deletePj/{id}", name: "deletePj")]
    public function deleteProductJSON($id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $Product = $em->getRepository(Product::class)->find($id);
        $em->remove($Product);
        $em->flush();
        $jsonContent = $Normalizer->normalize($Product, 'json', ['groups' => 'products']);
        return new Response("Product deleted successfully " . json_encode($jsonContent));
    }


}
