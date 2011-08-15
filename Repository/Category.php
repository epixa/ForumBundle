<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Repository;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\QueryBuilder,
    Epixa\ForumBundle\Entity\Category as CategoryEntity;

/**
 * Repository for data access logic related to category entities
 *
 * @category   EpixaForumBundle
 * @package    Repository
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Category extends EntityRepository
{
    /**
     * Gets the basic query builder for retrieving category entities
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getSelectQueryBuilder()
    {
        return $this->createQueryBuilder('c');
    }

    /**
     * Restricts the given query to only categories that do not match the given category
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param \Epixa\ForumBundle\Entity\Category $category
     * @return void
     */
    public function excludeCategory(QueryBuilder $qb, CategoryEntity $category)
    {
        $qb->andWhere('c.id <> :category_id');
        $qb->setParameter('category_id', $category->getId());
    }
}