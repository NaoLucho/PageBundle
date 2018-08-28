<?php
namespace Builder\PageBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class Menu_PageAdmin extends AbstractAdmin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
        //     ->add('menu', 'sonata_type_model', array(
        //     'class' => 'Builder\PageBundle\Entity\Menu',
        //     'property' => 'name',
        //     'label' => 'Menu',
        //     'multiple' => false,
        // ))
            -> add('page', 'sonata_type_model', array(
            'class' => 'Builder\PageBundle\Entity\Page',
            'property' => 'name',
            'label' => 'Page',
            'multiple' => false,
            'btn_add' => false
        ))
            ->add('position', 'text', array(
            'label' => 'Position',
            'required' => true
        ));
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('menu', null, array(), 'entity', array(
            'class' => 'Builder\PageBundle\Entity\Menu',
            'choice_label' => 'name',
        ))
            ->add('page', null, array(), 'entity', array(
            'class' => 'Builder\PageBundle\Entity\Page',
            'choice_label' => 'name',
        ));
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('menu.name')
            ->add('page.name')
            ->add('position');
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('menu.name')
            ->add('page.name')
            ->add('position');
    }
}