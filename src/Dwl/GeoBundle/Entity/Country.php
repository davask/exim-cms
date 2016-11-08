<?php

namespace Dwl\GeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="Dwl\GeoBundle\Repository\CountryRepository")
 * @Gedmo\TranslationEntity(class="Dwl\I18nBundle\Entity\CountryTranslation")
 */
class Country
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
     * @ORM\Column(name="iso", type="string")
     */
    private $iso;

    /**
     * @var string
     *
     * @ORM\Column(name="iso2", type="string")
     */
    private $iso2;

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
     * @ORM\ManyToOne(targetEntity="\Dwl\GeoBundle\Entity\Continent", inversedBy="countries")
     */
    private $continent;

    /**
     * @ORM\ManyToOne(targetEntity="\Dwl\I18nBundle\Entity\Language", inversedBy="countries")
     */
    private $language;

    /**
    * @ORM\OneToMany(targetEntity="\Dwl\LexBundle\Entity\Source", mappedBy="country")
    */
    private $sources;

    /**
    * @ORM\OneToOne(targetEntity="\Dwl\LexBundle\Entity\AuthoritySupreme", inversedBy="country")
    */
    private $authoritySupreme;

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
        $this->sources = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set iso
     *
     * @param string $iso
     *
     * @return Country
     */
    public function setIso($iso)
    {
        $this->iso = $iso;

        return $this;
    }

    /**
     * Get iso
     *
     * @return string
     */
    public function getIso()
    {
        return $this->iso;
    }

    /**
     * Set iso2
     *
     * @param string $iso2
     *
     * @return Country
     */
    public function setIso2($iso2)
    {
        $this->iso2 = $iso2;

        return $this;
    }

    /**
     * Get iso2
     *
     * @return string
     */
    public function getIso2()
    {
        return $this->iso2;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Country
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
     * @return Country
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
     * Set continent
     *
     * @param \Dwl\GeoBundle\Entity\Continent $continent
     *
     * @return Country
     */
    public function setContinent(\Dwl\GeoBundle\Entity\Continent $continent = null)
    {
        $this->continent = $continent;

        return $this;
    }

    /**
     * Get continent
     *
     * @return \Dwl\GeoBundle\Entity\Continent
     */
    public function getContinent()
    {
        return $this->continent;
    }

    /**
     * Add source
     *
     * @param \Dwl\LexBundle\Entity\Source $source
     *
     * @return Country
     */
    public function addSource(\Dwl\LexBundle\Entity\Source $source)
    {
        $this->sources[] = $source;

        return $this;
    }

    /**
     * Remove source
     *
     * @param \Dwl\LexBundle\Entity\Source $source
     */
    public function removeSource(\Dwl\LexBundle\Entity\Source $source)
    {
        $this->sources->removeElement($source);
    }

    /**
     * Get sources
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * Set authoritySupreme
     *
     * @param \Dwl\LexBundle\Entity\AuthoritySupreme $authoritySupreme
     *
     * @return Country
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
     * Set language
     *
     * @param \Dwl\I18nBundle\Entity\Language $language
     *
     * @return Country
     */
    public function setLanguage(\Dwl\I18nBundle\Entity\Language $language = null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return \Dwl\I18nBundle\Entity\Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

}
