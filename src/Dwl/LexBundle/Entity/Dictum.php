<?php

namespace Dwl\LexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * Dictum
 *
 * @ORM\Table(name="dictum")
 * @ORM\Entity(repositoryClass="Dwl\LexBundle\Repository\DictumRepository")
 * @Gedmo\TranslationEntity(class="Dwl\I18nBundle\Entity\DictumTranslation")
 */
class Dictum
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
     * @Gedmo\Slug(fields={"code_id","text_id"}, separator="_")
     * @ORM\Column(name="lcdd_slug", type="string", length=255)
     */
    private $lcdd_slug;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=1000, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="lcdd_id", type="string", length=255, nullable=true)
     */
    private $lcdd_id;

    /**
     * @var string
     *
     * @ORM\Column(name="eli_id", type="string", length=255, nullable=false)
     */
    private $eli_id;

    /**
     * @var string
     *
     * @ORM\Column(name="code_id", type="string", length=255)
     */
    private $code_id;

    /**
     * @var string
     *
     * @ORM\Column(name="text_id", type="string", length=255)
     */
    private $text_id;

    /**
     * @var string
     *
     * @ORM\Column(name="country_text_id", type="string", length=255, nullable=true)
     */
    private $country_text_id;

    /**
    * @ORM\ManyToOne(targetEntity="\Dwl\I18nBundle\Entity\Language", inversedBy="dicta")
    private $language;
    */

    /**
    * @ORM\ManyToOne(targetEntity="\Dwl\LexBundle\Entity\Source", inversedBy="dicta")
    */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="num", type="string", nullable=true)
     */
    private $num;

    /**
    * @ORM\ManyToOne(targetEntity="\Dwl\LexBundle\Entity\Authority", inversedBy="dicta")
    */
    private $authority;

    /**
     * @var string
     *
     * @ORM\Column(name="parents", type="string", nullable=true)
     */
    private $parents;

    /**
    * @ORM\ManyToOne(targetEntity="\Dwl\LexBundle\Entity\Type", inversedBy="dicta")
    */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="nature", type="string", length=255, nullable=true)
     */
    private $nature;

    /**
     * @var string
     *
     * @ORM\Column(name="encoding", type="string", length=255, nullable=true)
     */
    private $encoding;

    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=255, nullable=true)
     */
    private $version;

    /**
    * @ORM\ManyToOne(targetEntity="\Dwl\LexBundle\Entity\Status", inversedBy="dicta")
    */
    private $status;

    /**
     * @var \DateTime $validity_start
     *
     * @ORM\Column(name="validity_start", type="datetime", nullable=true)
     */
    private $validity_start;

    /**
     * @var \DateTime $validity_end
     *
     * @ORM\Column(name="validity_end", type="datetime", nullable=true)
     */
    private $validity_end;

    /**
     * @var \DateTime $date_publication
     *
     * @ORM\Column(name="date_publication", type="datetime", nullable=true)
     */
    private $date_publication;

    /**
     * @var \DateTime $date_signature
     *
     * @ORM\Column(name="date_signature", type="datetime", nullable=true)
     */
    private $date_signature;

    /**
     * @var text
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="content", type="text", length=20000, nullable=true)
     */
    private $content;

    /**
     * @var text
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="note", type="text", length=10000, nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToMany(targetEntity="\Dwl\LexBundle\Entity\Dictum", inversedBy="linkingTo")
     */
    private $linkedIn;

    /**
     * @ORM\ManyToMany(targetEntity="\Dwl\LexBundle\Entity\Dictum", mappedBy="linkedIn")
     */
    private $linkingTo;

    /**
     * @var string
     *
     * @ORM\Column(name="text_previous_id", type="string", length=255, nullable=true)
     */
    private $text_previous_id;

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
     * @var string
     *
     * @ORM\Column(name="url_legi", type="string", length=255, nullable=false)
     */
    private $url_legi;

    /**
     * @var string
     *
     * @ORM\Column(name="url_source", type="string", length=255, nullable=false)
     */
    private $url_source;

    public function __toString() {

        return $this->text_id;

    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->linkedIn = new \Doctrine\Common\Collections\ArrayCollection();
        $this->linkingTo = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set lcddSlug
     *
     * @param string $lcddSlug
     *
     * @return Dictum
     */
    public function setLcddSlug($lcddSlug)
    {
        $this->lcdd_slug = $lcddSlug;

        return $this;
    }

    /**
     * Get lcddSlug
     *
     * @return string
     */
    public function getLcddSlug()
    {
        return $this->lcdd_slug;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Dictum
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set lcddId
     *
     * @param string $lcddId
     *
     * @return Dictum
     */
    public function setLcddId($lcddId)
    {
        $this->lcdd_id = $lcddId;

        return $this;
    }

    /**
     * Get lcddId
     *
     * @return string
     */
    public function getLcddId()
    {
        return $this->lcdd_id;
    }

    /**
     * Set codeId
     *
     * @param string $codeId
     *
     * @return Dictum
     */
    public function setCodeId($codeId)
    {
        $this->code_id = $codeId;

        return $this;
    }

    /**
     * Get codeId
     *
     * @return string
     */
    public function getCodeId()
    {
        return $this->code_id;
    }

    /**
     * Set textId
     *
     * @param string $textId
     *
     * @return Dictum
     */
    public function setTextId($textId)
    {
        $this->text_id = $textId;

        return $this;
    }

    /**
     * Get textId
     *
     * @return string
     */
    public function getTextId()
    {
        return $this->text_id;
    }

    /**
     * Set num
     *
     * @param string $num
     *
     * @return Dictum
     */
    public function setNum($num)
    {
        $this->num = $num;

        return $this;
    }

    /**
     * Get num
     *
     * @return string
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Set parents
     *
     * @param string $parents
     *
     * @return Dictum
     */
    public function setParents($parents)
    {
        $this->parents = $parents;

        return $this;
    }

    /**
     * Get parents
     *
     * @return string
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * Set nature
     *
     * @param string $nature
     *
     * @return Dictum
     */
    public function setNature($nature)
    {
        $this->nature = $nature;

        return $this;
    }

    /**
     * Get nature
     *
     * @return string
     */
    public function getNature()
    {
        return $this->nature;
    }

    /**
     * Set encoding
     *
     * @param string $encoding
     *
     * @return Dictum
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;

        return $this;
    }

    /**
     * Get encoding
     *
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Set version
     *
     * @param string $version
     *
     * @return Dictum
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set validityStart
     *
     * @param \DateTime $validityStart
     *
     * @return Dictum
     */
    public function setValidityStart($validityStart)
    {
        $this->validity_start = $validityStart;

        return $this;
    }

    /**
     * Get validityStart
     *
     * @return \DateTime
     */
    public function getValidityStart()
    {
        return $this->validity_start;
    }

    /**
     * Set validityEnd
     *
     * @param \DateTime $validityEnd
     *
     * @return Dictum
     */
    public function setValidityEnd($validityEnd)
    {
        $this->validity_end = $validityEnd;

        return $this;
    }

    /**
     * Get validityEnd
     *
     * @return \DateTime
     */
    public function getValidityEnd()
    {
        return $this->validity_end;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Dictum
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Dictum
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set textPreviousId
     *
     * @param string $textPreviousId
     *
     * @return Dictum
     */
    public function setTextPreviousId($textPreviousId)
    {
        $this->text_previous_id = $textPreviousId;

        return $this;
    }

    /**
     * Get textPreviousId
     *
     * @return string
     */
    public function getTextPreviousId()
    {
        return $this->text_previous_id;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return Dictum
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
     * @return Dictum
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
     * @return Dictum
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
     * Set type
     *
     * @param \Dwl\LexBundle\Entity\Type $type
     *
     * @return Dictum
     */
    public function setType(\Dwl\LexBundle\Entity\Type $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Dwl\LexBundle\Entity\Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set status
     *
     * @param \Dwl\LexBundle\Entity\Status $status
     *
     * @return Dictum
     */
    public function setStatus(\Dwl\LexBundle\Entity\Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \Dwl\LexBundle\Entity\Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add linkedIn
     *
     * @param \Dwl\LexBundle\Entity\Dictum $linkedIn
     *
     * @return Dictum
     */
    public function addLinkedIn(\Dwl\LexBundle\Entity\Dictum $linkedIn)
    {
        $this->linkedIn[] = $linkedIn;

        return $this;
    }

    /**
     * Remove linkedIn
     *
     * @param \Dwl\LexBundle\Entity\Dictum $linkedIn
     */
    public function removeLinkedIn(\Dwl\LexBundle\Entity\Dictum $linkedIn)
    {
        $this->linkedIn->removeElement($linkedIn);
    }

    /**
     * Get linkedIn
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLinkedIn()
    {
        return $this->linkedIn;
    }

    /**
     * Add linkingTo
     *
     * @param \Dwl\LexBundle\Entity\Dictum $linkingTo
     *
     * @return Dictum
     */
    public function addLinkingTo(\Dwl\LexBundle\Entity\Dictum $linkingTo)
    {
        $this->linkingTo[] = $linkingTo;

        return $this;
    }

    /**
     * Remove linkingTo
     *
     * @param \Dwl\LexBundle\Entity\Dictum $linkingTo
     */
    public function removeLinkingTo(\Dwl\LexBundle\Entity\Dictum $linkingTo)
    {
        $this->linkingTo->removeElement($linkingTo);
    }

    /**
     * Get linkingTo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLinkingTo()
    {
        return $this->linkingTo;
    }

    /**
     * Get UrlLegi
     *
     * @return string
     */
    public function getUrlLegi(){

        return $this->url_legi;

    }

    /**
     * Set UrlLegi
     *
     * @return Dictum
     */
    public function setUrlLegi($urlLegi){
        $url = "//www.legifrance.gouv.fr/affichCodeArticle.do?cidTexte=" . $this->code_id . "&idArticle=" . $this->text_id;
        // return "<a src=\".$url.\" target=\"_blank\">".$this->title."</a>";

        $this->url_legi = $url;

        return $this;
    }

    /**
     * Get UrlSource
     *
     * @return string
     */
    public function getUrlSource(){

        return $this->url_source;

    }

    /**
     * Set UrlSource
     *
     * @return Dictum
     */
    public function setUrlSource($urlSource){
        $url = "//github.com/davask/codes-juridiques-francais/tree/source/lcdd-xml/CEV_" . $this->code_id . "_" . $this->text_id . ".xml";
        // return "<a src=\".$url.\" target=\"_blank\">".$this->title."</a>";

        $this->url_source = $url;

        return $this;
    }

    /**
     * Get eliId
     *
     * @return string
     */
    public function getEliId()
    {
        return $this->eli_id;
    }

    /**
     * Set eliId
     *
     * @return Dictum
     */
    public function setEliId($eliId)
    {
        // ref : http://eur-lex.europa.eu/legal-content/EN/TXT/?uri=CELEX:52012XG1026(01)#C_2012325EN.01000501
        $country=$this->getSource()->getCountry();

        $jurisdiction=$country->getIso2();
        $authority = $this->getAuthority();

        $agent=$authority->getAuthoritySupreme()->getShortname();
        $subAgent=$authority->getShortname();

        $date = $this->getDatePublication();
        // $year=$date->format('Y');
        // $month=$date->format('m');
        // $day=$date->format('d');
        $year="XXXX";
        $month="XX";
        $day="XX";

        $type=$this->getType()->getName();
        $naturalIdentifier=$this->getCountryTextId();
        // if article is 15-3-2 level is 15/3/2
        $levels = preg_replace('/\-/', '/', $this->getNum());
        $date = new \DateTime('now',  new \DateTimeZone( 'Europe/Paris' ));
        $pointInTime=$date->format('Ymd');
        $version=$this->getTextId();
        $language=$country->getLanguage()->getIso();
        $format="html";
        $url="/eli/".$jurisdiction."/".$agent."/".$subAgent."/".$year."/".$month."/".$day."/".$type."/".$naturalIdentifier."/".$levels."/".$pointInTime."/".$version."/".$language."/".$format;

        $this->eli_id = $url;
        return $this;
    }




    /**
     * Set countryTextId
     *
     * @param string $countryTextId
     *
     * @return Dictum
     */
    public function setCountryTextId($countryTextId)
    {
        $this->country_text_id = $countryTextId;

        return $this;
    }

    /**
     * Get countryTextId
     *
     * @return string
     */
    public function getCountryTextId()
    {
        return $this->country_text_id;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     *
     * @return Dictum
     */
    public function setDatePublication($datePublication)
    {
        $this->date_publication = $datePublication;

        return $this;
    }

    /**
     * Get datePublication
     *
     * @return \DateTime
     */
    public function getDatePublication()
    {
        return $this->date_publication;
    }

    /**
     * Set dateSignature
     *
     * @param \DateTime $dateSignature
     *
     * @return Dictum
     */
    public function setDateSignature($dateSignature)
    {
        $this->date_signature = $dateSignature;

        return $this;
    }

    /**
     * Get dateSignature
     *
     * @return \DateTime
     */
    public function getDateSignature()
    {
        return $this->date_signature;
    }

    /**
     * Set authority
     *
     * @param \Dwl\LexBundle\Entity\Authority $authority
     *
     * @return Dictum
     */
    public function setAuthority(\Dwl\LexBundle\Entity\Authority $authority = null)
    {
        $this->authority = $authority;

        return $this;
    }

    /**
     * Get authority
     *
     * @return \Dwl\LexBundle\Entity\Authority
     */
    public function getAuthority()
    {
        return $this->authority;
    }
}