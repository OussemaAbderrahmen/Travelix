<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackTemplateController extends AbstractController
{
    /**
     * @Route("/admin/", name="back_template")
     */
    public function index(): Response
    {
        return $this->render('back_template/index.html.twig', [
            'controller_name' => 'BackTemplateController',
        ]);
    }


}
