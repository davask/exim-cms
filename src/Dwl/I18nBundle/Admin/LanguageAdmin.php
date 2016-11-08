<?php
// http://symfony.com/doc/current/bundles/SonataAdminBundle/getting_started/creating_an_admin.html
namespace Dwl\I18nBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class LanguageAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof \Dwl\I18nBundle\Entity\Language
            ? $object->getName()
            : 'Type'; // shown in the breadcrumb on the create view
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('iso', 'text')
            ->add('name', 'text')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('iso');
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id');
        $listMapper->addIdentifier('iso');
        $listMapper->addIdentifier('name');
    }
}
