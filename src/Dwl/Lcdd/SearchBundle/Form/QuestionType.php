<?php

namespace Dwl\Lcdd\SearchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sonata\MediaBundle\Form\Type\MediaType;

class QuestionType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $question = $builder->getData();

        $hasMedia = $question->getMedia() ? false : true;

        $mediaField = $builder->create('media', MediaType::class, array(
            'provider' => 'sonata.media.provider.vimeo',
            'context'  => 'lcdd',
            'required' => $hasMedia,
            'new_on_update' => false
        ));

        if($hasMedia) {
            $mediaField->remove('unlink');
        }


        $builder
            ->add('question', TextType::class, array(
                'attr' => array(
                     "class" => "dwl-search-block-question-input form-control",
                     "placeholder" => "_f._q.your_question",
                ),
                'label' => '_f._q.suggest_new',
                'label_attr' => array(
                     "class" => "h3",
                ),
                // 'data' => '{[{ userQuestion }]}',
                'translation_domain' => 'DwlLcddSearchBundle',
            ))
            // ->add('slug')
            ->add('qualified')
            // ->add('date_create')
            // ->add('date_update')
            // ->add('qualifiedQuestion')
            ->add('unqualifiedQuestions', null, array(
                'expanded'=>false,
                'multiple'=>true,
                'by_reference' => false,
                'placeholder'=>'Choisissez une ou plusieurs questions qualifiees par cette question',
                'query_builder' => function ($repo) use ($question) {
                  return $repo->createQueryBuilder('q')
                    ->where('q.qualified = 0')
                    ->andWhere('q.qualifiedQuestion IS NULL')
                    ->orWhere('q.qualifiedQuestion = :qId')
                    ->setParameter('qId', $question->getId());
                }
            ))
            ->add('legalTags', null, array(
                'expanded'=>false,
                'multiple'=>true,
                'by_reference' => false,
                'placeholder'=>'Choisissez un ou plusieurs tags legaux par cette question',
                'query_builder' => function ($repo) {
                  return $repo->createQueryBuilder('t')
                    ->where('t.context = \'lcdd\'')
                  ;
                }
            ))
            ->add('civilTags', null, array(
                'expanded'=>false,
                'multiple'=>true,
                'by_reference' => false,
                'placeholder'=>'Choisissez un ou plusieurs tags civils par cette question',
                'query_builder' => function ($repo) {
                  return $repo->createQueryBuilder('t')
                    ->where('t.context = \'lcdd\'')
                  ;
                }
            ))
            ->add('categories', null, array(
                'expanded'=>false,
                'multiple'=>true,
                'required' => count($question->getCategories()) > 0 ? false : true,
                'placeholder'=>'Choisissez une categorie pour cette question',
                'query_builder' => function ($repo) {
                  return $repo->createQueryBuilder('c')
                    ->leftJoin('c.parent', 'p')
                    ->where('c.context = \'lcdd\'')
                    ->andWhere('p.slug = \'codes\'');
                }
            ))
            ->add($mediaField)
            ->add('speaker', TextType::class, array(
                'disabled' => true,
                'required' => false,
            ))
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
