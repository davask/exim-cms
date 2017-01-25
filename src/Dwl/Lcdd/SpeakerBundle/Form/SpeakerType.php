<?php

namespace Dwl\Lcdd\SpeakerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityRepository;

class SpeakerType extends AbstractType
{

    const CLASS_CAT_AVATAR = 48;
    const CLASS_CAT_POSITION = 52;
    const CLASS_CAT_PRESENTATION = 61;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $speaker = $builder->getData();
        $customer = $speaker->getCustomer();
        $user = $customer->getUser();

        // $categoryAdmin = $this->getConfigurationPool()->getAdminByClass("\\Application\\Sonata\\ClassificationBundle\\Entity\\Category");

        $builder
            // ->add('isSpeaker')
            ->add('biography', CKEditorType::class, array(
                'property_path' => 'customer.user.biography',
                'required' => true,
            ))
            ->add('career', CKEditorType::class, array(
                'required' => true,
            ))
            ->add('specialties', CKEditorType::class, array(
                'required' => true,
            ))
            ->add('publications', CKEditorType::class, array(
                'required' => true,
            ))
            ->add('phone', null, array(
                'property_path' => 'customer.user.phone',
                'required' => true,
            ))
            ->add('email', null, array(
                'property_path' => 'customer.user.email',
                'required' => true,
            ))
            ->add('adresses', EntityType::class, array(
                'class' => 'ApplicationSonataCustomerBundle:Address',
                'property_path' => 'customer.addresses',
            ))
            ->add('gplusUid', null, array(
                'property_path' => 'customer.user.gplusUid',
            ))
            ->add('twitterUid', null, array(
                'property_path' => 'customer.user.twitterUid',
            ))
            ->add('facebookUid', null, array(
                'property_path' => 'customer.user.facebookUid',
            ))
            ->add('youtubeUid', null, array(
                'property_path' => 'customer.user.youtubeUid',
            ))
            ->add('linkedinUid', null, array(
                'property_path' => 'customer.user.linkedinUid',
            ))
            ->add('latitude')
            ->add('longitude')
            ->add('position')
            // ->add('position', 'sonata_type_model_list', array(
            //     'model_manager' => $categoryAdmin->getModelManager(),
            // ))
            ->add('avatar', 'sonata_media_type', array(
                'provider' => 'sonata.media.provider.image',
                'context'  => 'lcdd',
                'required' => true,
                'empty_on_new' => false,
                'new_on_update' => false,
            ))
            ->add('presentation', 'sonata_media_type', array(
                'provider' => 'sonata.media.provider.vimeo',
                'context'  => 'lcdd',
                'required' => true,
                'empty_on_new' => false,
                'new_on_update' => false,
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dwl\Lcdd\SpeakerBundle\Entity\Speaker'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dwl_lcdd_speakerbundle_speaker';
    }
}