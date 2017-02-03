<?php

namespace Dwl\Lcdd\SpeakerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Doctrine\ORM\EntityRepository;

use Sonata\AdminBundle\Admin\Pool;
use Sonata\CustomerBundle\Form\Type\AddressType;
use Sonata\MediaBundle\Form\Type\MediaType;

use Application\Sonata\CustomerBundle\Entity\Address;
use Application\Sonata\ClassificationBundle\Entity\Category;
use Application\Sonata\MediaBundle\Entity\Media;

class SpeakerType extends AbstractType
{

    const CLASS_CAT_AVATAR = 48;
    const CLASS_CAT_POSITION = 52;
    const CLASS_CAT_PRESENTATION = 61;

    private $adminPool;

    public function __construct(Pool $adminPool, $container) {
        $this->adminPool = $adminPool;
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $mediaAdmin = $this->adminPool->getAdminByClass("Application\\Sonata\\MediaBundle\\Entity\\Media");

        $speaker = $builder->getData();
        $customer = $speaker->getCustomer();
        $user = $customer->getUser();

        $user->setPhone('0620300669');

        $addressCurrent = new Address();
        $adresses = $customer->getAddresses();
        foreach ($adresses as $key => $address) {
            if($address->getCurrent()) {
                $addressCurrent = $address;
                break;
            }
        }

        $addressCurrent->setAddress1('-');
        $addressCurrent->setPostcode('91054');
        $addressCurrent->setCity('-');

        $builder
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
            ->add('email', EmailType::class, array(
                'property_path' => 'customer.user.email',
                'required' => true,
            ))
            ->add(
                $builder->create('address', AddressType::class, array(
                    'by_reference' => false,
                    'mapped' => false,
                    'data' => $addressCurrent,
                ))
                ->add('name', HiddenType::class, array('data'=>'Mon bureau'))
                ->add('type', HiddenType::class, array('data'=>Address::TYPE_CONTACT))
                ->add('firstname', HiddenType::class, array('data'=>$speaker->getFirstname()))
                ->add('lastname', HiddenType::class, array('data'=>$speaker->getLastname()))
                ->add('address1')
                ->add('address2')
                ->add('address3')
                ->add('postcode')
                ->add('city')
                ->add('countryCode', null, array('data'=>'FR'))
                ->add('phone', HiddenType::class, array('data'=>$user->getPhone()))
            )
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
            ->add('position', null, array('placeholder'=>'Choisissez votre profession dans cette liste'))
            ->add(
                $builder->create('avatar', MediaType::class, array(
                    'provider' => 'sonata.media.provider.image',
                    'context'  => 'lcdd',
                    'required' => $speaker->getAvatar() ? false : true,
                    'new_on_update' => false,
                ))
                // ->remove('unlink')
            )
            ->add(
                $builder->create('presentation', MediaType::class, array(
                    'provider' => 'sonata.media.provider.vimeo',
                    'context'  => 'lcdd',
                    'required' => $speaker->getPresentation() ? false : true,
                    'new_on_update' => false
                ))
                // ->remove('unlink')
            )

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
