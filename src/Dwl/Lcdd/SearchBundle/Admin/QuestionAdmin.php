<?php

namespace Dwl\Lcdd\SearchBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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
            ->add('legalTags')
            ->add('civilTags')
            ->add('categories')
            ->add('unqualifiedQuestions')
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
            ->add('legalTags')
            ->add('civilTags')
            ->add('categories')
            ->add('unqualifiedQuestions')
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
            ->add('unqualifiedQuestions')
            ->add('legalTags', null, array('field_options' => array('expanded' => false, 'multiple' => true)))
            ->add('civilTags', null, array('field_options' => array('expanded' => false, 'multiple' => true)))
            ->add('categories', null, array('field_options' => array('expanded' => false, 'multiple' => true)))
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
            ->add('qualifiedQuestion', EntityType::class, array(
                'class' => 'DwlLcddSearchBundle:Question',
                // 'choice_label' => 'question',
                // 'multiple' => true,
                // 'expanded' => true,
            ))
            ->add('unqualifiedQuestions', EntityType::class, array(
                'class' => 'DwlLcddSearchBundle:Question',
                // 'choice_label' => 'question',
                'multiple' => true,
                // 'expanded' => true,
            ))
            ->add('legalTags', EntityType::class, array(
                'class' => 'ApplicationSonataClassificationBundle:Tag',
                // 'choice_label' => 'name',
                'multiple' => true,
                // 'expanded' => true,
            ))
            ->add('civilTags', EntityType::class, array(
                'class' => 'ApplicationSonataClassificationBundle:Tag',
                // 'choice_label' => 'name',
                'multiple' => true,
                // 'expanded' => true,
            ))
            ->add('categories', EntityType::class, array(
                'class' => 'ApplicationSonataClassificationBundle:Category',
                // 'choice_label' => 'name',
                'multiple' => true,
                // 'expanded' => true,
            ))
            ->add('media', EntityType::class, array(
                'class' => 'ApplicationSonataMediaBundle:Media',
                'choice_label' => 'name',
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

}
