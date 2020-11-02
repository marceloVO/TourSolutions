<?php

namespace App\Controller;

use App\Entity\Galeria;
use App\Form\GaleriaType;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class GaleriaController extends AbstractController
{
    /**
     * @Route("/registrar-galeria", name="RegistrarGaleria")
     */
    public function index(Request $request): Response
    {   
        $galeria = new Galeria();
        $form = $this->createForm(GaleriaType::class,$galeria);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $brochureFile = $form['image']->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = iconv('UTF-8', 'ASCII//TRANSLIT', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $galeria->setImage($newFilename);
            }

            $user = $this->getUser();
            $galeria->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($galeria);
            $em->flush();
            return $this->redirectToRoute('dashboard');
        }
        return $this->render('galeria/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    //Metodo para Eliminar la foto y redirecionar a la galeria 
    /**
     * @Route("/delete-galeria/{id}", name="deleteGaleria")
     * @Method({"DELETE"})
    */
    
    public function deleteGaleria($id){

        $em = $this->getDoctrine()->getManager();
        $galeria = $em->getRepository(Galeria::class)->find($id);
        
        if (!$galeria) {
            throw $this->createNotFoundException('No se permiten datos null el id que ocupo es:'.$id);
        } 
        
        $em->remove($galeria);
        $em->flush();               

        return $this->redirectToRoute('MiGaleria');
    }
    
    /**
     * @Route("/mi-galeria", name="MiGaleria")
    */

    public function MiGaleria(){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $galeria = $em->getRepository(Galeria::class)->findBy(['user'=>$user]);

        return $this->render('galeria/migaleria.html.twig',['galeria'=>$galeria]);
        
    }
    //Metodo para editar la foto y redirecionar a la galeria 
    /**
     * @Route("/edit-galeria/{id}", name="editGaleria")
     * Method({"GET","PUT","POST"})
    */
    public function editGaleria(Request $request,$id)
    {
        $galeria = $this->getDoctrine()->getRepository(Galeria::class)->find($id);

        if(!$galeria){
            throw $this->createNotFoundException('No se permiten datos null el id que ocupo es:'.$id);
        }
        
        
        $form = $this->createForm(GaleriaType::class,$galeria);
        
        $form->handleRequest($request);        
        if($form->isSubmitted() && $form->isValid()){
            $brochureFile = $form['image']->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = iconv('UTF-8', 'ASCII//TRANSLIT', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $galeria->setImage($newFilename);
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('MiGaleria');
        }
        return $this->render('galeria/editGaleria.html.twig',['form'=> $form->createView()]);
        
    }


    /**
     * @Route("/perfil/{id}/{nombre}", name="perfilUser")
    */

    public function perfilUser($id, $nombre){

        $em = $this->getDoctrine()->getManager();
        
        
        $galeria = $em->getRepository(Galeria::class)->findBy(['user'=>$id]);

        return $this->render('galeria/perfilUser.html.twig',['galeria'=>$galeria,'nombre'=>$nombre]);
        
    }

}
