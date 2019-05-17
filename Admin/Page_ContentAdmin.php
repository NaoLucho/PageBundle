<?php
namespace Builder\PageBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class Page_ContentAdmin extends AbstractAdmin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
        //     ->add('page', 'sonata_type_model', array(
        //     'class' => 'Builder\PageBundle\Entity\Page',
        //     'property' => 'name',
        //     'label' => 'Page',
        //     'multiple' => false,
        // ))
            ->add('content', 'sonata_type_model', array(
            'class' => 'Builder\PageBundle\Entity\Content',
            'property' => 'fullname',
            'label' => 'Contenu',
            'multiple' => false,
            'btn_add' => false,
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
            ->add('page', null, array(), 'entity', array(
            'class' => 'Builder\PageBundle\Entity\Page',
            'choice_label' => 'name',
        ))
            ->add('content', null, array(), 'entity', array(
            'class' => 'Builder\PageBundle\Entity\Content',
            'choice_label' => 'title',
        ));
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('page.name')
            ->add('content.title')
            ->add('position');
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('content.title')
            ->add('page.name')
            ->add('position');
    }
}