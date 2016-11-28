<?php

namespace Dwl\Lcdd\SearchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gedmo\Mapping\Annotation as Gedmo;

use Dwl\Lcdd\SearchBundle\Component\Question\QuestionCategoryInterface as QuestionCategoryInterface;

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
     * @ORM\Column(name="question", type="string", length=255, nullable=false)
     */
    private $question;

    /**
     * @var boolean
     *
     * @ORM\Column(name="qualified", type="boolean", nullable=false)
     */
    private $qualified;

    /**
     * @ORM\ManyToOne(targetEntity="\Dwl\Lcdd\SearchBundle\Entity\Question", inversedBy="unqualifiedQuestions")
     */
    private $qualifiedQuestion;

    /**
     * @ORM\OneToMany(targetEntity="\Dwl\Lcdd\SearchBundle\Entity\Question", mappedBy="qualifiedQuestion")
     */
    private $unqualifiedQuestions;

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

    /* TODO: to move in a Model Class ? */

    /**
     * @var ArrayCollection
     */
    private $legalTags;

    /**
     * @var ArrayCollection
     */
    private $civilTags;

    /**
     * @var ArrayCollection
     */
    private $questionCategories;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->qualified = false;
        $this->unqualifiedQuestions = new ArrayCollection();
        $this->legalTags = new ArrayCollection();
        $this->civilTags = new ArrayCollection();
        $this->questionCategories = new ArrayCollection();
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
     * Add unqualifiedQuestions
     *
     * @param \Dwl\Lcdd\SearchBundle\Entity\Question $unqualifiedQuestions
     * @return Question
     */
    public function addUnqualifiedQuestion(\Dwl\Lcdd\SearchBundle\Entity\Question $unqualifiedQuestions)
    {
        $this->unqualifiedQuestions[] = $unqualifiedQuestions;

        return $this;
    }

    /**
     * Remove unqualifiedQuestions
     *
     * @param \Dwl\Lcdd\SearchBundle\Entity\Question $unqualifiedQuestions
     */
    public function removeUnqualifiedQuestion(\Dwl\Lcdd\SearchBundle\Entity\Question $unqualifiedQuestions)
    {
        $this->unqualifiedQuestions->removeElement($unqualifiedQuestions);
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
     * {@inheritdoc}
     */
    public function toArray()
    {
        $baseArrayRep = array(
            'question' => $this->question,
            'qualified' => $this->qualified,
        );

        return $baseArrayRep;
    }

    /**
     * {@inheritdoc}
     */
    public function fromArray($array)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($array as $key => $value) {
            $accessor->setValue($this, $key, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addQuestionCategorie(QuestionCategoryInterface $questionCategory)
    {
        $this->addQuestionCategory($questionCategory);
    }

    /**
     * {@inheritdoc}
     */
    public function removeQuestionCategorie(QuestionCategoryInterface $questionCategory)
    {
        $this->removeQuestionCategory($questionCategory);
    }

    /**
     * {@inheritdoc}
     */
    public function addQuestionCategory(QuestionCategoryInterface $questionCategory)
    {
        $questionCategory->setQuestion($this);

        $this->questionCategories->add($questionCategory);
    }

    /**
     * {@inheritdoc}
     */
    public function removeQuestionCategory(QuestionCategoryInterface $questionCategory)
    {
        if ($this->questionCategories->contains($questionCategory)) {
            $this->questionCategories->removeElement($questionCategory);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQuestionCategories()
    {
        return $this->questionCategories;
    }

    /**
     * {@inheritdoc}
     */
    public function setQuestionCategories(ArrayCollection $questionCategories)
    {
        $this->questionCategories = $questionCategories;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategories()
    {
        $categories = new ArrayCollection();

        foreach ($this->questionCategories as $questionCategory) {
            if (!$categories->contains($questionCategory)) {
                $categories->add($questionCategory->getCategory());
            }
        }

        return $categories;
    }

    /**
     * {@inheritdoc}
     */
    public function getMainCategory()
    {
        foreach ($this->getQuestionCategories() as $questionCategory) {
            if ($questionCategory->getMain()) {
                return $questionCategory->getCategory();
            }
        }
    }

    // /**
    //  * {@inheritdoc}
    //  */
    // public function hasOneMainCategory()
    // {
    //     if ($this->getCategories()->count() == 0) {
    //         return false;
    //     }

    //     $has = false;

    //     foreach ($this->getQuestionCategories() as $questionCategory) {
    //         if ($questionCategory->getMain()) {
    //             if ($has) {
    //                 $has = false;
    //                 break;
    //             }

    //             $has = true;
    //         }
    //     }

    //     return $has;
    // }

    // /**
    //  * {@inheritdoc}
    //  */
    // public function validateOneMainCategory(ExecutionContextInterface $context)
    // {
    //     if ($this->getCategories()->count() == 0) {
    //         return;
    //     }

    //     if (!$this->hasOneMainCategory()) {
    //         $context->addViolation('dwl.lcdd.search.question.must_have_one_main_category');
    //     }
    // }

}
