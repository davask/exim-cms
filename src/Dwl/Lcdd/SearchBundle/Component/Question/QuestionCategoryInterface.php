<?php

namespace Dwl\Lcdd\SearchBundle\Component\Question;

use Sonata\ClassificationBundle\Model\CategoryInterface;

interface QuestionCategoryInterface
{
    /**
     * Set enabled.
     *
     * @param bool $enabled
     */
    public function setEnabled($enabled);

    /**
     * Get enabled.
     *
     * @return bool $enabled
     */
    public function getEnabled();

    /**
     * Set if question category is the main category.
     *
     * @param bool $main
     */
    public function setMain($main);

    /**
     * Get if question category is the main category.
     *
     * @return bool $main
     */
    public function getMain();

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt = null);

    /**
     * Get updatedAt.
     *
     * @return \DateTime $updatedAt
     */
    public function getUpdatedAt();

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt = null);

    /**
     * Get createdAt.
     *
     * @return \Datetime $createdAt
     */
    public function getCreatedAt();

    /**
     * Set Question.
     *
     * @param QuestionInterface $question
     */
    public function setQuestion(QuestionInterface $question);

    /**
     * Get Question.
     *
     * @return QuestionInterface
     */
    public function getQuestion();

    /**
     * Set Category.
     *
     * @param CategoryInterface $category
     */
    public function setCategory(CategoryInterface $category);

    /**
     * Get Category.
     *
     * @return CategoryInterface $category
     */
    public function getCategory();
}
