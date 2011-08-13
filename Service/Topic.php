<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Service;

use Doctrine\ORM\NoResultException,
    Epixa\ForumBundle\Entity\Category as CategoryEntity,
    Epixa\ForumBundle\Entity\Topic\StandardTopic as TopicEntity,
    Epixa\ForumBundle\Entity\Post as PostEntity,
    InvalidArgumentException;

/**
 * Service for managing forum Topics
 *
 * @category   EpixaForumBundle
 * @package    Service
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Topic extends AbstractDoctrineService
{
    /**
     * Gets a specific topic by its unique identifier
     *
     * @throws \Doctrine\ORM\NoResultException
     * @param integer $id
     * @return \Epixa\ForumBundle\Entity\Topic\StandardTopic
     */
    public function get($id)
    {
        $repo = $this->getEntityManager()->getRepository('Epixa\ForumBundle\Entity\Topic\StandardTopic');
        $topic = $repo->find($id);
        if (!$topic) {
            throw new NoResultException('That topic cannot be found');
        }

        return $topic;
    }

    /**
     * Gets a page of topics associated with the given category
     * 
     * @param \Epixa\ForumBundle\Entity\Category $category
     * @param int $page
     * @return array
     */
    public function getByCategory(CategoryEntity $category, $page = 1)
    {
        /* @var \Epixa\ForumBundle\Repository\Topic $repo */
        $repo = $this->getEntityManager()->getRepository('Epixa\ForumBundle\Entity\Topic\StandardTopic');
        $qb = $repo->getStandardQueryBuilder();

        $repo->restrictToCategory($qb, $category);
        $repo->restrictToPage($qb, $page);

        return $qb->getQuery()->getResult();
    }

    /**
     * Updates topic stats for the given topic
     *
     * @param \Epixa\ForumBundle\Entity\Topic\StandardTopic $topic
     * @return void
     */
    public function updateNewTopicStats(TopicEntity $topic)
    {
        /* @var \Doctrine\DBAL\Connection $db */
        $db = $this->getEntityManager()->getConnection();
        $sql = sprintf(
            'update epixa_forum_category
             set total_topics = total_topics + 1
             where id = %s',
            $db->quote($topic->getCategory()->getId())
        );

        $db->exec($sql);
    }

    /**
     * Adds the given new topic to the database
     *
     * @param \Epixa\ForumBundle\Entity\Topic\StandardTopic $topic
     * @return \Epixa\ForumBundle\Entity\Topic\StandardTopic
     */
    public function add(TopicEntity $topic)
    {
        $em = $this->getEntityManager();
        $em->persist($topic);
        $em->flush();

        return $topic;
    }

    /**
     * Updates the given topic in the database
     * 
     * @throws \InvalidArgumentException
     * @param \Epixa\ForumBundle\Entity\Topic\StandardTopic $topic
     * @return void
     */
    public function update(TopicEntity $topic)
    {
        $em = $this->getEntityManager();
        if (!$em->contains($topic)) {
            throw new InvalidArgumentException('Topic is not managed');
        }

        $em->flush();
    }
}