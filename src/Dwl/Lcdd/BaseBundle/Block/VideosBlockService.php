<?php

/*
 * (c) David Asquiedge <contact@davaskweblimited.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dwl\Lcdd\BaseBundle\Block;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Model\ManagerInterface;
use Sonata\CoreBundle\Model\Metadata;
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
 * @author     David Asquiedge <contact@davaskweblimited.com>
 */
class VideosBlockService extends BaseBlockService
{
    /**
     * @param string             $name
     * @param EngineInterface    $templating
     * @param ContainerInterface $container
     * @param ManagerInterface   $mediaManager
     */
    public function __construct($name, EngineInterface $templating, ContainerInterface $container)
    {
        parent::__construct($name, $templating);

        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            // 'display' => 'inline',
            // 'ngcontroller' => 'formCtrl',
            // 'title' => false,
            // 'sub_title' => false,
            // 'block_class' => 'col-sm-offset-1 col-sm-10 col-md-offset-3 col-md-6',
            // 'inline_class' => 'header-search nav navbar-nav navbar-right',
            // 'bottom_class' => 'row bottom-search clearfix',
            // 'img_class' => '',
            // 'context' => false,
            // 'format' => false,
            // 'template' => 'DwlLcddBaseBundle:Block:block_search.html.twig',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {

        $blockSettings = $block->getSettings();

        $formSettings = array();
        //     array('display', 'choice', array(
        //         'required' => true,
        //         'choices' => array(
        //             'inline' => '_f._q.inline',
        //             'block' => '_f._q.block',
        //             'bottom' => '_f._q.bottom',
        //         ),
        //         'expanded' => true,
        //         'multiple' => false,
        //         'label' => 'form.label_display',
        //     )),
        // );

        // if($blockSettings['display'] != 'inline') {

        //     if($blockSettings['display'] == 'bottom') {

        //         $formSettings[] = array('bottom_class', 'text', array(
        //             'required' => false,
        //             'label' => 'form.label_bottom_class',
        //         ));

        //     } else if($blockSettings['display'] == 'block') {

        //         $formSettings[] = array('title', 'text', array(
        //             'required' => false,
        //             'label' => 'form.label_title',
        //         ));
        //         $formSettings[] = array('sub_title', 'text', array(
        //             'required' => false,
        //             'label' => 'form.label_sub_title',
        //         ));
        //         $formSettings[] = array('block_class', 'text', array(
        //             'required' => false,
        //             'label' => 'form.label_block_class',
        //         ));

        //     }

        //     $formSettings[] = array('img_class', 'text', array(
        //         'required' => false,
        //         'label' => 'form.label_img_class',
        //     ));

        //     if (!$block->getSetting('mediaId') instanceof MediaInterface) {
        //         $this->load($block);
        //     }

        //     if (empty($block->getSetting('mediaId'))) {
        //         $block->setSetting('mediaId', null);
        //     }

        //     $formatChoices = $this->getFormatChoices($block->getSetting('mediaId'));

        //     $formSettings[] = array('format', 'choice', array(
        //         'required' => count($formatChoices) > 0,
        //         'disabled' => count($formatChoices) == 0,
        //         'choices' => $formatChoices,
        //         'label' => 'form.label_format',
        //     ));

        // } else {

            // $formSettings[] = array('inline_class', 'text', array(
            //     'required' => false,
            //     'label' => 'form.label_inline_class',
            // ));

        // }

        // $formMapper->add('settings', 'sonata_type_immutable_array', array(
        //     'keys' => $formSettings,
        //     'translation_domain' => 'DwlLcddBaseBundle',
        // ));

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
            //     'translation_domain' => 'DwlLcddBaseBundle',
            // ));
        // });
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // make sure we have a valid format
        // $media = $blockContext->getBlock()->getSetting('mediaId');
        // if ($media instanceof MediaInterface) {
        //     $choices = $this->getFormatChoices($media);

        //     if (!array_key_exists($blockContext->getSetting('format'), $choices)) {
        //         $blockContext->setSetting('format', key($choices));
        //     }
        // }

        // $questionForm = $this->container->get( 'dwl.lcdd.block.search.form.question' );
        // $question = $this->container->get( 'dwl.lcdd.block.search.form.entity.question' );
        // $questionForm->setData( $question );
        // return $this->renderResponse($blockContext->getTemplate(), array(
        //     'media' => $blockContext->getSetting('mediaId'),
        //     'block' => $blockContext->getBlock(),
        //     'settings' => $blockContext->getSettings(),
        //     'questionForm' => $questionForm->createView(),
        // ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function load(BlockInterface $block)
    {
        // $media = $block->getSetting('mediaId', null);

        // if (is_int($media)) {
        //     $media = $this->mediaManager->findOneBy(array('id' => $media));
        // }

        // $block->setSetting('mediaId', $media);
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist(BlockInterface $block)
    {
        // $block->setSetting('mediaId', is_object($block->getSetting('mediaId')) ? $block->getSetting('mediaId')->getId() : null);
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate(BlockInterface $block)
    {
        // $block->setSetting('mediaId', is_object($block->getSetting('mediaId')) ? $block->getSetting('mediaId')->getId() : null);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockMetadata($code = null)
    {
        // return new Metadata($this->getName(), (!is_null($code) ? $code : $this->getName()), false, 'DwlLcddBaseBundle', array(
        //     'class' => 'fa fa-search',
        // ));
    }

    /**
     * @param MediaInterface|null $media
     *
     * @return array
     */
    protected function getFormatChoices(MediaInterface $media = null)
    {
        // $formatChoices = array();

        // if (!$media instanceof MediaInterface) {
        //     return $formatChoices;
        // }

        // $formats = $this->getMediaPool()->getFormatNamesByContext($media->getContext());

        // foreach ($formats as $code => $format) {
        //     $formatChoices[$code] = $code;
        // }

        // return $formatChoices;
    }

}
