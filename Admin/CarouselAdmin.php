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

    //to add template for fields
    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                //var_dump( parent::getTemplate($name));
                return 'BuilderPageBundle::Field\Admin\admin_form_template.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $entity = $formMapper->getAdmin()->getSubject();
        $imageFileExists = false;
        if($entity->getImageName() != null)
        {
            $imageFileExists = true;
        }
        $formMapper  
            ->add('carousel', 'text', array(
                'label' => 'Nom du carousel',
                'required' => true
            ))
            ->add('title', 'text', array(
                'label' => 'Titre',
                'required' => false
            ))
            ->add('content', 'text', array(
                'label' => 'Contenu',
                'required' => false
            ))
            ->add('imageFile', FileType::class, array(
                'label' => 'Image',
                'required' => !$imageFileExists
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
            ->addIdentifier('id')
            ->add('carousel')
            ->addIdentifier('title', null, array(
                'label' => "Titre"
            ))
            ->add('content', null, array(
                'label' => "Contenu"
            ));
    }
}