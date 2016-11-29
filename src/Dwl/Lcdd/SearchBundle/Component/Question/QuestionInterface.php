<?php

namespace Dwl\Lcdd\SearchBundle\Component\Question;

use Doctrine\Common\Collections\ArrayCollection;
use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\Validator\ExecutionContextInterface;

use Application\Sonata\ClassificationBundle\Entity\Tag;

interface QuestionInterface
{

    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set question
     *
     * @param string $question
     * @return Question
     */
    public function setQuestion($question);

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion();

    /**
     * Set qualified
     *
     * @param boolean $qualified
     * @return Question
     */
    public function setQualified($qualified);

    /**
     * Get qualified
     *
     * @return boolean
     */
    public function getQualified();

    /**
     * Set date_create
     *
     * @param \DateTime $dateCreate
     * @return Question
     */
    public function setDateCreate($dateCreate);

    /**
     * Get date_create
     *
     * @return \DateTime
     */
    public function getDateCreate();

    /**
     * Set date_update
     *
     * @param \DateTime $dateUpdate
     * @return Question
     */
    public function setDateUpdate($dateUpdate);

    /**
     * Get date_update
     *
     * @return \DateTime
     */
    public function getDateUpdate();

    /**
     * Set qualifiedQuestion
     *
     * @param \Dwl\Lcdd\SearchBundle\Entity\QuestionInterface $qualifiedQuestion
     * @return Question
     */
    public function setQualifiedQuestion(\Dwl\Lcdd\SearchBundle\Entity\QuestionInterface $qualifiedQuestion = null);

    /**
     * Get qualifiedQuestion
     *
     * @return \Dwl\Lcdd\SearchBundle\Entity\QuestionInterface
     */
    public function getQualifiedQuestion();

    /**
     * Add unqualifiedQuestions
     *
     * @param \Dwl\Lcdd\SearchBundle\Entity\QuestionInterface $unqualifiedQuestions
     * @return Question
     */
    public function addUnqualifiedQuestion(\Dwl\Lcdd\SearchBundle\Entity\QuestionInterface $unqualifiedQuestions);

    /**
     * Remove unqualifiedQuestions
     *
     * @param \Dwl\Lcdd\SearchBundle\Entity\QuestionInterface $unqualifiedQuestions
     */
    public function removeUnqualifiedQuestion(\Dwl\Lcdd\SearchBundle\Entity\QuestionInterface $unqualifiedQuestions);

    /**
     * Get unqualifiedQuestions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUnqualifiedQuestions();

    /**
     * Add legalTags
     *
     * @param Tag $legalTags
     * @return Question
     */
    public function addLegalTag(Tag $legalTags);

    /**
     * Remove legalTags
     *
     * @param Tag $legalTags
     */
    public function removeLegalTag(Tag $legalTags);

    /**
     * Get legalTags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLegalTags();

    /**
     * Add civilTags
     *
     * @param Tag $civilTags
     * @return Question
     */
    public function addCivilTag(Tag $civilTags);

    /**
     * Remove civilTags
     *
     * @param Tag $civilTags
     */
    public function removeCivilTag(Tag $civilTags);

    /**
     * Get civilTags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCivilTags();

    /**
     * {@inheritdoc}
     */
    public function toArray();

    /**
     * {@inheritdoc}
     */
    public function fromArray($array);

    /**
     * {@inheritdoc}
     */
    public function addQuestionCategorie($questionCategory);

    /**
     * {@inheritdoc}
     */
    public function removeQuestionCategorie($questionCategory);

    /**
     * {@inheritdoc}
     */
    public function addQuestionCategory($questionCategory);

    /**
     * {@inheritdoc}
     */
    public function removeQuestionCategory($questionCategory);

    /**
     * {@inheritdoc}
     */
    public function getQuestionCategories();

    /**
     * {@inheritdoc}
     */
    public function setQuestionCategories(ArrayCollection $questionCategories);

    /**
     * {@inheritdoc}
     */
    public function getCategories();

    /**
     * {@inheritdoc}
     */
    public function getMainCategory();

    /**
     * {@inheritdoc}
     */
    public function hasOneMainCategory();

    /**
     * {@inheritdoc}
     */
    public function validateOneMainCategory(ExecutionContextInterface $context);
}
