<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Model;

use Epixa\ForumBundle\Entity\Category as CategoryEntity,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * A representation of the parameters used to delete a category
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
     * @var \Epixa\ForumBundle\Entity\Category
     */
    protected $targetCategory;

    /**
     * @Assert\NotBlank()
     * @var \Epixa\ForumBundle\Entity\Category|null
     */
    protected $inheritingCategory = null;


    /**
     * Constructs the required deletion options
     *
     * The category that is targeted for deletion is set here to ensure it is never not set.
     *
     * @param \Epixa\ForumBundle\Entity\Category $category
     */
    public function __construct(CategoryEntity $category)
    {
        if (!$category) {
            throw new \InvalidArgumentException('No target category specified');
        }

        $this->targetCategory = $category;
    }

    /**
     * Gets the category that is targeted for deletion
     * 
     * @return \Epixa\ForumBundle\Entity\Category
     */
    public function getTargetCategory()
    {
        return $this->targetCategory;
    }

    /**
     * Sets the category id that should inherit all posts from the deleted category
     *
     * @param \Epixa\ForumBundle\Entity\Category $category
     * @return CategoryDeletionOptions *Fluent interface*
     */
    public function setInheritingCategory($category)
    {
        if ($category->getId() === $this->getTargetCategory()->getId()) {
            throw new \InvalidArgumentException('A category targeted for deletion cannot be its own heir');
        }
        
        $this->inheritingCategory = $category;
        return $this;
    }

    /**
     * Gets the category id that should inherit all posts from the deleted category
     * 
     * @return \Epixa\ForumBundle\Entity\Category|null
     */
    public function getInheritingCategory()
    {
        return $this->inheritingCategory;
    }
}