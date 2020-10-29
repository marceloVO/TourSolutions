<?php

namespace App\Controller;
use App\Entity\Galeria;
use App\Entity\User;
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
                4
            );    
            return $this->render('dashboard/index.html.twig', [
                'pagination' => $pagination,
                
                
            ]);
        }else{
            return $this->redirectToRoute('app_login');
        }
        
    }

}
