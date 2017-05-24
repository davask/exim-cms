<?php

/*
 * (c) David Asquiedge <contact@davaskweblimited.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dwl\Lcdd\BaseBundle\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Sonata\CoreBundle\Validator\ErrorElement;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Doctrine\ORM\EntityManager;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Sonata\CoreBundle\Model\Metadata;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\EngineInterface;

use Symfony\Component\HttpFoundation\Response;

/**
 * PageExtension.
 *
 * @author     David Asquiedge <contact@davaskweblimited.com>
 */
class ListQuestionsBlockService extends BaseBlockService
{
    /**
     * @param string             $name
     * @param EngineInterface    $templating
     * @param ContainerInterface $container
     */
    public function __construct($name, EngineInterface $templating, ContainerInterface $container, EntityManager $em)
    {
        parent::__construct($name, $templating);

        $this->container = $container;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'title' => false,
            'template' => 'DwlLcddBaseBundle:Block:block_list_questions.html.twig',
            'list' => 'new',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {

        $blockSettings = $block->getSettings();

        $formSettings = array(
            array('title', 'text', array(
                    'required' => false,
                    'label' => 'form.label_title',
            )),
            array('list', 'choice', array(
                'required' => true,
                'choices' => array(
                    'new' => 'Les plus recentes',
                    'view' => 'Les plus visionnees',
                    'similar' => 'Resultats semblables',
                    'user' => 'Les videos de l\'intervenant',
                ),
                'expanded' => true,
                'multiple' => false,
                'label' => 'type de liste',
            )),

        );

        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => $formSettings,
            'translation_domain' => 'DwlLcddBaseBundle',
        ));

    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        $errorElement
            ->with('settings.list')
                ->assertNotNull(array())
            ->end()
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $this->repo = $this->em->getRepository('DwlLcddSearchBundle:Question');
        $this->repoMedia = $this->em->getRepository('ApplicationSonataMediaBundle:Media');

        $questions = $this->repo->findBy(
            array('qualified' => true),
            array('date_update' => 'DESC'),
            12
        );

        foreach ($questions as $key => $value) {
            $media = $value->getMedia();
            if(!empty($media)){
                $media = $this->repoMedia->findOneById($media->getId());
                $media->getProviderMetadata();
                $questions[$key]->setMedia($media);
            }
        }

        return $this->renderResponse($blockContext->getTemplate(), array(
            'block' => $blockContext->getBlock(),
            'settings' => $blockContext->getSettings(),
            'questions' => $questions,
        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockMetadata($code = null)
    {
        return new Metadata($this->getName(), (!is_null($code) ? $code : $this->getName()), false, 'DwlLcddBaseBundle', array(
            'class' => 'fa fa-th',
        ));
    }

}
