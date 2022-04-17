<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;


class EvennementController extends AbstractController
{
    /**
     * @Route("/evennement", name="evennement")
     */
    public function index(EventRepository $repository): Response
    {
        return $this->render('evennement/index.html.twig', [
            'events' => $repository->findAll()
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/evennement/add", name="create_event")
     */
    public function createEvent(\Symfony\Component\HttpFoundation\Request $request)
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $event = new Event();
        $form = $this->createForm(EventType::class,$event);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $uploadedFile */
            $uploadedFile=$form["imagefilename"]->getData();
            $destination=$this->getParameter('kernel.project_dir')."/public/uploads";
            $originalFileName=pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME);
            $newfilename=$originalFileName.'-'.uniqid().".".$uploadedFile->guessExtension();
            $uploadedFile->move($destination,$newfilename);
            $event->setImage($newfilename);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($event);
            $manager->flush();
            return $this->redirectToRoute('evennement');
        }
        return $this->render('evennement/AddEvent.html.twig',['form'=>$form->createView()]);
    }


    /**
     * @Route("/evennement/edit/{id}", name="evennement_edit")
     */
    public function edit(\Symfony\Component\HttpFoundation\Request $request, Event $event): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile=$form["imagefilename"]->getData();
            $destination=$this->getParameter('kernel.project_dir')."/public/uploads";
            $originalFileName=pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME);
            $newfilename=$originalFileName.'-'.uniqid().".".$uploadedFile->guessExtension();
            $uploadedFile->move($destination,$newfilename);
            $event->setImage($newfilename);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('evennement');
        }
        return $this->render('evennement/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/evennement/delete/{id}", name="evennement_delete")
     */
    public function deleteEvent($id,EventRepository $repository): Response
    {
        $event = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();
         return $this->redirectToRoute('evennement');
    }


}
