<?php
namespace Dwl\Lcdd\SearchBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class dwlLegiTagsType extends AbstractType
{

    /**
     * @author  David Asquiedge <davask.42@gmail.com>
     * @return  string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'dwllegitags';
    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults(array(
            'label' => 'legi Tags',
            'label_render' => 'legi Tags!',
            'sonata_field_description' => 'Dynamic Legi tag management',
            'multipart' => false,
            'tag_type' => 'legal',
            'by_reference' => false,
            'data' => array(),
            'allow_extra_fields' => true,
            'display_assets' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        $view->vars = array_replace($view->vars, array(
            'display_assets' => $options['display_assets'],
            'tag_type' => $options['tag_type'],
            'data' => !empty($options['data']) ? $options['data'] : array(),
        ));

    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['multipart'] = false;
        $view->vars['unique_block_prefix'] = '_dwl_lcdd_searchbundle_question_'.$options['tag_type'].'Tags';
        $view->vars['block_prefixes'] = [];
        $view->vars['block_prefixes'][] = $this->getBlockPrefix();
        $view->vars['cache_key'] = $view->vars['unique_block_prefix'].'_'.$this->getName();
    }

}
