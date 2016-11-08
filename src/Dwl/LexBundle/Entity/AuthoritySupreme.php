<?php

namespace Dwl\LexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * AuthoritySupreme
 *
 * @ORM\Table(name="authority_supreme")
 * @ORM\Entity(repositoryClass="Dwl\LexBundle\Repository\AuthoritySupremeRepository")
 * @Gedmo\TranslationEntity(class="Dwl\I18nBundle\Entity\AuthoritySupremeTranslation")
 */
class AuthoritySupreme
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
    * @ORM\OneToMany(targetEntity="\Dwl\LexBundle\Entity\Authority", mappedBy="authoritySupreme")
    */
    private $authorities;

    /**
    * @ORM\OneToOne(targetEntity="\Dwl\GeoBundle\Entity\Country", mappedBy="authoritySupreme")
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
        $this->authorities = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return AuthoritySupreme
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
     * @return AuthoritySupreme
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
     * @return AuthoritySupreme
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
     * @return AuthoritySupreme
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
     * Add authority
     *
     * @param \Dwl\LexBundle\Entity\Authority $authority
     *
     * @return AuthoritySupreme
     */
    public function addAuthority(\Dwl\LexBundle\Entity\Authority $authority)
    {
        $this->authorities[] = $authority;

        return $this;
    }

    /**
     * Remove authority
     *
     * @param \Dwl\LexBundle\Entity\Authority $authority
     */
    public function removeAuthority(\Dwl\LexBundle\Entity\Authority $authority)
    {
        $this->authorities->removeElement($authority);
    }

    /**
     * Get authorities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAuthorities()
    {
        return $this->authorities;
    }

    /**
     * Set country
     *
     * @param \Dwl\GeoBundle\Entity\Country $country
     *
     * @return AuthoritySupreme
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

    /**
     * Set shortname
     *
     * @param string $shortname
     *
     * @return AuthoritySupreme
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
