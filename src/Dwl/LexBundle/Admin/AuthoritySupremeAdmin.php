<?php
// http://symfony.com/doc/current/bundles/SonataAdminBundle/getting_started/creating_an_admin.html
namespace Dwl\LexBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Dwl\LexBundle\Entity\Type;

class AuthoritySupremeAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof \Dwl\LexBundle\Entity\AuthoritySupreme
            ? $object->getName()
            : 'AuthoritySupreme'; // shown in the breadcrumb on the create view
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text')
            ->add('shortname', 'text')
            ->add('country', 'sonata_type_model', array(
                'class'    => 'Dwl\GeoBundle\Entity\Country',
                'property' => 'name',
            ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('id');
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id');
        $listMapper->addIdentifier('name');
        $listMapper->addIdentifier('shortname');
    }
}
