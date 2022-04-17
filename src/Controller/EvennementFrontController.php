<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\Reservation;
use App\Form\ReservationFrontType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvennementFrontController extends AbstractController
{
    /**
     * @Route("/evennement/front", name="evennement_front")
     */
    public function index(): Response
    {
        $categories= $this->getDoctrine()->getRepository(Category::class)->findAll();
        $event= $this->getDoctrine()->getRepository(Event::class)->findAll();
        return $this->render('evennement_front/listevent.html.twig', [
            'events' => $event,
            'categories' => $categories,

        ]);
    }
    /**
     * @Route("/evennement/reservation", name="evennement_reservation")
     */
    public function reserver(): Response
    {
        return $this->render('evennement_front/ReseservationEvent.html.twig', [
            'controller_name' => 'EvennementFrontController',
        ]);
    }

    /**
     * @Route("/evennement/show/{id}", name="evennement_show")
     */
    public function showEvent(Request $request,Event $events): Response
    {
        $em =$this->getDoctrine()->getManager();
        $events->setNbViews($events->getNbViews()+1);
        $em->persist($events);
        $em->flush();

        $reservation = new Reservation();
        $form = $this->createForm(ReservationFrontType::class,$reservation);
        $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid()){

           $manager = $this->getDoctrine()->getManager();$manager->persist($reservation);
           $manager->flush();
           return $this->redirectToRoute('reservation_facture');
      }


        return $this->render('evennement_front/consulterEvent.html.twig', [
            'events' => $events,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/trier/event", name="trierpardate")
     */
    public function listpartrie()
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->listOrderBydate();
        $categories= $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('evennement_front/ListEventTrier.html.twig', [
            "events" => $event,
            'categories' => $categories,]);

    }

    /**
     * @Route("/trierPrice/event", name="trierparPrice")
     */
    public function listtriePrice()
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->listOrderByPrice();
        $categories= $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('evennement_front/ListEventTrier.html.twig', [
            "events" => $event,
            'categories' => $categories,]);

    }

}
