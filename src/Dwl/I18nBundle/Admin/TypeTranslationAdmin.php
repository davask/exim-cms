<?php
// http://symfony.com/doc/current/bundles/SonataAdminBundle/getting_started/creating_an_admin.html
namespace Dwl\I18nBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class TypeTranslationAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('locale', 'text');
        $formMapper->add('content', 'text');
        $formMapper->add('field', 'text');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('locale');
        $datagridMapper->add('field');
        $datagridMapper->add('content');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('id');
        $listMapper->addIdentifier('locale');
        $listMapper->addIdentifier('field');
        $listMapper->addIdentifier('content');
    }
}
