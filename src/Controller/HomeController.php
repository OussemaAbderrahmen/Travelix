<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'publicitys'=> []

        ]);
    }
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if($this->getUser()){
                return $this->redirectToRoute('home');
        }
        $user =new User();
        $form = $this->createForm(ClientType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if(!empty($this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $form["email"]->getData()]))){
                $this->addFlash('danger', 'Duplicated Email');
                return $this->render('security/inscription.html.twig', [
                    'controller_name' => 'HomeController',
                    'form' => $form->createView(),
                    'user'=>$user
                ]);
            }
            $entityManager = $this->getDoctrine()->getManager();

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['pictureUser']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/user';
                $newFilename = 'user-'. uniqid(). date('M-Y') . '-' . $uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $user->setPictureUser($newFilename);
            }
            $user->setRole("ROLE_CLIENT");

            $user->setPassword($passwordEncoder->encodePassword($user, $form["password"]->getData()));

            $entityManager->persist($user);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'User added successfully');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/inscription.html.twig', [
            'controller_name' => 'HomeController',
            'form' => $form->createView(),
            'user'=>$user
        ]);
    }






}
