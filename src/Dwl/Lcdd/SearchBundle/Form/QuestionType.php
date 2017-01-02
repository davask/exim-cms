<?php

namespace Dwl\Lcdd\SearchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class QuestionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', TextareaType::class, array(
                'attr' => array(
                     "class" => "dwl-search-block-question-input form-control",
                     "placeholder" => "_f._q.your_question",
                ),
                'label' => '_f._q.suggest_new',
                'label_attr' => array(
                     "class" => "h3",
                ),
                'data' => '{[{ userQuestion }]}',
                'translation_domain' => 'DwlLcddSearchBundle',
            ))
            // ->add('slug')
            // ->add('qualified')
            // ->add('date_create')
            // ->add('date_update')
            // ->add('qualifiedQuestion')
            // ->add('legalTags')
            // ->add('civilTags')
            // ->add('categories')
            // ->add('media')
            // ->add('author')
            ->add('submit', SubmitType::class, array(
                'label' => '_f._q.submit',
                'attr' => array(
                    'class' => 'btn btn-question',
                ),
                'translation_domain' => 'DwlLcddSearchBundle',
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dwl\Lcdd\SearchBundle\Entity\Question'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dwl_lcdd_searchbundle_question';
    }
}
