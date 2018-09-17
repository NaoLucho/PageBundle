<?php

namespace Builder\PageBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CarouselAdmin extends AbstractAdmin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper  
            ->add('carousel', 'text', array(
                'label' => 'Nom du carousel',
                'required' => true
            ))
            ->add('title', 'text', array(
                'label' => 'Titre',
                'required' => true
            ))
            ->add('content', 'text', array(
                'label' => 'Contenu',
                'required' => true
            ))
            ->add('imageFile', FileType::class, array(
                'label' => 'Image',
                'required' => true
            ))
            ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('carousel')
            ->add('title')
            ->add('content');
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title', null, array(
                'label' => "Titre"
            ))
            ->add('content', null, array(
                'label' => "Contenu"
            ));
    }
}