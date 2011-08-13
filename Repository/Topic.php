<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Repository;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\QueryBuilder,
    Epixa\ForumBundle\Entity\Category as CategoryEntity;

/**
 * Repository for data access logic related to topic entities
 *
 * @category   EpixaForumBundle
 * @package    Repository
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Topic extends EntityRepository
{
    /**
     * Gets the basic query builder for retrieving topic entities
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getStandardQueryBuilder()
    {
        return $this->createQueryBuilder('t');
    }

    /**
     * Restricts the given query to only posts that are associated with the given category
     * 
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param \Epixa\ForumBundle\Entity\Category $category
     * @return void
     */
    public function restrictToCategory(QueryBuilder $qb, CategoryEntity $category)
    {
        $qb->andWhere('t.category = :category');
        $qb->setParameter('category', $category);
    }

    /**
     * Restricts the given query to only posts that fall on the given page
     * 
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param integer $page
     * @param integer $max
     * @return void
     */
    public function restrictToPage(QueryBuilder $qb, $page, $max = 25)
    {
        $qb->setMaxResults($max);
        $qb->setFirstResult($max * ($page - 1));
    }
}