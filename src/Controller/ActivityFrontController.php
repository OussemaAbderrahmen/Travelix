<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Categoryactivity;
use App\Repository\CategoryactivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivityFrontController extends AbstractController
{
    /**
     * @Route("/activity/front", name="activity_front")
     */
    public function index(): Response
    {
        return $this->render('activity_front/index.html.twig', [
            'controller_name' => 'ActivityFrontController',
        ]);
    }
    /**
     * @Route("/listactivityfront", name="activity_frontlist")
     */
    public function list(): Response
    {
        $activity=$this->getDoctrine()->getRepository(Activity::class)->findAll();
        $categories=$this->getDoctrine()->getRepository(Categoryactivity::class)->findAll();
        return $this->render('activity_front/list.html.twig', [
            'activity' => $activity,
            'categories'=> $categories,
        ]);
    }
    /**
     * @Route("/consultactivityfront/{id}", name="activity_frontconsult")
     */
    public function activitedetail(Activity $activity): Response
    {

        $em = $this->getDoctrine()->getManager();

        $em->persist($activity);
        $em->flush();

        return $this->render('activity_front/listconsult.html.twig', [
            'activity' => $activity,

        ]);
    }
    /**
     * @Route("/likeactivity/{id}", name="activity_frontlike")
     */
    public function likes(Activity $activity): Response
    {
        $activities=$this->getDoctrine()->getRepository(Activity::class)->findAll();
        $categories=$this->getDoctrine()->getRepository(Categoryactivity::class)->findAll();

        $activity->setLikes($activity->getLikes()+1);
        return $this->render('activity_front/list.html.twig', [
            'activity' => $activities,
            'categories'=> $categories,
        ]);
    }

    /**
     * @Route("/listactivitycategory/{id}", name="activity_frontlistcategory")
     */
    public function listCategory($id, CategoryactivityRepository $repository): Response
    {

        $categories = $this->getDoctrine()
            ->getRepository(Categoryactivity::class)
            ->find($id);

        $activity = $categories->getActivities();
        return $this->render('activity_front/list.html.twig', [
            'activity' => $activity,
            'categories'=> $categories,
        ]);
    }
}
