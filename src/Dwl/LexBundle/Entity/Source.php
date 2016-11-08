<?php

namespace Dwl\LexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * Source
 *
 * @ORM\Table(name="source")
 * @ORM\Entity(repositoryClass="Dwl\LexBundle\Repository\SourceRepository")
 * @Gedmo\TranslationEntity(class="Dwl\I18nBundle\Entity\SourceTranslation")
 */
class Source
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
    * @ORM\OneToMany(targetEntity="\Dwl\LexBundle\Entity\Dictum", mappedBy="source")
    */
    private $dicta;

    /**
    * @ORM\OneToMany(targetEntity="\Dwl\LexBundle\Entity\Status", mappedBy="source")
    */
    private $statuses;

    /**
    * @ORM\ManyToOne(targetEntity="\Dwl\GeoBundle\Entity\Country", inversedBy="sources")
    */
    private $country;

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
        $this->statuses = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Source
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
     * @return Source
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
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return Source
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
     * @return Source
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
     * Add dictum
     *
     * @param \Dwl\LexBundle\Entity\Dictum $dictum
     *
     * @return Source
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

    /**
     * Add status
     *
     * @param \Dwl\LexBundle\Entity\Status $status
     *
     * @return Source
     */
    public function addStatus(\Dwl\LexBundle\Entity\Status $status)
    {
        $this->statuses[] = $status;

        return $this;
    }

    /**
     * Remove status
     *
     * @param \Dwl\LexBundle\Entity\Status $status
     */
    public function removeStatus(\Dwl\LexBundle\Entity\Status $status)
    {
        $this->statuses->removeElement($status);
    }

    /**
     * Get statuses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * Set country
     *
     * @param \Dwl\GeoBundle\Entity\Country $country
     *
     * @return Source
     */
    public function setCountry(\Dwl\GeoBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \Dwl\GeoBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }
}
