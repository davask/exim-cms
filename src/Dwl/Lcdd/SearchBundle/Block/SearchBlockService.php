<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dwl\Lcdd\SearchBundle\Block;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Model\ManagerInterface;
use Sonata\CoreBundle\Model\Metadata;
use Sonata\MediaBundle\Admin\BaseMediaAdmin;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\Pool;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Templating\EngineInterface;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * PageExtension.
 *
 * @author     Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class SearchBlockService extends BaseBlockService
{
    /**
     * @var BaseMediaAdmin
     */
    protected $mediaAdmin;

    /**
     * @var ManagerInterface
     */
    protected $mediaManager;

    /**
     * @param string             $name
     * @param EngineInterface    $templating
     * @param ContainerInterface $container
     * @param ManagerInterface   $mediaManager
     */
    public function __construct($name, EngineInterface $templating, ContainerInterface $container, ManagerInterface $mediaManager)
    {
        parent::__construct($name, $templating);

        $this->mediaManager = $mediaManager;
        $this->container = $container;
    }

    /**
     * @return Pool
     */
    public function getMediaPool()
    {
        return $this->getMediaAdmin()->getPool();
    }

    /**
     * @return BaseMediaAdmin
     */
    public function getMediaAdmin()
    {
        if (!$this->mediaAdmin) {
            $this->mediaAdmin = $this->container->get('sonata.media.admin.media');
        }

        return $this->mediaAdmin;
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'media' => false,
            'display' => 'inline',
            'title' => false,
            'sub_title' => false,
            'block_class' => 'col-sm-offset-1 col-sm-10 col-md-offset-3 col-md-6',
            'inline_class' => 'header-search nav navbar-nav navbar-right',
            'bottom_class' => 'row bottom-search clearfix',
            'img_class' => '',
            'context' => false,
            'mediaId' => null,
            'format' => false,
            'template' => 'DwlLcddSearchBundle:Block:block_search.html.twig',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {

        if (!$block->getSetting('mediaId') instanceof MediaInterface) {
            $this->load($block);
        }

        if (empty($block->getSetting('mediaId'))) {
            $block->setSetting('mediaId', null);
        }

        dump($block->getSetting('mediaId'));
        $formatChoices = $this->getFormatChoices($block->getSetting('mediaId'));

        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('display', 'choice', array(
                    'required' => true,
                    'choices' => array(
                        'inline' => '_f._q.inline',
                        'block' => '_f._q.block',
                        'bottom' => '_f._q.bottom',
                    ),
                    'expanded' => true,
                    'multiple' => false,
                    'label' => 'form.label_display',
                )),
                array('title', 'text', array(
                    'required' => false,
                    'label' => 'form.label_title',
                )),
                array('sub_title', 'text', array(
                    'required' => false,
                    'label' => 'form.label_sub_title',
                )),
                array('inline_class', 'text', array(
                    'required' => false,
                    'label' => 'form.label_inline_class',
                )),
                array('block_class', 'text', array(
                    'required' => false,
                    'label' => 'form.label_block_class',
                )),
                array('bottom_class', 'text', array(
                    'required' => false,
                    'label' => 'form.label_bottom_class',
                )),
                array('img_class', 'text', array(
                    'required' => false,
                    'label' => 'form.label_img_class',
                )),
                array($this->getMediaBuilder($formMapper), null, array()),
                array('format', 'choice', array(
                    'required' => count($formatChoices) > 0,
                    'disabled' => count($formatChoices) == 0,
                    'choices' => $formatChoices,
                    'label' => 'form.label_format',
                )),
            ),
            'translation_domain' => 'DwlLcddSearchBundle',
        ));

        // TODO : add event listener on block settings
        // $formMapper->getFormBuilder()->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($formMapper) {
        //     $form = $event->getForm();
        //     dump($form);
            // $form->add('settings', 'sonata_type_immutable_array', array(
            //     'keys' => array(
            //         array('inline_class', 'text', array(
            //             'required' => false,
            //             'label' => 'form.label_inline_class',
            //         )),
            //         array('block_class', 'text', array(
            //             'required' => false,
            //             'label' => 'form.label_block_class',
            //         )),
            //     ),
            //     'translation_domain' => 'DwlLcddSearchBundle',
            // ));
        // });
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // make sure we have a valid format
        $media = $blockContext->getBlock()->getSetting('mediaId');
        if ($media instanceof MediaInterface) {
            $choices = $this->getFormatChoices($media);

            if (!array_key_exists($blockContext->getSetting('format'), $choices)) {
                $blockContext->setSetting('format', key($choices));
            }
        }

        $questionForm = $this->container->get( 'dwl.lcdd.block.search.form.question' );
        $question = $this->container->get( 'dwl.lcdd.block.search.form.entity.question' );
        $questionForm->setData( $question );
        return $this->renderResponse($blockContext->getTemplate(), array(
            'media' => $blockContext->getSetting('mediaId'),
            'block' => $blockContext->getBlock(),
            'settings' => $blockContext->getSettings(),
            'questionForm' => $questionForm->createView(),
        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function load(BlockInterface $block)
    {
        $media = $block->getSetting('mediaId', null);

        if (is_int($media)) {
            $media = $this->mediaManager->findOneBy(array('id' => $media));
        }

        $block->setSetting('mediaId', $media);
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist(BlockInterface $block)
    {
        $block->setSetting('mediaId', is_object($block->getSetting('mediaId')) ? $block->getSetting('mediaId')->getId() : null);
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate(BlockInterface $block)
    {
        $block->setSetting('mediaId', is_object($block->getSetting('mediaId')) ? $block->getSetting('mediaId')->getId() : null);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockMetadata($code = null)
    {
        return new Metadata($this->getName(), (!is_null($code) ? $code : $this->getName()), false, 'DwlLcddSearchBundle', array(
            'class' => 'fa fa-search',
        ));
    }

    /**
     * @param MediaInterface|null $media
     *
     * @return array
     */
    protected function getFormatChoices(MediaInterface $media = null)
    {
        $formatChoices = array();

        if (!$media instanceof MediaInterface) {
            return $formatChoices;
        }

        $formats = $this->getMediaPool()->getFormatNamesByContext($media->getContext());

        foreach ($formats as $code => $format) {
            $formatChoices[$code] = $code;
        }

        return $formatChoices;
    }

    /**
     * @param FormMapper $formMapper
     *
     * @return FormBuilder
     */
    protected function getMediaBuilder(FormMapper $formMapper)
    {
        // simulate an association ...
        $fieldDescription = $this->getMediaAdmin()->getModelManager()->getNewFieldDescriptionInstance($this->mediaAdmin->getClass(), 'media', array(
            'translation_domain' => 'DwlLcddSearchBundle',
        ));
        $fieldDescription->setAssociationAdmin($this->getMediaAdmin());
        $fieldDescription->setAdmin($formMapper->getAdmin());
        $fieldDescription->setOption('edit', 'list');
        $fieldDescription->setAssociationMapping(array(
            'fieldName' => 'media',
            'type' => ClassMetadataInfo::MANY_TO_ONE,
        ));

        return $formMapper->create('mediaId', 'sonata_type_model_list', array(
            'sonata_field_description' => $fieldDescription,
            'class' => $this->getMediaAdmin()->getClass(),
            'model_manager' => $this->getMediaAdmin()->getModelManager(),
            'label' => 'form.label_media',
        ));
    }
}
