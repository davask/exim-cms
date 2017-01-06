<?php

namespace Dwl\Lcdd\SpeakerBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

use Symfony\Bridge\Doctrine\Form\Type\EntityHidden;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author David Asquiedge <contact@davaskweblimited.com>
 */
class SpeakerAdmin extends Admin
{

    const CLASS_CAT_AVATAR = 48;
    const CLASS_CAT_POSITION = 52;

    protected $baseRouteName = 'admin_lcdd_speaker';
    protected $baseRoutePattern = 'lcdd/speaker';

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
    public function postUpdate($user)
    {
        $doctrine = $this->getConfigurationPool()->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $repo = $doctrine->getRepository($this->getClass());
        $owner = $repo->findLcdd();
        if(empty($owner) || $user->getId() == $owner->getId()) {
            $owner = $repo->findOneById(1);
        }
        $toFlush = false;
        foreach ($user->getQuestions() as $i => $question) {
            dump($user->getGroups());
            // if($user->getGroups() && $question->getQualified()) {
            //     $toFlush = true;
            //     $question->setSpeaker($owner);
            //     $em->persist($question);
            // }
        }
        if($toFlush) {
            $em->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('customer', 'sonata_type_model_list')
            ->add('isSpeaker')
            ->add('position')
            ->add('customer.updatedAt')
            ->add('customer.createdAt')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('customer')
            ->addIdentifier('customer.user')
            ->addIdentifier('customer.user.groups')
            ->add('isSpeaker', null, array(
                'editable' => true
            ))
            ->add('customer.updatedAt')
            ->add('customer.createdAt')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {

        $datagridMapper
            ->add('customer')
            ->add('isSpeaker')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $speaker = $this->getSubject();

        // define group zoning
        $formMapper
            ->tab('Intervenant')
                ->with('Utilisateur', array('class' => 'col-md-12'))->end()
            ->end()
            ->tab('Informations')
                ->with('A propos', array('class' => 'col-md-4'))->end()
                ->with('Specialitees', array('class' => 'col-md-4'))->end()
                ->with('Publications', array('class' => 'col-md-4'))->end()
            ->end()
            ->tab('Divers')
                ->with('Configuration', array('class' => 'col-md-6'))->end()
                ->with('Speaker', array('class' => 'col-md-6'))->end()
                ->with('Videos', array('class' => 'col-md-12'))->end()
            ->end()
        ;

        $formMapper
            ->tab('Intervenant')
                ->with('Utilisateur')
                    ->add('customer', 'sonata_type_model_list')
                ->end()
            ->end()
            ->tab('Informations')
                ->with('A propos')
                    ->add('career', 'textarea', array(
                        'required' => true,
                    ))
                ->end()
                ->with('Specialitees')

                ->end()
                ->with('Publications')

                ->end()
            ->end()
            ->tab('Divers')
                ->with('Configuration')
                    ->add('isSpeaker', CheckboxType::class, array(
                        'required' => true,
                    ))
                ->end()
                ->with('Speaker')
                    ->add('position', null, array(
                        'required' => false,
                        'query_builder' => function(EntityRepository $repository) {
                            $qb = $repository->createQueryBuilder('c');
                            return $qb
                                ->where($qb->expr()->eq('c.context', '\'lcdd\''))
                                ->andWhere($qb->expr()->eq('c.parent', self::CLASS_CAT_POSITION))
                            ;
                        },
                    ))
                    ->add('avatar', null, array(
                        'required' => true,
                        'placeholder' => 'Choisissez votre avatar',
                        'query_builder' => function(EntityRepository $repository) {
                            $qb = $repository->createQueryBuilder('m');
                            return $qb
                                ->where($qb->expr()->eq('m.context', '\'lcdd\''))
                                ->andWhere($qb->expr()->eq('m.category', self::CLASS_CAT_AVATAR))
                            ;
                        },
                    ))
                ->end()
                ->with('Videos')
                    ->add('questions', null, array(
                        'disabled' => true,
                        'multiple' => true,
                    ))
                ->end()
            ->end()
        ;

    }

    /**
     * {@inheritdoc}
    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, array('edit'))) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;

        $id = $admin->getRequest()->get('id');

        $menu->addChild(
            $this->trans('customer.sidemenu.link_customer_edit', array(), 'DwlLcddSpeakerBundle'),
            $admin->generateMenuUrl('edit', array('id' => $id))
        );

    }
     */

}
