<?php
// http://symfony.com/doc/current/bundles/SonataAdminBundle/getting_started/creating_an_admin.html
namespace Dwl\LexBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class DictumAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof \Dwl\LexBundle\Entity\Dictum
            ? $object->getLcddSlug()
            : 'Dictum'; // shown in the breadcrumb on the create view
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('General')
                ->with('Content', array('class' => 'col-md-12'))
                    ->add('num', 'text')
                    ->add('title', 'text')
                    ->add('content', 'textarea')
                    ->add('note', 'textarea', array('required' => false))
                    ->add('linkedIn', null, array('required' => false))
                    // ->add('linkingTo', null, array('required' => false))

                    ->add('eli_id', 'hidden', array(
                        'required' => true,
                        'label' => 'URL ELI (LCDD)',
                        'data' => '-'
                    ))
                    ->add('url_legi', 'hidden', array(
                        'required' => true,
                        'label' => 'URL LEGIFRANCE',
                        'data' => '-'
                    ))
                    ->add('url_source', 'hidden', array(
                        'required' => true,
                        'label' => 'URL GITHUB',
                        'data' => '-'
                    ))
                ->end()
                ->with('Classification', array('class' => 'col-md-9'))
                    ->add('source', 'sonata_type_model', array(
                        'class'    => 'Dwl\LexBundle\Entity\source',
                        'property' => 'name',
                    ))
                    ->add('status', 'sonata_type_model', array(
                        'class'    => 'Dwl\LexBundle\Entity\Status',
                        'property' => 'name',
                    ))
                    ->add('type', 'sonata_type_model', array(
                        'class'    => 'Dwl\LexBundle\Entity\Type',
                        'property' => 'name',
                    ))
                ->end()
                ->with('Misc', array('class' => 'col-md-3'))
                    // ->add('nature', 'text')
                    ->add('code_id', 'text')
                    ->add('text_id', 'text')
                    ->add('authority', 'sonata_type_model', array(
                        'class'    => 'Dwl\LexBundle\Entity\Authority',
                        'property' => 'name',
                    ))
                    ->add('country_text_id', 'text', array(
                        'required' => false,
                    ))
                ->end()
            ->end()
        ;

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title');
        $datagridMapper->add('code_id');
        $datagridMapper->add('text_id');
        $datagridMapper->add('num');
        $datagridMapper->add('source', null, array(), 'entity', array(
            'class'    => 'Dwl\LexBundle\Entity\source',
            'property' => 'name',
        ));
        $datagridMapper->add('type', null, array(), 'entity', array(
            'class'    => 'Dwl\LexBundle\Entity\Type',
            'property' => 'name',
        ));
        $datagridMapper->add('nature');
        $datagridMapper->add('status', null, array(), 'entity', array(
            'class'    => 'Dwl\LexBundle\Entity\Status',
            'property' => 'name',
        ));
        $datagridMapper->add('validity_start');
        $datagridMapper->add('validity_end');

        $datagridMapper->add('linkedIn', null, array(), 'entity', array(
            'class'    => 'Dwl\LexBundle\Entity\Dictum',
            'property' => 'code_id',
        ));
        $datagridMapper->add('linkingTo', null, array(), 'entity', array(
            'class'    => 'Dwl\LexBundle\Entity\Dictum',
            'property' => 'code_id',
        ));
        $datagridMapper->add('authority', null, array(), 'entity', array(
            'class'    => 'Dwl\LexBundle\Entity\Authority',
            'property' => 'name',
        ));

    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            # identifier
            ->add('id')
            ->add('code_id')
            ->add('text_id')
            // ->add('lcdd_id')
            ->add('lcdd_slug')

            # action
            ->add('_action', 'actions', array(
                'actions' => array(
                    'view' => array(),
                    'edit' => array(),
                    'delete' => array(),
                    // 'delete' => array('template' => 'DwlLexBundle:Default:delete.html.twig'),
                )
            ))

            # identifier
            ->add('eli_id')
            // ->add('country_text_id')
            ->add('url_legi')
            ->add('url_source')

            # content
            ->add('num')
            // ->add('title')
            // ->add('language')
            ->add('parents')
            // ->add('content')
            // ->add('note')
            ->add('linkedIn')
            // ->add('linkingTo.text_id')

            # details
            // ->add('source')
            ->add('authority.shortname')
            ->add('authority.authoritySupreme.shortname')
            // ->add('type')
            // ->add('nature')
            // ->add('encoding')
            // ->add('version')
            // ->add('status')

            # date
            // ->add('validity_start')
            // ->add('validity_end')
            // ->add('date_publication')
            // ->add('date_signature')
            // ->add('text_previous_id')
            // ->add('date_create')
            // ->add('date_update')

        ;

    }
}
