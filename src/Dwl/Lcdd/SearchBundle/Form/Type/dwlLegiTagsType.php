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

use Application\Sonata\ClassificationBundle\Entity\Tag;

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
            'em' => null,
            'label' => 'legi Tags',
            'label_render' => 'legi Tags!',
            'sonata_field_description' => 'Dynamic Legi tag management',
            'multipart' => false,
            'tag_type' => 'legal',
            'multiple' => true,
            'by_reference' => true,
            'allow_extra_fields' => false,
            'display_assets' => false,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options) {
                // check http://symfony.com/doc/current/form/dynamic_form_modification.html
                $data = $event->getData();
                $form = $event->getForm();
                $em = $options['em'];

                $tagRepo = $em->getRepository('ApplicationSonataClassificationBundle:Tag');
                $contextRepo = $em->getRepository('ApplicationSonataClassificationBundle:Context');
                $cLcdd = $contextRepo->findOneByName('lcdd');
                if(!empty($data)) {
                    $tags = $data;
                    $data = array();
                    for ($i=0; $i < count($tags); $i++) {
                        if (!is_object($tags[$i])) {
                            $slug = Tag::slugify($tags[$i]);
                            $tag = $tagRepo->findOneBySlug($slug);
                            if (empty($tag)) {
                                $tag = new Tag();
                                $tag->setName($tags[$i]);
                                $tag->setEnabled(true);
                                $tag->setContext($cLcdd);
                                $em->persist($tag);
                                $em->flush();
                            }
                            // $data[] = (string)$tag->getId();
                            $data[] = $tag;
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
            'display_assets' => $options['display_assets'],
            'tag_type' => $options['tag_type'],
            'data' => $form->getNormData(),
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
