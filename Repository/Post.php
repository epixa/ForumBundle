<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Repository;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\QueryBuilder,
    Epixa\ForumBundle\Entity\Topic\StandardTopic as TopicEntity;

/**
 * Repository for data access logic related to post entities
 *
 * @category   EpixaForumBundle
 * @package    Repository
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Post extends EntityRepository
{
    /**
     * Gets the basic query builder for retrieving post entities
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getStandardQueryBuilder()
    {
        return $this->createQueryBuilder('p');
    }

    /**
     * Restricts the given query to only posts that are associated with the given topic
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param \Epixa\ForumBundle\Entity\Topic $topic
     * @return void
     */
    public function restrictToTopic(QueryBuilder $qb, TopicEntity $topic)
    {
        $qb->andWhere('p.topic = :topic');
        $qb->setParameter('topic', $topic);
    }

    /**
     * Restricts the given query to only posts that fall on the given page
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param integer $page
     * @param integer $max
     * @return void
     */
    public function restrictToPage(QueryBuilder $qb, $page, $max = 50)
    {
        $qb->setMaxResults($max);
        $qb->setFirstResult($max * ($page - 1));
    }
}