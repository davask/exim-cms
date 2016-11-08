<?php
// http://symfony.com/doc/current/bundles/SonataAdminBundle/getting_started/creating_an_admin.html
namespace Dwl\GeoBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ContinentAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof \Dwl\GeoBundle\Entity\Continent
            ? $object->getName()
            : 'Continent'; // shown in the breadcrumb on the create view
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('iso', 'text');
        $formMapper->add('name', 'text');
        // $formMapper->add('language', null, array('required' => false));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('iso');
        $datagridMapper->add('name');
        // $datagridMapper->add('language');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('id');
        $listMapper->addIdentifier('iso');
        $listMapper->addIdentifier('name');
        // $listMapper->add('language');
    }
}
