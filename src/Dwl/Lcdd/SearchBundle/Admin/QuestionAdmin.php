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
            // ->add('questionCategories', null, array('associated_tostring' => 'getCategory'))
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
            // ->add('questionCategories.category', null, array('field_options' => array('expanded' => false, 'multiple' => true)))
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
            ->add('legalTags', 'sonata_type_model_autocomplete', array(
                'property' => 'name',
                'multiple' => 'true',
                'required' => false,
            ))
            ->add('civilTags', 'sonata_type_model_autocomplete', array(
                'property' => 'name',
                'multiple' => 'true',
                'required' => false,
            ))
            ->add('questionCategories', 'sonata_type_model_autocomplete', array(
                'property' => 'name',
                'multiple' => 'true',
                'required' => false,
            ))
        ;
    }

    // /**
    //  * {@inheritdoc}
    //  */
    // protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    // {
    //     if (!$childAdmin && !in_array($action, array('edit'))) {
    //         return;
    //     }

    //     $admin = $this->isChild() ? $this->getParent() : $this;

    //     $id = $admin->getRequest()->get('id');

    //     $menu->addChild(
    //         $this->trans('sidemenu.link_edit_question'),
    //         array('uri' => $admin->generateUrl('edit', array('id' => $id)))
    //     );

    //     if ($this->hasSubject() && $this->getSubject()->getId() !== null) {
    //         $menu->addChild('sidemenu.link_view_question',
    //             array('uri' => $admin->getRouteGenerator()->generate('dwl_lcdd_search_questions_view', array('permalink' => $this->permalinkGenerator->generate($this->getSubject()))))
    //         );
    //     }
    // }

    /**
     * {@inheritdoc}
     */
    public function getNewInstance()
    {
        $question = parent::getNewInstance();

        return $question;
    }
}
