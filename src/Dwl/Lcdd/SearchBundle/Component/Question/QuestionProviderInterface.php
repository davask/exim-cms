<?php

namespace Dwl\Lcdd\SearchBundle\Component\Question;

use Doctrine\Common\Collections\ArrayCollection;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
// use Sonata\Component\Basket\BasketElementInterface;
// use Sonata\Component\Basket\BasketElementManagerInterface;
// use Sonata\Component\Basket\BasketInterface;
// use Sonata\Component\Currency\CurrencyInterface;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;

interface QuestionProviderInterface
{
    /*

    /* *
     * @param \Sonata\Component\Basket\BasketElementManagerInterface $basketElementManager
     * /
    public function setBasketElementManager(BasketElementManagerInterface $basketElementManager);

    /* *
     * @return \Sonata\Component\Basket\BasketElementManagerInterface
     * /
    public function getBasketElementManager();

    /* *
     * @param \Sonata\Component\Question\QuestionInterface $question      A Sonata question instance
     * @param \Symfony\Component\Form\FormBuilder        $formBuilder  Symfony form builder
     * @param bool                                       $showQuantity Specifies if quantity field will be displayed (default true)
     * @param array                                      $options      An options array
     * /
    public function defineAddBasketForm(QuestionInterface $question, FormBuilder $formBuilder, $showQuantity = true, array $options = array());

    /* *
     * @param \Sonata\Component\Basket\BasketElementInterface $basketElement
     * @param \Symfony\Component\Form\FormBuilder             $formBuilder
     * @param array                                           $options
     * /
    public function defineBasketElementForm(BasketElementInterface $basketElement, FormBuilder $formBuilder, array $options = array());

    /* *
     * return true if the basket element is still valid.
     *
     * @param \Sonata\Component\Basket\BasketInterface        $basket
     * @param QuestionInterface                                $question
     * @param \Sonata\Component\Basket\BasketElementInterface $newBasketElement
     * /
    public function basketAddQuestion(BasketInterface $basket, QuestionInterface $question, BasketElementInterface $newBasketElement);

    /* *
     * Merge a question with another when the question is already present into the basket.
     *
     * @param \Sonata\Component\Basket\BasketInterface        $basket
     * @param QuestionInterface                                $question
     * @param \Sonata\Component\Basket\BasketElementInterface $newBasketElement
     * /
    public function basketMergeQuestion(BasketInterface $basket, QuestionInterface $question, BasketElementInterface $newBasketElement);

    /* *
     * @param \Sonata\Component\Basket\BasketElementInterface $basketElement
     *
     * @return bool true if the basket element is still valid
     * /
    public function isValidBasketElement(BasketElementInterface $basketElement);

    /* *
     * Updates basket element different prices computation fields values.
     *
     * @param BasketInterface        $basket        A basket instance
     * @param BasketElementInterface $basketElement A basket element instance
     * @param QuestionInterface       $question       A question instance
     * /
    public function updateComputationPricesFields(BasketInterface $basket, BasketElementInterface $basketElement, QuestionInterface $question);

    /* *
     * Calculate the question price depending on the currency.
     *
     * @param QuestionInterface       $question  A question instance
     * @param CurrencyInterface|null $currency A currency instance
     * @param bool                   $vat      Returns price including VAT?
     * @param int                    $quantity Defaults to one
     *
     * @return float
     * /
    public function calculatePrice(QuestionInterface $question, CurrencyInterface $currency, $vat = false, $quantity = 1);

    /* *
     * Return true if the question can be added to the provided basket.
     *
     * @param \Sonata\Component\Basket\BasketInterface   $basket
     * @param \Sonata\Component\Question\QuestionInterface $question
     * @param array                                      $options
     *
     * @return bool
     * /
    public function isAddableToBasket(BasketInterface $basket, QuestionInterface $question, array $options = array());

    /* *
     * @param null|QuestionInterface $question
     * @param array                 $options
     *
     * @return BasketElementInterface
     * /
    public function createBasketElement(QuestionInterface $question = null, array $options = array());

    /* *
     * @param \Sonata\Component\Basket\BasketElementInterface $basketElement
     * @param null|\Sonata\Component\Question\QuestionInterface $question
     * @param array                                           $options
     * /
    public function buildBasketElement(BasketElementInterface $basketElement, QuestionInterface $question = null, array $options = array());

    /* *
     * return an array of errors if any, you can also manipulate the basketElement if require
     * please not you always work with a clone version of the basketElement.
     *
     * If the basket is valid it will then replace the one in session
     *
     * @param \Sonata\CoreBundle\Validator\ErrorElement       $errorElement
     * @param \Sonata\Component\Basket\BasketElementInterface $basketElement
     * @param \Sonata\Component\Basket\BasketInterface        $basket
     * /
    public function validateFormBasketElement(ErrorElement $errorElement, BasketElementInterface $basketElement, BasketInterface $basket);

    /* *
     * Creates a variation from a given Question and its dependencies.
     *
     * @param QuestionInterface $question          Question to duplicate
     * @param bool             $copyDependencies If false, duplicates only Question (without dependencies)
     *
     * @throws \RuntimeException
     *
     * @return \Sonata\Component\Question\QuestionInterface
     * /
    public function createVariation(QuestionInterface $question, $copyDependencies = true);

    /* *
     * Synchronizes all parent Question data to its variations (or a single one if $targetVariation is specified).
     *
     * @param QuestionInterface $question    Parent Question
     * @param ArrayCollection  $variations Optional target variations to synchronize
     * /
    public function synchronizeVariations(QuestionInterface $question, ArrayCollection $variations = null);

    /* *
     * Synchronizes parent Question data to its variations (or a single one if $targetVariation is specified).
     *
     * @param QuestionInterface $question    Parent Question
     * @param ArrayCollection  $variations Optional target variations to synchronize
     * /
    public function synchronizeVariationsQuestion(QuestionInterface $question, ArrayCollection $variations = null);

    /* *
     * Synchronizes parent Question deliveries to its variations (or a single one if $targetVariation is specified).
     *
     * @param QuestionInterface $question    Parent Question
     * @param ArrayCollection  $variations Optional target variations to synchronize
     * /
    public function synchronizeVariationsDeliveries(QuestionInterface $question, ArrayCollection $variations = null);

    /* *
     * Synchronizes parent Question categories to its variations (or a single one if $targetVariation is specified).
     *
     * @param QuestionInterface $question    Parent Question
     * @param ArrayCollection  $variations Optional target variations to synchronize
     * /
    public function synchronizeVariationsCategories(QuestionInterface $question, ArrayCollection $variations = null);

    /* *
     * Synchronizes parent Question collections to its variations (or a single one if $targetVariation is specified).
     *
     * @param QuestionInterface $question    Parent Question
     * @param ArrayCollection  $variations Optional target variations to synchronize
     * /
    public function synchronizeVariationsCollections(QuestionInterface $question, ArrayCollection $variations = null);

    /* *
     * Synchronizes parent Question packages to its variations (or a single one if $targetVariation is specified).
     *
     * @param QuestionInterface $question    Parent Question
     * @param ArrayCollection  $variations Optional target variations to synchronize
     * /
    public function synchronizeVariationsPackages(QuestionInterface $question, ArrayCollection $variations = null);

    /* *
     * Check if the question has variations.
     *
     * @param QuestionInterface $question
     *
     * @return bool
     * /
    public function hasVariations(QuestionInterface $question);

    /* *
     * Return true if Question has enabled variation(s).
     *
     * @param QuestionInterface $question
     *
     * @return bool
     * /
    public function hasEnabledVariations(QuestionInterface $question);

    /* *
     * Return the list of enabled question variations.
     *
     * @param QuestionInterface $question
     *
     * @return ArrayCollection
     * /
    public function getEnabledVariations(QuestionInterface $question);

    /* *
     * Fetch the cheapest variation if provided/existing.
     *
     * @param QuestionInterface $question
     *
     * @return null|QuestionInterface
     * /
    public function getCheapestEnabledVariation(QuestionInterface $question);

    /* *
     * return the stock available for the current question.
     *
     * @param \Sonata\Component\Question\QuestionInterface $question
     *
     * @return int the stock available
     * /
    public function getStockAvailable(QuestionInterface $question);

    /* *
     * Gets the fields on which the question might be filtered in the catalog view.
     *
     * @return mixed
     * /
    public function getFilters();

    /* *
     * Gets the possible values for $fields (or variation fields if not set).
     *
     * @param QuestionInterface $question
     * @param array            $fields
     *
     * @return array
     * /
    public function getVariationsChoices(QuestionInterface $question, array $fields = array());

    /* *
     * Gets the properties values of $question amongst variation fields or $fields if set.
     *
     * @param QuestionInterface $question
     * @param array            $fields
     *
     * @return array
     * /
    public function getVariatedProperties(QuestionInterface $question, array $fields = array());

    /* *
     * Gets the variation matching $choices from master question $question.
     *
     * @param QuestionInterface $question
     * @param array            $choices
     * /
    public function getVariation(QuestionInterface $question, array $choices = array());

    /* *
     * Update the stock value of a given Question id.
     *
     * @param QuestionInterface|int    $question
     * @param QuestionManagerInterface $questionManager
     * @param int                     $diff
     * /
    public function updateStock($question, QuestionManagerInterface $questionManager, $diff);

    */

