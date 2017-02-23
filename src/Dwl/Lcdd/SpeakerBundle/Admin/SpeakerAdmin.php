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

use Ivory\CKEditorBundle\Form\Type\CKEditorType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\MediaBundle\Form\Type\MediaType;

/**
 * @author David Asquiedge <contact@davaskweblimited.com>
 */
class SpeakerAdmin extends Admin
{

    const CLASS_CAT_AVATAR = 48;
    const CLASS_CAT_POSITION = 52;
    const CLASS_CAT_PRESENTATION = 61;

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
    // public function postUpdate($speaker)
    // {
    //     $doctrine = $this->getConfigurationPool()->getContainer()->get('doctrine');
    //     $em = $doctrine->getEntityManager();
    //     $repo = $doctrine->getRepository($this->getClass());
    //     $owner = $repo->findLcdd();
    //     if(empty($owner) || $speaker->getId() == $owner->getId()) {
    //         $owner = $repo->findOneById(1);
    //     }
    //     $toFlush = false;
    //     foreach ($speaker->getQuestions() as $i => $question) {
    //         // dump($speaker->getGroups());
    //         // if($speaker->getGroups() && $question->getQualified()) {
    //         //     $toFlush = true;
    //         //     $question->setSpeaker($owner);
    //         //     $em->persist($question);
    //         // }
    //     }
    //     if($toFlush) {
    //         $em->flush();
    //     }
    // }

    /**
     * {@inheritdoc}
     */
    public function prePersist($speaker)
    {
        $this->preUpdate($speaker);
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($speaker)
    {
        $questions = $this->getForm()->get('questions')->getData();
        foreach ($questions as $question) {
            $question->setSpeaker($speaker);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('customer')
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
            ->addIdentifier('fullname')
            ->addIdentifier('customer')
            ->addIdentifier('customer.user')
            ->addIdentifier('customer.user.groups')
            ->addIdentifier('position')
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
            ->add('customer.user')
            ->add('customer.user.groups')
            ->add('position')
            ->add('isSpeaker')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $speaker = $this->getSubject();

        $categoryAdmin = $this->configurationPool->getAdminByClass("Application\\Sonata\\ClassificationBundle\\Entity\\Category");
        $mediaAdmin = $this->configurationPool->getAdminByClass("Application\\Sonata\\MediaBundle\\Entity\\Media");

        // define group zoning
        $formMapper
            ->tab('Intervenant')
                ->with('Utilisateur', array('class' => 'col-md-12'))->end()
            ->end()
            ->tab('Informations')
                ->with('A-propos', array('class' => 'col-md-6'))->end()
                ->with('Parcours', array('class' => 'col-md-6'))->end()
                ->with('Specialitees', array('class' => 'col-md-6'))->end()
                ->with('Publications', array('class' => 'col-md-6'))->end()
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
                    ->add('customer', ModelListType::class)
                ->end()
            ->end()
            ->tab('Informations')
                ->with('A-propos')
                    ->add('customer.user.biography', CKEditorType::class, array(
                        'required' => true,
                    ))
                ->end()
                ->with('Parcours')
                    ->add('career', CKEditorType::class, array(
                        'required' => true,
                    ))
                ->end()
                ->with('Specialitees')
                    ->add('specialties', CKEditorType::class, array(
                        'required' => true,
                    ))
                ->end()
                ->with('Publications')
                    ->add('publications', CKEditorType::class, array(
                        'required' => true,
                    ))
                ->end()
                ->with('Video de presentation')
                    ->add('presentation', ModelListType::class, array(
                        'model_manager' => $mediaAdmin->getModelManager(),
                    ))
                ->end()
            ->end()
            ->tab('Divers')
                ->with('Configuration')
                    ->add('isSpeaker', CheckboxType::class, array(
                        'required' => true,
                    ))
                ->end()
                ->with('Speaker')
                    ->add('position', ModelListType::class, array(
                        'model_manager' => $categoryAdmin->getModelManager(),
                    ))
                    ->add('avatar', ModelListType::class, array(
                        'model_manager' => $mediaAdmin->getModelManager(),
                    ))
                ->end()
                ->with('Videos')
                    ->add('questions', null, array(
                        'multiple' => true,
                        'disabled' => true,
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
