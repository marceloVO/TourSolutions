<?php

namespace App\Controller;

use App\Entity\Galeria;
use App\Form\GaleriaType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
                // this is needed to safely include the file name as part of the URL
                
                $safeFilename = iconv('UTF-8', 'ASCII//TRANSLIT', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
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
    //con la ruta podemos ver el enrutamiento que en este caso se dirige a galeria/{id}
    /**
     * @Route("/galeria/{id}", name="verGaleria")
    */
    
    public function verGaleria($id){
        $em = $this->getDoctrine()->getManager();
        $galeria = $em->getRepository(Galeria::class)->find($id);

        return $this->render('galeria/verGaleria.html.twig',['galeria'=>$galeria]);
    }

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

    /**
     * @Route("/edit-galeria/{id}", name="editGaleria")
     * Method({"GET","PUT"})
    */
    public function editGaleria(Request $request,$id)
    {
        
        $em = $this->getDoctrine()->getManager();
        $galeria = $em->getRepository(Galeria::class)->find($id);
        $form = $this->createForm(GaleriaType::class,$galeria);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $brochureFile = $form['image']->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                
                $safeFilename = iconv('UTF-8', 'ASCII//TRANSLIT', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $galeria->setImage($newFilename);
            }

            $user = $this->getUser();
            $galeria->setUser($user);
            $em = $this->getDoctrine()->getManager()->flush();            
            
            return $this->redirectToRoute('editGaleria');
        }
        return $this->render('galeria/migaleria.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
