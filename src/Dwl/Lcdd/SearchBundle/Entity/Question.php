<?php

namespace Dwl\Lcdd\SearchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Gedmo\Mapping\Annotation as Gedmo;

use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="Dwl\Lcdd\SearchBundle\Repository\QuestionRepository")
 */
class Question
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
     * @ORM\Column(name="question", type="string", length=255)
     */
    private $question;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"question"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var boolean
     *
     * @ORM\Column(name="qualified", type="boolean")
     */
    private $qualified = false;

    /**
     * @var \Dwl\Lcdd\SearchBundle\Entity\Question
     *
     * @ORM\OneToMany(targetEntity="\Dwl\Lcdd\SearchBundle\Entity\Question", mappedBy="qualifiedQuestion", cascade={"persist", "remove"})
     */
    private $unqualifiedQuestions;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="\Dwl\Lcdd\SearchBundle\Entity\Question", inversedBy="unqualifiedQuestions")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $qualifiedQuestion;

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
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="\Application\Sonata\ClassificationBundle\Entity\Tag")
     * @ORM\JoinTable(name="questions__legal_tags",
     *      joinColumns={@ORM\JoinColumn(name="question_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     */
    private $legalTags;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="\Application\Sonata\ClassificationBundle\Entity\Tag")
     * @ORM\JoinTable(name="questions__civil_tags",
     *      joinColumns={@ORM\JoinColumn(name="question_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     */
    private $civilTags;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="\Application\Sonata\ClassificationBundle\Entity\Category")
     * @ORM\JoinTable(name="questions_categories",
     *      joinColumns={@ORM\JoinColumn(name="question_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     * )
     */
    private $categories;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\MediaBundle\Entity\Media",cascade={"persist"})
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     */
    private $media;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="\Dwl\Lcdd\SpeakerBundle\Entity\Speaker", inversedBy="questions")
     * @ORM\JoinColumn(name="speaker_id", referencedColumnName="id")
     */
    private $speaker;

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getQuestion() ?: 'n/a';
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->unqualifiedQuestions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->legalTags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->civilTags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set question
     *
     * @param string $question
     * @return Question
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Question
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set qualified
     *
     * @param boolean $qualified
     * @return Question
     */
    public function setQualified($qualified)
    {
        $this->qualified = $qualified;

        return $this;
    }

    /**
     * Get qualified
     *
     * @return boolean
     */
    public function getQualified()
    {
        return $this->qualified;
    }

    /**
     * Set date_create
     *
     * @param \DateTime $dateCreate
     * @return Question
     */
    public function setDateCreate($dateCreate)
    {
        $this->date_create = $dateCreate;

        return $this;
    }

    /**
     * Get date_create
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->date_create;
    }

    /**
     * Set date_update
     *
     * @param \DateTime $dateUpdate
     * @return Question
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->date_update = $dateUpdate;

        return $this;
    }

    /**
     * Get date_update
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->date_update;
    }

    /**
     * Set unqualifiedQuestions
     *
     * @param \Dwl\Lcdd\SearchBundle\Entity\Question $unqualifiedQuestions
     * @return Question
     */
    public function setUnqualifiedQuestions(\Doctrine\Common\Collections\ArrayCollection $unqualifiedQuestions)
    {
        $this->unqualifiedQuestions = new \Doctrine\Common\Collections\ArrayCollection();

        foreach ($unqualifiedQuestions as $key => $unqualifiedQuestion) {
            $this->addUnqualifiedQuestion($unqualifiedQuestion);
        }

        return $this;
    }

    public function hasUnqualifiedQuestion(\Dwl\Lcdd\SearchBundle\Entity\Question $unqualifiedQuestion)
    {
        return $this->unqualifiedQuestions->contains($unqualifiedQuestion);
        }

    /**
     * Add unqualifiedQuestion
     *
     * @param \Dwl\Lcdd\SearchBundle\Entity\Question $unqualifiedQuestion
     * @return Question
     */
    public function addUnqualifiedQuestion(\Dwl\Lcdd\SearchBundle\Entity\Question $unqualifiedQuestion)
    {
        $this->unqualifiedQuestions->add($unqualifiedQuestion);
        $unqualifiedQuestion->setQualifiedQuestion($this);

        return $this;
    }

    /**
     * Remove unqualifiedQuestions
     *
     * @param \Dwl\Lcdd\SearchBundle\Entity\Question $unqualifiedQuestion
     */
    public function removeUnqualifiedQuestion(\Dwl\Lcdd\SearchBundle\Entity\Question $unqualifiedQuestion)
    {

        if ($this->hasUnqualifiedQuestion($unqualifiedQuestion)) {
            $this->unqualifiedQuestions->removeElement($unqualifiedQuestion);
            $unqualifiedQuestion->removeQualifiedQuestion();
        }

        return $this;
    }

    /**
     * Get unqualifiedQuestions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUnqualifiedQuestions()
    {
        return $this->unqualifiedQuestions;
    }

    /**
     * Set qualifiedQuestion
     *
     * @param \Dwl\Lcdd\SearchBundle\Entity\Question $qualifiedQuestion
     * @return Question
     */
    public function setQualifiedQuestion(\Dwl\Lcdd\SearchBundle\Entity\Question $qualifiedQuestion = null)
    {
        $this->qualifiedQuestion = $qualifiedQuestion;

        return $this;
    }

    /**
     * Get qualifiedQuestion
     *
     * @return \Dwl\Lcdd\SearchBundle\Entity\Question
     */
    public function getQualifiedQuestion()
    {
        return $this->qualifiedQuestion;
    }

    /**
     * Remove qualifiedQuestion
     *
     * @return \Dwl\Lcdd\SearchBundle\Entity\Question
     */
    public function removeQualifiedQuestion()
    {

        $this->qualifiedQuestion = null;

        return $this;
    }

    /**
     * Add legalTags
     *
     * @param \Application\Sonata\ClassificationBundle\Entity\Tag $legalTags
     * @return Question
     */
    public function addLegalTag(\Application\Sonata\ClassificationBundle\Entity\Tag $legalTags)
    {
        $this->legalTags[] = $legalTags;

        return $this;
    }

    /**
     * Remove legalTags
     *
     * @param \Application\Sonata\ClassificationBundle\Entity\Tag $legalTags
     */
    public function removeLegalTag(\Application\Sonata\ClassificationBundle\Entity\Tag $legalTags)
    {
        $this->legalTags->removeElement($legalTags);
    }

    /**
     * Get legalTags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLegalTags()
    {
        return $this->legalTags;
    }

    /**
     * Add civilTags
     *
     * @param \Application\Sonata\ClassificationBundle\Entity\Tag $civilTags
     * @return Question
     */
    public function addCivilTag(\Application\Sonata\ClassificationBundle\Entity\Tag $civilTags)
    {
        $this->civilTags[] = $civilTags;

        return $this;
    }

    /**
     * Remove civilTags
     *
     * @param \Application\Sonata\ClassificationBundle\Entity\Tag $civilTags
     */
    public function removeCivilTag(\Application\Sonata\ClassificationBundle\Entity\Tag $civilTags)
    {
        $this->civilTags->removeElement($civilTags);
    }

    /**
     * Get civilTags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCivilTags()
    {
        return $this->civilTags;
    }

    /**
     * Add categories
     *
     * @param \Application\Sonata\ClassificationBundle\Entity\Category $categories
     * @return Question
     */
    public function addCategory(\Application\Sonata\ClassificationBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Application\Sonata\ClassificationBundle\Entity\Category $categories
     */
    public function removeCategory(\Application\Sonata\ClassificationBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set media
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $media
     * @return Question
     */
    public function setMedia(\Application\Sonata\MediaBundle\Entity\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set speaker
     *
     * @param \Dwl\Lcdd\SpeakerBundle\Entity\Speaker $speaker
     * @return Question
     */
    public function setSpeaker(\Dwl\Lcdd\SpeakerBundle\Entity\Speaker $speaker = null)
    {
        $this->speaker = $speaker;

        return $this;
    }

    /**
     * Get speaker
     *
     * @return \Dwl\Lcdd\SpeakerBundle\Entity\Speaker
     */
    public function getSpeaker()
    {
        return $this->speaker;
    }
}
