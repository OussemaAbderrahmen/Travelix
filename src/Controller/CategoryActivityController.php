<?php

namespace App\Controller;

use App\Entity\Categoryactivity;
use App\Form\CategoryactivityType;
use App\Repository\CategoryactivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin/categories")
 */
class CategoryActivityController extends AbstractController
{

    /**
     * @Route("/", name="category_activity")
     */
    public function index(): Response
    {
        $categories=$this->getDoctrine()->getRepository(Categoryactivity::class)->findAll();
        return $this->render('category_activity/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    /**
     * @Route("/form", name="category_activityadd")
     */
    public function add(Request $request): Response
    {
        $category = new Categoryactivity();
        $form = $this->createForm(CategoryactivityType::class,$category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager=$this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();
            return $this->redirectToRoute('category_activity');
        }
        return $this->render('category_activity/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/delete/{id}", name="category_activitydelete")
     */
    public function delete($id,CategoryactivityRepository $repository): Response
    {
        $category = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('category_activity');
    }
    /**
     * @Route("/form/{id}", name="category_activityupdate")
     */
    public function update(Request $request, Categoryactivity $category): Response
    {
        $form = $this->createForm(CategoryactivityType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_activity');
        }

        return $this->render('category_activity/update.html.twig', [
            'categories' => $category,
            'form' => $form->createView(),
        ]);
    }
}
