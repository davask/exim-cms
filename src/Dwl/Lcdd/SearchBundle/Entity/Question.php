<?php

namespace Dwl\Lcdd\SearchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gedmo\Mapping\Annotation as Gedmo;

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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->qualified = false;
        $this->unqualifiedQuestions = new \Doctrine\Common\Collections\ArrayCollection();
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
}
