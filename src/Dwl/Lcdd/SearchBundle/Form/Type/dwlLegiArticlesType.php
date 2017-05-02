<?php
namespace Dwl\Lcdd\SearchBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class dwlLegiArticlesType extends AbstractType
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
        return 'dwllegiarticles';
    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults(array(
            'required' => 'true',
            'label' => 'label dynamic article management',
            'label_render' => 'label render dynamic article management',
            'sonata_field_description' => 'sonata description: dynamic article management',
            'multipart' => false,
            'display_assets' => false,
            'data' => array(),
            'question' => null,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('legalTags', dwlLegiTagsType::class, array(
                'mapped' => false,
                'data' => !empty($options['question']) ? $options['question']->getLegalTags() : array(),
            ))
            ->add('civilTags', dwlLegiTagsType::class, array(
                'mapped' => false,
                'tag_type' => 'civil',
                'data' => !empty($options['question']) ? $options['question']->getCivilTags() : array(),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        $view->vars = array_replace($view->vars, array(
            'display_assets' => $options['display_assets'],
            'data' => !empty($options['question']) ? $options['question']->getLegiIds() : array(),
        ));

    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['multipart'] = false;
        $view->vars['unique_block_prefix'] = '_dwl_lcdd_searchbundle_question_legiIds';
        $view->vars['block_prefixes'] = [];
        $view->vars['block_prefixes'][] = $this->getBlockPrefix();
        $view->vars['cache_key'] = $view->vars['unique_block_prefix'].'_'.$this->getName();
    }

}
