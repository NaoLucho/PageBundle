<?php

namespace Builder\PageBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class ContentAdmin extends AbstractAdmin
{
    //to add template for fields
    public function getTemplate($name)
    {
        // dump($name);
        switch ($name) {
            case 'list':
                //var_dump( parent::getTemplate($name));
                return 'BuilderPageBundle::Field\Admin\admincontent_list_template.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }
    
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');

        $formMapper
            ->add('title', 'text', array(
                'label' => 'Titre'
            ))
            // ->add('content', CKEditorType::class, array(
            //     'label' => 'Contenu',
            //     'attr' => array('size' => '10')
            // ))
            ->add('content', CKEditorType::class)
            // ->add('content', CKEditorType::class, array(
            //     'config' => array(
            //     'filebrowserBrowseRoute'           => 'my_route',
            //     'filebrowserBrowseRouteParameters' => array('slug' => 'my-slug'),
            //     'filebrowserBrowseRouteType'       => UrlGeneratorInterface::ABSOLUTE_URL,
            //     ),
            // ))
            ->add('rights', 'sonata_type_model', array(
                'class' => 'Application\Sonata\UserBundle\Entity\Group',
                'property' => 'name',
                'multiple' => true,
                'label' => 'Droits additionnel',
                'btn_add' => false,
                'required' => false
            ));
            if ( $securityContext->isGranted('ROLE_SUPER_ADMIN') || $securityContext->isGranted('ROLE_ADMIN') ) {
                $formMapper
                ->add('type', 'text', array(
                    'label' => 'Type',
                ))
                ->add('class', 'text', array(
                    'label' => 'Class spécifique',
                    'required' => false
                ))
                ->add('locked', null, array(
                    'label' => 'Locked by super-admin',
                    'required' => false
                ));
            }
       ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
       $datagridMapper
            ->add('title')
            ->add('type')
       ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');

        $listMapper
            ->addIdentifier('title', null, array(
                'label' => "Titre"
            ))
            ->add('content', 'string', array(
                'template' => 'BuilderPageBundle:Field\Admin\List:admin_list_content_overflow.html.twig')); //FIND HOW TRUNCATE CONTENT
            if( $securityContext->isGranted('ROLE_SUPER_ADMIN') ) {
                $listMapper
                ->add('type')
                ->add('class')
                ->add('rights', null, array(
                    'associated_property' => 'name'))
                ;
            }
       //$listMapper->get('content')->setData('content');
    }

    public function createQuery($context = 'list') {

        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');

        $query = parent::createQuery($context);
        if ( !$securityContext->isGranted('ROLE_SUPER_ADMIN') ) {

            $query->andWhere(
                $query->expr()->eq($query->getRootAliases()[0] . '.locked', ':locked')
            );
            $query->setParameter('locked', false);
        }


        return $query;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
           ->add('name')
           ->add('type')
       ;
    }
}