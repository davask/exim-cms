<?php
// http://symfony.com/doc/current/bundles/SonataAdminBundle/getting_started/creating_an_admin.html
namespace Dwl\GeoBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CountryAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof \Dwl\GeoBundle\Entity\Country
            ? $object->getName()
            : 'Country'; // shown in the breadcrumb on the create view
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('iso', 'text');
        $formMapper->add('iso2', 'text');
        $formMapper->add('name', 'text');
        $formMapper->add('language', null, array(
                        'class'    => 'Dwl\I18nBundle\Entity\Language',
                        'property' => 'name',
                    ));
        $formMapper->add('authoritySupreme', null, array(
                        'class'    => 'Dwl\LexBundle\Entity\AuthoritySupreme',
                        'property' => 'name',
                    ));
        $formMapper->add('continent', null, array(
                        'class'    => 'Dwl\GeoBundle\Entity\Continent',
                        'property' => 'name',
                    ));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('iso');
        $datagridMapper->add('name');
        $datagridMapper->add('language');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('iso');
        $listMapper->addIdentifier('iso2');
        $listMapper->addIdentifier('name');
        $listMapper->add('language');
        $listMapper->addIdentifier('continent');
    }
}
