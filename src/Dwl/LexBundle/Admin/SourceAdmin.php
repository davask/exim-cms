<?php
// http://symfony.com/doc/current/bundles/SonataAdminBundle/getting_started/creating_an_admin.html
namespace Dwl\LexBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Dwl\LexBundle\Entity\Source;

class SourceAdmin extends AbstractAdmin
{

    public function toString($object)
    {
        return $object instanceof \Dwl\LexBundle\Entity\Source
            ? $object->getName()
            : 'Source'; // shown in the breadcrumb on the create view
    }

    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            ->add('name', 'text')
            ->add('country', 'sonata_type_model', array(
                'class'    => 'Dwl\GeoBundle\Entity\Country',
                'property' => 'name',
            ))
        ->end()
        ;

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('id');
        $datagridMapper->add('name');
        $datagridMapper->add('country', null, array(), 'entity', array(
            'class'    => 'Dwl\GeoBundle\Entity\Country',
            'property' => 'name',
        ));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id');
        $listMapper->addIdentifier('name');
        $listMapper->addIdentifier('country');
    }
}
