<?php

namespace Dwl\LexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * Authority
 *
 * @ORM\Table(name="authority")
 * @ORM\Entity(repositoryClass="Dwl\LexBundle\Repository\AuthorityRepository")
 * @Gedmo\TranslationEntity(class="Dwl\I18nBundle\Entity\AuthorityTranslation")
 */
class Authority
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
     * @ORM\Column(name="shortname", type="string", length=255)
     */
    private $shortname;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, separator="_")
     * @ORM\Column(name="canonical_name", type="string", length=255)
     */
    private $canonical_name;

    /**
    * @ORM\ManyToOne(targetEntity="\Dwl\LexBundle\Entity\AuthoritySupreme", inversedBy="authorities")
    */
    private $authoritySupreme;

    /**
    * @ORM\OneToMany(targetEntity="\Dwl\LexBundle\Entity\Dictum", mappedBy="authority")
    */
    private $dicta;

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

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;

    public function __toString() {

        return $this->name;

    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dicta = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Authority
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
     * @return Authority
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
     * @return Authority
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
     * @return Authority
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
     * Set authoritySupreme
     *
     * @param \Dwl\LexBundle\Entity\AuthoritySupreme $authoritySupreme
     *
     * @return Authority
     */
    public function setAuthoritySupreme(\Dwl\LexBundle\Entity\AuthoritySupreme $authoritySupreme = null)
    {
        $this->authoritySupreme = $authoritySupreme;

        return $this;
    }

    /**
     * Get authoritySupreme
     *
     * @return \Dwl\LexBundle\Entity\AuthoritySupreme
     */
    public function getAuthoritySupreme()
    {
        return $this->authoritySupreme;
    }

    /**
     * Add dictum
     *
     * @param \Dwl\LexBundle\Entity\Dictum $dictum
     *
     * @return Authority
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
     * Set shortname
     *
     * @param string $shortname
     *
     * @return Authority
     */
    public function setShortname($shortname)
    {
        $this->shortname = $shortname;

        return $this;
    }

    /**
     * Get shortname
     *
     * @return string
     */
    public function getShortname()
    {
        return $this->shortname;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

}
