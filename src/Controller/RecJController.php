<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Entity\Rec;
use App\Entity\RecT;



class RecJController extends AbstractController
{
    #[Route('/rec/j', name: 'app_rec_j')]
    public function index(): Response
    {
        return $this->render('rec_j/index.html.twig', [
            'controller_name' => 'RecJController',
        ]);
    }
    #[Route('/afficheRj', name: 'afficheRj')]
    public function afficheRJ(RecTRepository $repo, NormalizerInterface $normalizer)
    {
        $recs= $repo->findAll();
        $recsNormalises = $normalizer->normalize($recs, 'json');
        $json = json_encode($recsNormalises) ;
        return new Response ($json);
    }
    #[Route('/addRj', name: 'addRj')]
public function addRJ(Request $req, NormalizerInterface $Normalizer): Response
{$em =$this->getDoctrine()->getManager();
    $rect = new RecT();
    $rect->setNomT($req->get('nomT'));
    $em->persist($rect);
    $em->flush();
    $jsonContent = $Normalizer->normalize($rect, 'json', ['groups' => 'rects']);
    return new Response(json_encode($jsonContent));



}
#[Route('/updateRj/{id}', name: 'updateRj')]
public function updateRJ(Request $req,$id, NormalizerInterface $Normalizer): Response
{
 $em = $this->getDoctrine()->getManager();
 $rect = $em->getRepository(RecT::class)->find($id);
 $rect->setNomT($req->get('nomT'));
 $em->flush();

    $jsonContent = $Normalizer->normalize($rect, 'json', ['groups' => 'rects']);
    return new Response(" RecT updated succesfully".json_encode($jsonContent));

}
#[Route('/deleteRj/{id}', name: 'deleteRj')]
public function deleteRJ(Request $req, $id, NormalizerInterface $Normalizer): Response
{    $em = $this->getDoctrine()->getManager();
    $rect = $em->getRepository(RecT::class)->find($id);
    $em->remove($rect);
    $em->flush();
    $jsonContent = $Normalizer->normalize($rect, 'json', ['groups' => 'rects']);
    return new Response(" RecT deleted succesfully".json_encode($jsonContent));


}
}