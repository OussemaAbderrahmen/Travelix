<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieBackController extends AbstractController
{
    /**
     * @Route("/categorie/back", name="categorie_back")
     */
    public function index(CategoryRepository $repository): Response
    {
        return $this->render('categorie_back/index.html.twig', [
            'category' => $repository->findAll(),
        ]);
    }
    /**
     * @Route("/categorie/add", name="categorie_add")
     */
    public function addCategory(CategoryRepository $repository,Request $request): Response
    {
        $categorie = new Category();
        $form = $this->createForm(CategoryType::class,$categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){


            $manager = $this->getDoctrine()->getManager();
            $manager->persist($categorie);
            $manager->flush();
            return $this->redirectToRoute('categorie_back');
        }
        return $this->render('categorie_back/categorieAdd.html.twig',['form'=>$form->createView()]);
    }

    /**
     * @Route("/category/edit/{id}", name="categorie_edit")
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('categorie_back');
        }
        return $this->render('categorie_back/categoryEdit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/category/delete/{id}", name="category_delete")
     */
    public function deleteEvent($id,CategoryRepository $repository): Response
    {
        $categorie = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute('categorie_back');
    }
}
