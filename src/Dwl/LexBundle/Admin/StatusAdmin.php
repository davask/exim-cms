<?php
// http://symfony.com/doc/current/bundles/SonataAdminBundle/getting_started/creating_an_admin.html
namespace Dwl\LexBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Dwl\LexBundle\Entity\Status;

class StatusAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof \Dwl\LexBundle\Entity\Status
            ? $object->getName()
            : 'Status'; // shown in the breadcrumb on the create view
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text')
            ->add('source', 'sonata_type_model', array(
                'class' => 'Dwl\LexBundle\Entity\Source',
                'property' => 'name',
            ))
            ->add('active', ChoiceType::class, array(
                'choices'  => array(
                    1 => 'Oui',
                    0 => 'Non',
                )
            ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('id');
        $datagridMapper->add('name');
        $datagridMapper->add('source', null, array(), 'entity', array(
            'class'    => 'Dwl\LexBundle\Entity\Source',
            'property' => 'name',
        ));
        $datagridMapper->add('active');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id');
        $listMapper->addIdentifier('name');
        $listMapper->addIdentifier('source');
        $listMapper->add('active');
    }
}
