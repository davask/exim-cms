<?php

namespace Dwl\Lcdd\SearchBundle\Component\Question;

use Sonata\CoreBundle\Model\ManagerInterface;
use Sonata\CoreBundle\Model\PageableManagerInterface;

interface QuestionManagerInterface extends ManagerInterface, PageableManagerInterface
{
    /*

    /* *
     * Returns the questions in the same collections as those specified in $questionCollections.
     *
     * @param mixed $questionCollections
     *
     * @return array
     * /
    public function findInSameCollections($questionCollections);

    /* *
     * Returns the parent questions in the same collections as those specified in $questionCollections.
     *
     * @param mixed $questionCollections
     *
     * @return array
     * /
    public function findParentsInSameCollections($questionCollections);

    /* *
     * Retrieve an active question from its id and its slug.
     *
     * @param int    $id
     * @param string $slug
     *
     * @return QuestionInterface|null
     * /
    public function findEnabledFromIdAndSlug($id, $slug);

    /* *
     * @param QuestionInterface $question
     *
     * @return array
     * /
    public function findVariations(QuestionInterface $question);

    /* *
     * Updated stock value for a given Question.
     *
     * @param QuestionInterface|int $question
     * @param int                  $diff
     * /
    public function updateStock($question, $diff);

    */
}
