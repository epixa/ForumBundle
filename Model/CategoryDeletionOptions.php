<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Model;

use Epixa\ForumBundle\Entity\Category as CategoryEntity,
    Symfony\Component\Validator\Constraints as Assert,
    Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface,
    Symfony\Component\Validator\ExecutionContext;

/**
 * A representation of the parameters used to delete a category
 *
 * @Assert\Callback(methods = {"isInheritingCategoryValid"})
 * 
 * @category   EpixaForumBundle
 * @package    Model
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class CategoryDeletionOptions
{
    /**
     * @Assert\NotBlank()
     * @var integer|null
     */
    protected $inheritingCategoryId = null;

    /**
     * @var \Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface|null
     */
    protected $choices = null;


    /**
     * Sets the category id that should inherit all posts from the deleted category
     *
     * @param integer $id
     * @return CategoryDeletionOptions *Fluent interface*
     */
    public function setInheritingCategoryId($id)
    {
        $this->inheritingCategoryId = (int)$id;
        return $this;
    }

    /**
     * Gets the category id that should inherit all posts from the deleted category
     * 
     * @return integer|null
     */
    public function getInheritingCategoryId()
    {
        return $this->inheritingCategoryId;
    }

    /**
     * Sets the choice list for all possible inheriting categories
     *
     * @param \Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface $choices
     * @return CategoryDeletionOptions *Fluent interface*
     */
    public function setInheritingCategoryChoices(ChoiceListInterface $choices)
    {
        $this->choices = $choices;
        return $this;
    }

    /**
     * Gets the choice list for all possible inheriting categories
     * 
     * @return \Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface|null
     */
    public function getInheritingCategoryChoices()
    {
        return $this->choices;
    }

    /**
     * Is the set inheriting category valid?
     * 
     * @throws \LogicException
     * @param \Symfony\Component\Validator\ExecutionContext $context
     * @return void
     */
    public function isInheritingCategoryValid(ExecutionContext $context)
    {
        $choiceList = $this->getInheritingCategoryChoices();
        if (!$choiceList) {
            throw new \LogicException('No choice list configured for inheriting categories');
        }
        
        $choices = $this->getInheritingCategoryChoices()->getChoices();
        if (!array_key_exists($this->getInheritingCategoryId(), $choices)) {
            $propertyPath = $context->getPropertyPath() . '.inheritingCategoryId';
            $context->setPropertyPath($propertyPath);
            $context->addViolation('The category you chose is not a valid option', array(), null);
        }
    }
}