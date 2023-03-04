<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class LikesController extends AbstractController
{
    #[Route('/likes/{id}', name: 'app_likes')]
    public function index($id , ManagerRegistry $doctrine): Response
    {   
        $r=$this->getDoctrine()->getRepository(Comment::Class);
        //utiliser la fonction findby()
        $c=$r->find($id);

        $likes = $c->getLikes();

        //Incrémentation
       // if ($likes=0) {
            $plusDeLikes = $likes + 1 ;
       // } else {  $plusDeLikes = 1 ;}
      

        //Je mets à jour mon champ la table Post
        $c->setLikes($plusDeLikes);
        $em =$doctrine->getManager() ;
        $em->flush();

        return $this->redirectToRoute('Detailproduct', ['id' => $c->getProduct()->getId()]);

         ;
    }
    #[Route('/dislikes/{id}', name: 'app_dislikes')]
    public function app_Dislikes($id , ManagerRegistry $doctrine): Response
    {   
        $r=$this->getDoctrine()->getRepository(Comment::Class);
        //utiliser la fonction findby()
        $c=$r->find($id);

        $dislikes = $c->getDislike();

        //Incrémentation
        
        $plusDedislikes = $dislikes + 1 ;
        
      

        //Je mets à jour mon champ la table Post
        $c->setDislike($plusDedislikes);
        $em =$doctrine->getManager() ;
        $em->flush();

        return $this->redirectToRoute('Detailproduct', ['id' => $c->getProduct()->getId()]);

         ;
    }

    #[Route('/likess/{id}', name: 'app_likess')]
public function indexx($id , ManagerRegistry $doctrine): Response
{   
    $r=$this->getDoctrine()->getRepository(Product::class);
    //utiliser la fonction findby()
    $c=$r->find($id);

    $likes = $c->getLikes();

    //Incrémentation
    $plusDeLikes = $likes + 1 ;
    
    //Je mets à jour mon champ la table Post
    $c->setLikes($plusDeLikes);
    $em =$doctrine->getManager() ;
    $em->flush();

    return $this->redirectToRoute('Detailproduct', ['id' => $c->getId()]);
}

#[Route('/dislikess/{id}', name: 'app_dislikess')]
public function app_Dislikess($id , ManagerRegistry $doctrine): Response
{   
    $r=$this->getDoctrine()->getRepository(Product::class);
    //utiliser la fonction findby()
    $c=$r->find($id);

    $dislikes = $c->getDislike();

    //Incrémentation
    $plusDedislikes = $dislikes + 1 ;
      
    //Je mets à jour mon champ la table Post
    $c->setDislike($plusDedislikes);
    $em =$doctrine->getManager() ;
    $em->flush();

    return $this->redirectToRoute('Detailproduct', ['id' => $c->getId()]);
}

}