<?php

namespace Dwl\Lcdd\SpeakerBundle\Component;

use FOS\UserBundle\Model\UserInterface;
use Sonata\Component\Customer\CustomerInterface;

interface SpeakerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFullname();

    /**
     * set textToPng
     */
    public function textToPng($text = null, $R = 49, $G = 75, $B = 72);

    /**
     * Get protectedPhone
     */
    public function setProtectedPhone();

    /**
     * Get protectedEmail
     */
    public function getProtectedPhone();

    /**
     * Get protectedEmail
     */
    public function setProtectedEmail();

    /**
     * Get protectedEmail
     */
    public function getProtectedEmail();

    /**
     * Set avatar
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $avatar
     * @return Speaker
     */
    public function setAvatar(\Application\Sonata\MediaBundle\Entity\Media $avatar = null);

    /**
     * Get avatar
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getAvatar();

    /**
     * Set customer
     *
     * @param \Application\Sonata\CustomerBundle\Entity\Customer $customer
     * @return Speaker
     */
    public function setCustomer(\Application\Sonata\CustomerBundle\Entity\Customer $customer = null);

    /**
     * Get customer
     *
     * @return \Application\Sonata\CustomerBundle\Entity\Customer
     */
    public function getCustomer();

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Speaker
     */
    public function setLatitude($latitude);

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude();

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Speaker
     */
    public function setLongitude($longitude);

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude();

}