    /**
     * @param QuestionCategoryManagerInterface $questionCategoryManager
     */
    public function setQuestionCategoryManager(QuestionCategoryManagerInterface $questionCategoryManager);

    /**
     * @return QuestionCategoryManagerInterface
     */
    public function getQuestionCategoryManager();

    /**
     * @param QuestionCollectionManagerInterface $questionCollectionManager
     */
    public function setQuestionCollectionManager(QuestionCollectionManagerInterface $questionCollectionManager);

    /**
     * @return QuestionCollectionManagerInterface
     */
    public function getQuestionCollectionManager();

    /**
     * @return string
     */
    public function getBaseControllerName();

    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     */
    public function configureShowFields(ShowMapper $showMapper);

    /**
     * Build form by adding provider fields.
     *
     * @param FormBuilderInterface $builder     Symfony form builder
     * @param array                $options     An options array
     * @param bool                 $isVariation Is the question a variation of a master question?
     */
    public function buildForm(FormBuilderInterface $builder, array $options, $isVariation = false);

    /**
     * @param FormMapper $formMapper
     * @param bool       $isVariation
     */
    public function buildEditForm(FormMapper $formMapper, $isVariation = false);

    /**
     * @param FormMapper $formMapper
     */
    public function buildCreateForm(FormMapper $formMapper);

}
