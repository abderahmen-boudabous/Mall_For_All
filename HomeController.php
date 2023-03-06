<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use DateTime;
use App\Repository\EventRepository;







class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Event::class);
        $events = $repository->findAll();
    
        $notifications = [];
        foreach ($events as $event) {
            if ($event->isLive()) {
                $notifications[] = "The event " . $event->getNom() . " is happening today!";
            }
        }
        
        $liveEvents = $this->getDoctrine()->getRepository(Event::class)->findLiveEvents();    
        $liveEvents = $repository->findBy(['Date' => new DateTime()]);

        return $this->render('base.html.twig', [
            'liveEvents' => $liveEvents,
            'notifications' => $notifications
        ]);
        
        // return $this->render('home/index.html.twig', [
        //     'events' => $events,
        //     'notifications' => $notifications,
        //     'liveEvents' => $liveEvents,
        // ]);
    }


}
// $liveEvents = $this->getDoctrine()->getRepository(Event::class)->findLiveEvents();
// return $this->render('afficheE.html.twig', ['liveEvents' => $liveEvents]);
