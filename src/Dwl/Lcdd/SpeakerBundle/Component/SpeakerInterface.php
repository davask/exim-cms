<?php

namespace Dwl\Lcdd\SpeakerBundle\Component;

use FOS\UserBundle\Model\UserInterface;
use Sonata\Component\Customer\CustomerInterface;

interface SpeakerInterface
{
    /**
     * Add questions
     *
     * @param \Dwl\Lcdd\SearchBundle\Entity\Question $questions
     * @return User
     */
    public function addQuestion(\Dwl\Lcdd\SearchBundle\Entity\Question $questions);

    /**
     * Remove questions
     *
     * @param \Dwl\Lcdd\SearchBundle\Entity\Question $questions
     */
    public function removeQuestion(\Dwl\Lcdd\SearchBundle\Entity\Question $questions);

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions();

    /**
     * Set avatar
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $avatar
     * @return User
     */
    public function setAvatar(\Application\Sonata\MediaBundle\Entity\Media $avatar = null);

    /**
     * Get avatar
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getAvatar();

    /**
     * Set position
     *
     * @param \Application\Sonata\ClassificationBundle\Entity\Category $position
     * @return User
     */
    public function setPosition(\Application\Sonata\ClassificationBundle\Entity\Category $position = null);

    /**
     * Get position
     *
     * @return \Application\Sonata\ClassificationBundle\Entity\Category
     */
    public function getPosition();

    /**
     * Set career
     *
     * @param string $career
     * @return User
     */
    public function setCareer($career);

    /**
     * Get career
     *
     * @return string
     */
    public function getCareer();

    /**
     * Set specialties
     *
     * @param string $specialties
     * @return User
     */
    public function setSpecialties($specialties);

    /**
     * Get specialties
     *
     * @return string
     */
    public function getSpecialties();

    /**
     * Set publications
     *
     * @param string $publications
     * @return User
     */
    public function setPublications($publications);

    /**
     * Get publications
     *
     * @return string
     */
    public function getPublications();

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return User
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
     * @return User
     */
    public function setLongitude($longitude);

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude();


}
