<?php

namespace Dwl\Lcdd\SpeakerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;

use Dwl\Lcdd\SpeakerBundle\Base\BaseSpeaker as BaseSpeaker;

/**
 * Speaker
 *
 * @ORM\Entity(repositoryClass="Dwl\Lcdd\SpeakerBundle\Repository\SpeakerRepository")
 */
class Speaker extends BaseSpeaker
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_speaker", type="boolean", nullable=true)
     */
    protected $isSpeaker;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\Dwl\Lcdd\SearchBundle\Entity\Question", mappedBy="speaker")
     */
    protected $questions;

    /**
     * @var \Application\Sonata\ClassificationBundle\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\ClassificationBundle\Entity\Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $position;

    /**
     * @var string
     *
     * @ORM\Column(name="career", type="string", length=1000, nullable=true)
     */
    protected $career;

    /**
     * @var string
     *
     * @ORM\Column(name="specialties", type="string", length=1000, nullable=true)
     */
    protected $specialties;

    /**
     * @var string
     *
     * @ORM\Column(name="publications", type="string", length=1000, nullable=true)
     */
    protected $publications;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\MediaBundle\Entity\Media",cascade={"persist"})
     * @ORM\JoinColumn(name="presentation_id", referencedColumnName="id")
     */
    protected $presentation;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set isSpeaker
     *
     * @param boolean $isSpeaker
     * @return Speaker
     */
    public function setIsSpeaker($isSpeaker)
    {
        $this->isSpeaker = $isSpeaker;

        return $this;
    }

    /**
     * Get isSpeaker
     *
     * @return boolean
     */
    public function getIsSpeaker()
    {
        return $this->isSpeaker;
    }

    /**
     * Set career
     *
     * @param string $career
     * @return Speaker
     */
    public function setCareer($career)
    {
        $this->career = $career;

        return $this;
    }

    /**
     * Get career
     *
     * @return string
     */
    public function getCareer()
    {
        return $this->career;
    }

    /**
     * Set specialties
     *
     * @param string $specialties
     * @return Speaker
     */
    public function setSpecialties($specialties)
    {
        $this->specialties = $specialties;

        return $this;
    }

    /**
     * Get specialties
     *
     * @return string
     */
    public function getSpecialties()
    {
        return $this->specialties;
    }

    /**
     * Set publications
     *
     * @param string $publications
     * @return Speaker
     */
    public function setPublications($publications)
    {
        $this->publications = $publications;

        return $this;
    }

    /**
     * Get publications
     *
     * @return string
     */
    public function getPublications()
    {
        return $this->publications;
    }

    /**
     * Add questions
     *
     * @param \Dwl\Lcdd\SearchBundle\Entity\Question $questions
     * @return Speaker
     */
    public function addQuestion(\Dwl\Lcdd\SearchBundle\Entity\Question $questions)
    {
        $this->questions[] = $questions;

        return $this;
    }

    /**
     * Remove questions
     *
     * @param \Dwl\Lcdd\SearchBundle\Entity\Question $questions
     */
    public function removeQuestion(\Dwl\Lcdd\SearchBundle\Entity\Question $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set position
     *
     * @param \Application\Sonata\ClassificationBundle\Entity\Category $position
     * @return Speaker
     */
    public function setPosition(\Application\Sonata\ClassificationBundle\Entity\Category $position = null)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return \Application\Sonata\ClassificationBundle\Entity\Category
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set presentation
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $presentation
     * @return Speaker
     */
    public function setPresentation(\Application\Sonata\MediaBundle\Entity\Media $presentation = null)
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * Get presentation
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getPresentation()
    {
        return $this->presentation;
    }
}
