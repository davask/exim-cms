<?php
namespace Dwl\Lcdd\SearchBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Dwl\Lcdd\SearchBundle\Entity\Article;

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
            'em' => null,
            'required' => 'true',
            'label' => 'label dynamic article management',
            'label_render' => 'label render dynamic article management',
            'sonata_field_description' => 'sonata description: dynamic article management',
            'multipart' => false,
            'display_assets' => false,
            'by_reference' => true,
            'allow_extra_fields' => false,
            'question' => null,
            'formUniqId' => 'dwl_lcdd_searchbundle_question',
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options) {

                $data = $event->getData();
                $form = $event->getForm();

                dump($data);
                $em = $options['em'];

                $artRepo = $em->getRepository('DwlLcddSearchBundle:Article');
                $contextRepo = $em->getRepository('ApplicationSonataClassificationBundle:Context');
                $cLcdd = $contextRepo->findOneByName('lcdd');
                if(!empty($data)) {
                    $articles = $data;
                    $data = array();
                    for ($i=0; $i < count($articles); $i++) {
                        if (!is_object($articles[$i])) {
                            $art = $artRepo->findOneByUniqueId($articles[$i]);
                            if (empty($art)) {
                                $art = new Article();
                                $art->setUniqueId($articles[$i]);
                                $em->persist($art);
                                $em->flush();
                            }
                            // $data[] = (string)$tag->getId();
                            $data[] = $art;
                        }
                    }
                } else {
                    $data = array();
                }

                $form->setData($data);
                $event->setData(array());

            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        $view->vars = array_replace($view->vars, array(
            'em' => $options['em'],
            'display_assets' => $options['display_assets'],
            'data' => $form->getNormData(),
            'formUniqId' => $options['formUniqId'],
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
