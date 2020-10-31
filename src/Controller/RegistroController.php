<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class RegistroController extends AbstractController
{
    /**
     * @Route("/registro", name="registro")
     */
    public function index(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form ->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();            
            $user->setPassword($passwordEncoder->encodePassword($user,$form['password']->getData()));//nos permite encryptar las contraseñas
            $user->setRoles(['ROLE_USER']);
            $em->persist($user);
            $em->flush();
            $this->addFlash('exito',User::REGISTRO_EXITOSO);
            return $this->redirectToRoute('app_login');
        }
        return $this->render('registro/index.html.twig', [
            'controller_name' => 'RegistroController',
            'formulario'=>$form->createView()
        ]);
    }

    /**
     * @Route("/registroAdministradorTourSolutionsGalery", name="registroAdmin")
     */
    public function registroAdmin(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form ->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();            
            $user->setPassword($passwordEncoder->encodePassword($user,$form['password']->getData()));//nos permite encryptar las contraseñas
            $user->setRoles(['ROLE_ADMIN']);
            $em->persist($user);
            $em->flush();
            $this->addFlash('exito',User::REGISTRO_EXITOSO);
            return $this->redirectToRoute('app_login');
        }
        return $this->render('registro/RegistroAdmin.html.twig', [
            'controller_name' => 'RegistroController',
            'formulario'=>$form->createView()
        ]);
    }
}
