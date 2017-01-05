<?php

namespace Dwl\Lcdd\SearchBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

use Symfony\Bridge\Doctrine\Form\Type\EntityHidden;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author David Asquiedge <contact@davaskweblimited.com>
 */
class QuestionAdmin extends Admin
{
    protected $baseRouteName = 'admin_lcdd_search_question';
    protected $baseRoutePattern = 'lcdd/search/question';

    protected $datagridValues = array(

        // display the first page (default = 1)
        '_page' => 1,

        // reverse order (default = 'ASC')
        '_sort_order' => 'DESC',

        // name of the ordered field (default = the model's id field, if any)
        '_sort_by' => 'qualified',
    );

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('question')
            ->add('speaker')
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
            ->addIdentifier('speaker')
            ->add('slug')
            ->add('qualified', null, array(
                'editable' => true
            ))
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
        $question = $this->getSubject();

        $datagridMapper
            ->add('question')
            ->add('speaker')
            ->add('qualified')
            ->add('unqualifiedQuestions', null, array('field_options' => array('expanded' => false,'multiple' => true)), null, array(
                'class' => 'DwlLcddSearchBundle:Question',
                // 'required'=> false,
                'query_builder' => function(EntityRepository $repository) use ($question) {
                    $qb = $repository->createQueryBuilder('q');
                    return $qb
                        ->where($qb->expr()->eq('q.qualified', '0'))
                    ;
                },
            ))
            ->add('legalTags', null, array('field_options' => array('expanded' => false,'multiple' => true)), null, array(
                'class' => 'ApplicationSonataClassificationBundle:Tag',
                'query_builder' => function(EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('t');
                    return $qb
                        ->where($qb->expr()->eq('t.context', '\'lcdd\''))
                    ;
                },
            ))
            ->add('civilTags', null, array('field_options' => array('expanded' => false,'multiple' => true)), null, array(
                'class' => 'ApplicationSonataClassificationBundle:Tag',
                'query_builder' => function(EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('t');
                    return $qb
                        ->where($qb->expr()->eq('t.context', '\'lcdd\''))
                    ;
                },
            ))
            ->add('categories', null, array('field_options' => array('expanded' => false,'multiple' => true)), null, array(
                'class' => 'ApplicationSonataClassificationBundle:Category',
                'query_builder' => function(EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('c');
                    return $qb
                        ->where($qb->expr()->eq('c.context', '\'lcdd\''))
                        ->andWhere($qb->expr()->neq('c.name', '\'lcdd\''))
                    ;
                },
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $question = $this->getSubject();

        $formMapper
            ->add('question')
            ->add('speaker')
            // ->add('slug')
            ->add('qualified')
        ;

        if($question->getQualified()) {
            $formMapper
                ->add('media', EntityType::class, array(
                    'class' => 'ApplicationSonataMediaBundle:Media',
                    'placeholder' => 'Choisissez une video',
                    'required' => true,
                    'query_builder' => function(EntityRepository $repository) {
                        $qb = $repository->createQueryBuilder('m');
                        return $qb
                            ->where($qb->expr()->eq('m.providerName', '\'sonata.media.provider.vimeo\''))
                        ;
                    },
                ))
                ->add('unqualifiedQuestions', EntityType::class, array(
                    'class' => 'DwlLcddSearchBundle:Question',
                    'multiple' => true,
                    'query_builder' => function(EntityRepository $repository) use ($question) {
                        $qb = $repository->createQueryBuilder('q');
                        return $qb
                            ->where($qb->expr()->eq('q.qualified', '0'))
                            ->andWhere($qb->expr()->neq('q.id', $question->getId()))
                        ;
                    },
                ))
            ;
        } else {
            $formMapper
                ->add('qualifiedQuestion', EntityType::class, array(
                    'class' => 'DwlLcddSearchBundle:Question',
                    'query_builder' => function(EntityRepository $repository) use ($question) {
                        $qb = $repository->createQueryBuilder('q');
                        return $qb
                            ->where($qb->expr()->eq('q.qualified', '1'))
                            ->andWhere($qb->expr()->neq('q.id', $question->getId()))
                        ;
                    },
                ))
            ;
        }
        $formMapper
            ->add('legalTags', EntityType::class, array(
                'class' => 'ApplicationSonataClassificationBundle:Tag',
                'multiple' => true,
                'required' => true,
                'query_builder' => function(EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('t');
                    return $qb
                        ->where($qb->expr()->eq('t.context', '\'lcdd\''))
                    ;
                },
            ))
            ->add('civilTags', EntityType::class, array(
                'class' => 'ApplicationSonataClassificationBundle:Tag',
                'multiple' => true,
                'required' => true,
                'query_builder' => function(EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('t');
                    return $qb
                        ->where($qb->expr()->eq('t.context', '\'lcdd\''))
                    ;
                },
            ))
            ->add('categories', EntityType::class, array(
                'class' => 'ApplicationSonataClassificationBundle:Category',
                'multiple' => true,
                'required' => true,
                'query_builder' => function(EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('c');
                    return $qb
                        ->where($qb->expr()->eq('c.context', '\'lcdd\''))
                        ->andWhere($qb->expr()->neq('c.name', '\'lcdd\''))
                    ;
                },
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
