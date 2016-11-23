<?php

namespace Dwl\Lcdd\SearchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

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
     * @var bool
     *
     * @ORM\Column(name="qualified", type="bool")
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

}
