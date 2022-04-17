<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Reservation;
use App\Form\ReservationFrontType;
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ReservationEventFrontController extends AbstractController
{
    /**
     * @Route("/reservation/event/front", name="reservation_event_front")
     */
    public function index(): Response
    {
        return $this->render('reservation_event_front/index.html.twig', [
            'controller_name' => 'ReservationEventFrontController',

        ]);
    }

    /**
     * @Route("/reservation/facture", name="reservation_facture")
     */
    public function showfacture(): Response
    {


        return $this->render('reservation_event_front/factureEvent.html.twig', [

            'controller_name' => 'ReservationEventFrontController',

        ]);
    }
    /**
     * @Route("/reservation/add/{id}", name="create_reservationFront")
     */
    public function createResevationFront(Request $request, Event $events)
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $reservation = new Reservation();
        $form = $this->createForm(ReservationFrontType::class,$reservation);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($reservation);
            $manager->flush();
            return $this->redirectToRoute('reservation_facture');
        }
        return $this->render('evennement_front/consulterEvent.html.twig',[
            'events' => $events,

            'form'=>$form->createView()

        ]);
    }

    /**
     * @route ("formulaire/pdf", name="PDF")
     */
    function generePDF()
    {
        // Configure Dompdf according to your needs

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');


        //$form = $repository->find($ref);
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('pdf/pdfFacture.html.twig', [
            //'form' => $form
        ]);
        //$html .= '';
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("facture.pdf", [
            "Attachment" => true
        ]);
    }
    /**
     * @Route("/payment/success", name="successPaiment")
     */
    public function success(): Response
    {
        return $this->render('reservation_event_front/success.html.twig', [
            'controller_name' => 'ReservationEventFrontController',

        ]);
    }
    /**
     * @Route("/payment/error", name="errorPaiment")
     */
    public function error(): Response
    {
        return $this->render('reservation_event_front/error.html.twig', [
            'controller_name' => 'ReservationEventFrontController',

        ]);
    }

    /**
     * @Route("/create-checkout-session", name="checkout")
     */
    public function checkout(): Response
    {
        \Stripe\Stripe::setApiKey('sk_test_4eC39HqLyjWDarjtT1zdp7dc');
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'T-shirt',
                    ],
                    'unit_amount' => 2000,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('successPaiment',[],UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('errorPaiment',[],UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        return new JsonResponse(['id' => $session->id ]);
    }
}
