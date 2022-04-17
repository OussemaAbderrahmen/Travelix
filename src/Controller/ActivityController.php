<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType;
use App\Repository\ActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin/activitys")
 */
class ActivityController extends AbstractController
{

    /**
     * @Route("/", name="activity_index")
     */
    public function list()
    {
        $activity = $this->getDoctrine()->getRepository(Activity::class)->findAll();
        return $this->render('activity/index.html.twig', [
            'activity' => $activity,
        ]);
    }


    /**
     * @Route("/form", name="activity_add")
     * @param Request $request
     */
    public function add(Request $request)
    {
        $activity = new Activity();
        $form = $this->createForm(ActivityType::class,$activity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager=$this->getDoctrine()->getManager();
            $activity->setLikes(0);
            $manager->persist($activity);
            $manager->flush();
            return $this->redirectToRoute('activity_index');
        }
        return $this->render('activity/add.html.twig', [
            'form' => $form->createView()
        ]);


    }
    /**
     * @Route("/form/{id}", name="activity_update")
     */
    public function edit(Request $request, Activity $activity): Response
    {
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('activity_index');
        }

        return $this->render('activity/update.html.twig', [
            'activity' => $activity,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/delete/{id}", name="activity_delete")
     */
    public function delete($id,ActivityRepository $repository): Response
    {
        $activity = $repository->find($id);
        $em = $this->getDoctrine()->getManager();

        $em->remove($activity);

        $em->flush();
        return $this->redirectToRoute('activity_index');
    }
}
