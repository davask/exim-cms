<?php

namespace Dwl\Lcdd\SearchBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

/**
 * @author David Asquiedge <contact@davaskweblimited.com>
 */
class QuestionAdmin extends Admin
{
    protected $baseRouteName = 'admin_lcdd_search_question';
    protected $baseRoutePattern = 'lcdd/search/question';

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('question')
            ->add('qualified')
            ->add('date_create')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('question')
            ->add('qualified')
            ->add('date_create')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('question')
            ->add('qualified')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('question')
            ->add('qualified')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewInstance()
    {
        $question = parent::getNewInstance();

        return $question;
    }
}
