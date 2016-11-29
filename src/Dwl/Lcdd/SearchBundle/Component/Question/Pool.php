<?php

namespace Dwl\Lcdd\SearchBundle\Component\Question;

class Pool
{
    /**
     * @var array
     */
    protected $questions = array();

    /**
     * add a delivery method into the pool.
     *
     * @param string                                      $code
     * @param \Dwl\Lcdd\SearchBundle\Component\Question\QuestionDefinition $questionDescription
     */
    public function addQuestion($code, QuestionDefinition $questionDescription)
    {
        $this->questions[$code] = $questionDescription;
    }

    /**
     * @param QuestionInterface|string $code
     *
     * @throws \RuntimeException
     *
     * @return \Dwl\Lcdd\SearchBundle\Component\Question\QuestionProviderInterface
     */
    public function getProvider($code)
    {
        if ($code instanceof QuestionInterface) {
            $code = $this->getQuestionCode($code);

            if (!$code) {
                throw new \RuntimeException(sprintf('The class is not linked to a QuestionProvider!'));
            }
        }

        return $this->getQuestion($code)->getProvider();
    }

    /**
     * @param QuestionInterface|string $code
     *
     * @throws \RuntimeException
     *
     * @return \Dwl\Lcdd\SearchBundle\Component\Question\QuestionManagerInterface
     */
    public function getManager($code)
    {
        if ($code instanceof QuestionInterface) {
            $code = $this->getQuestionCode($code);

            if (!$code) {
                throw new \RuntimeException(sprintf('The class is not linked to a QuestionManager!'));
            }
        }

        return $this->getQuestion($code)->getManager();
    }

    /**
     * @param QuestionInterface $question
     *
     * @return int|null|string
     */
    public function getQuestionCode(QuestionInterface $question)
    {
        dump($this->questions);
        foreach ($this->questions as $code => $questionDescription) {
            $className = $questionDescription->getManager()->getClass();
            if ($question instanceof $className) {
                return $code;
            }
        }

        return;
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public function hasProvider($code)
    {
        return isset($this->questions[$code]);
    }

    /**
     * Tells if a question with $code is in the pool.
     *
     * @param string $code
     *
     * @return bool
     */
    public function hasQuestion($code)
    {
        return isset($this->questions[$code]);
    }

    /**
     * @param string $code
     *
     * @throws \RuntimeException
     *
     * @return \Dwl\Lcdd\SearchBundle\Component\Question\QuestionDefinition
     */
    public function getQuestion($code)
    {
        if (!$this->hasQuestion($code)) {
            throw new \RuntimeException(sprintf('The question definition `%s` does not exist!', $code));
        }

        return $this->questions[$code];
    }

    /**
     * @return array
     */
    public function getQuestions()
    {
        return $this->questions;
    }
}
