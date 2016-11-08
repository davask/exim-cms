<?php

namespace Dwl\LexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * Status
 *
 * @ORM\Table(name="status")
 * @ORM\Entity(repositoryClass="Dwl\LexBundle\Repository\StatusRepository")
 * @Gedmo\TranslationEntity(class="Dwl\I18nBundle\Entity\StatusTranslation")
 */
class Status
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, separator="_")
     * @ORM\Column(name="canonical_name", type="string", length=255)
     */
    private $canonical_name;

    /**
    * @ORM\ManyToOne(targetEntity="\Dwl\LexBundle\Entity\Source", inversedBy="statuses")
    */
    private $source;

    /**
    * @ORM\ManyToOne(targetEntity="\Dwl\I18nBundle\Entity\Language", inversedBy="statuses")
    private $language;
    */

    /**
    * @ORM\OneToMany(targetEntity="\Dwl\LexBundle\Entity\Dictum", mappedBy="status")
    */
    private $dicta;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var \DateTime $date_create
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $date_create;

    /**
     * @var \DateTime $date_update
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $date_update;

    public function __toString() {
        return $this->name;
    }

    /**
     * Constructor
     */
    public function __construct()
    {

        $this->dicta = new \Doctrine\Common\Collections\ArrayCollection();
        $this->active = 1;

    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Status
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set canonicalName
     *
     * @param string $canonicalName
     *
     * @return Status
     */
    public function setCanonicalName($canonicalName)
    {
        $this->canonical_name = $canonicalName;

        return $this;
    }

    /**
     * Get canonicalName
     *
     * @return string
     */
    public function getCanonicalName()
    {
        return $this->canonical_name;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Status
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return Status
     */
    public function setDateCreate($dateCreate)
    {
        $this->date_create = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->date_create;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     *
     * @return Status
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->date_update = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->date_update;
    }

    /**
     * Set source
     *
     * @param \Dwl\LexBundle\Entity\Source $source
     *
     * @return Status
     */
    public function setSource(\Dwl\LexBundle\Entity\Source $source = null)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return \Dwl\LexBundle\Entity\Source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Add dictum
     *
     * @param \Dwl\LexBundle\Entity\Dictum $dictum
     *
     * @return Status
     */
    public function addDictum(\Dwl\LexBundle\Entity\Dictum $dictum)
    {
        $this->dicta[] = $dictum;

        return $this;
    }

    /**
     * Remove dictum
     *
     * @param \Dwl\LexBundle\Entity\Dictum $dictum
     */
    public function removeDictum(\Dwl\LexBundle\Entity\Dictum $dictum)
    {
        $this->dicta->removeElement($dictum);
    }

    /**
     * Get dicta
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDicta()
    {
        return $this->dicta;
    }
}
