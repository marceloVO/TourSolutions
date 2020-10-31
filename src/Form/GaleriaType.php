<?php

namespace App\Form;

use App\Entity\Galeria;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GaleriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,['label'=>'Titulo'])
            ->add('description', TextareaType::class,['label'=>'Descripción'])
            ->add('image', FileType::class, array('label'=>'Archivo para subir','data_class' => null))// para cambiarle el label es necesario ocupar 
            ->add('Guardar',SubmitType::class)
            //->add('user') comentamos el usuario para que no lo tenga que registrar
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Galeria::class,
        ]);
    }
}
