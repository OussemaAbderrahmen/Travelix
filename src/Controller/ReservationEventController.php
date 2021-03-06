<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactType;


class ReservationEventController extends AbstractController
{
    /**
     * @Route("/reservation/event", name="reservation_event")
     */
    public function index(ReservationRepository $repository): Response
    {
        return $this->render('reservation_event/index.html.twig', [
            'reservation' => $repository->findAll()
        ]);
    }

    /**
     * @Route("/reservation/edit/{id}", name="reservation_edit")
     */
    public function edit(\Symfony\Component\HttpFoundation\Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $this->getDoctrine()->getManager()->flush();



            return $this->redirectToRoute('reservation_event');


        }

        return $this->render('reservation_event/edit.html.twig', [
            'event' => $reservation,

            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/reservation/delete/{id}", name="reservation_delete")
     */
    public function deleteReservation($id,ReservationRepository $repository): Response
    {
        $reservation = $repository->find($id);
        $em = $this->getDoctrine()->getManager();

        $em->remove($reservation);

        $em->flush();
        return $this->redirectToRoute('reservation_event');
    }


    /**
     * @Route("/contact/{id}", name="contact")
     */
    public function email(Request $request,$id, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        $event=$this->getDoctrine()->getRepository(Reservation::class)->find($id);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $message = (new \Swift_Message('Nouveau contact'))
                // On attribue l'exp??diteur
                ->setFrom($contact['from'])
                // On attribue le destinataire
                ->setTo($contact['email'])
                // On cr??e le texte avec la vue
                ->setBody(
                    $this->renderView(
                        'contact/email.html.twig',compact('contact','event')),
                    'text/html')
            ;
            $mailer->send($message);
            $this->addFlash('message', 'Votre message a ??t?? transmis, nous vous r??pondrons dans les meilleurs d??lais.'); // Permet un message flash de renvoi
            return $this->redirectToRoute('reservation_event');

        }
        return $this->render('contact/index.html.twig',['contactForm' => $form->createView()]);
    }
}
