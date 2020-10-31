<?php

namespace App\Controller;
use App\Entity\Galeria;
use App\Entity\User;
use App\Form\UserType;
use Knp\Component\Pager\PaginatorInterface ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(PaginatorInterface  $paginator, Request $request): Response
    {   #em emtity manager
        $user = $this->getUser();// sirve para obtener al usuario actualmente logeado
        
        if($user){
            $em = $this->getDoctrine()->getManager();
            
            $query = $em->getRepository(Galeria::class)->BuscarTodasLasFotos();//para buscar todos los datos de la tabla
            $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page',1),
                6
            );    
            return $this->render('dashboard/index.html.twig', [
                'pagination' => $pagination,
                
                
            ]);
        }else{
            return $this->redirectToRoute('app_login');
        }
        
    }
    //funcion para deshabilitar las imagenes del usuario en el menu principal
    /**
     * @Route("/statusUser/{id}", name="statusUser")
     * 
    */
    public function deshabilitarUser($id)
    {     
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);

        if(!$user){
            throw $this->createNotFoundException('No se permiten datos null el id que ocupo es:'.$id);
        }
        $user->setStatus(false);  
        $em->flush();
        return $this->redirectToRoute('usuarios');
    }
    //funcion para deshabilitar las imagenes del usuario en el menu principal
    /**
     * @Route("/statusUsuario/{id}", name="statusUsuario")
    
    */
    public function habilitarUser($id)
    {     
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);

        if(!$user){
            throw $this->createNotFoundException('No se permiten datos null el id que ocupo es:'.$id);
        }
        $user->setStatus(true);  
        $em->flush();
        return $this->redirectToRoute('usuarios');
    }
    //funcion para banear a los usuarios y que no puedan ocupar el sistema
    /**
     * @Route("/banUser/{id}", name="banUser")    
    */
    public function Banear($id)
    {     
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);

        if(!$user){
            throw $this->createNotFoundException('No se permiten datos null el id que ocupo es:'.$id);
        }
        $user->setBaneado(true);  
        $em->flush();
        return $this->redirectToRoute('usuarios');
    }
    //funcion para banear a los usuarios y que no puedan ocupar el sistema
    /**
     * @Route("/Desbanear/{id}", name="Desbanear")    
    */
    public function Desbanear($id)
    {     
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);

        if(!$user){
            throw $this->createNotFoundException('No se permiten datos null el id que ocupo es:'.$id);
        }
        $user->setBaneado(false);  
        $em->flush();
        return $this->redirectToRoute('usuarios');
    }


    /**
     * @Route("/usuarios", name="usuarios")
     */
    public function usuariosListar(){
        $em = $this->getDoctrine()->getManager();        
        $usuario = $em->getRepository(User::class)->findAll();

        return $this->render('dashboard/usuarios.html.twig',['usuario'=>$usuario]);
        
    }

}
