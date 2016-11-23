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
                     "placeholder" => "Votre question",
                ),
                'label' => 'Suggérer une nouvelle question',
                'label_attr' => array(
                     "class" => "h3",
                ),
            ))
            ->add('_format', HiddenType::class, array(
                'data' => 'html',
                'mapped' => false,
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Soumettre',
                'attr' => array(
                    'class' => 'btn btn-question',
                ),
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
