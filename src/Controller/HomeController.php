<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/dashb', name: 'app_dashb')]
    public function index2(): Response
    {
        return $this->render('dashb/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/detail', name: 'app_detail')]
    public function index3(): Response
    {
        return $this->render('detail/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

}
