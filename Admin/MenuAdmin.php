<?php

namespace Builder\PageBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class MenuAdmin extends AbstractAdmin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', array(
                'label' => 'Menu'
            ))
            ->add('menuPages', 'sonata_type_collection', array(
                    'by_reference' => false,
                    'required' => true,
                    'type_options' => array(
                        'delete' => true
                    )
                ), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'id'
                ))
       ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
       $datagridMapper
            ->add('name')
       ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', null, array(
                'label' => "Menu"
            ))
            ->add('menuPages', null, array(
                'associated_property' => 'page.name')
        )
       ;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
           ->add('name')
       ;
    }
}