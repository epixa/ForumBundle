<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Service;

use Doctrine\ORM\NoResultException,
    Epixa\ForumBundle\Entity\Post as PostEntity,
    Epixa\ForumBundle\Entity\Topic\StandardTopic as TopicEntity,
    InvalidArgumentException;

/**
 * Service for managing forum Posts
 *
 * @category   EpixaForumBundle
 * @package    Service
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Post extends AbstractDoctrineService
{
    /**
     * Gets a specific post by its unique identifier
     *
     * @throws \Doctrine\ORM\NoResultException
     * @param integer $id
     * @return \Epixa\ForumBundle\Entity\Post
     */
    public function get($id)
    {
        $repo = $this->getEntityManager()->getRepository('Epixa\ForumBundle\Entity\Post');
        $post = $repo->find($id);
        if (!$post) {
            throw new NoResultException('That post cannot be found');
        }

        return $post;
    }

    /**
     * Updates post stats for the given new post
     *
     * @param \Epixa\ForumBundle\Entity\Post $post
     * @return void
     */
    public function updateNewPostStats(PostEntity $post)
    {
        /* @var \Doctrine\DBAL\Connection $db */
        $db = $this->getEntityManager()->getConnection();
        $sql = sprintf(
            'update epixa_forum_topic
             set total_posts = total_posts + 1
             where id = %s',
            $db->quote($post->getTopic()->getId())
        );

        $db->exec($sql);
    }

    /**
     * Updates post stats for the given deleted post
     * 
     * @param \Epixa\ForumBundle\Entity\Post $post
     * @return void
     */
    public function updateRemovedPostStats(PostEntity $post)
    {
        /* @var \Doctrine\DBAL\Connection $db */
        $db = $this->getEntityManager()->getConnection();
        $sql = sprintf(
            'update epixa_forum_topic
             set total_posts = total_posts - 1
             where id = %s',
            $db->quote($post->getTopic()->getId())
        );

        $db->exec($sql);
    }

    /**
     * Gets a page of posts associated with the given topic
     * 
     * @param \Epixa\ForumBundle\Entity\Topic\StandardTopic $topic
     * @param int $page
     * @return array
     */
    public function getByTopic(TopicEntity $topic, $page = null)
    {
        /* @var \Epixa\ForumBundle\Repository\Post $repo */
        $repo = $this->getEntityManager()->getRepository('Epixa\ForumBundle\Entity\Post');
        $qb = $repo->getSelectQueryBuilder();

        $repo->restrictToTopic($qb, $topic);

        if ($page !== null) {
            $repo->restrictToPage($qb, $page);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Adds the given post to the database
     *
     * @param \Epixa\ForumBundle\Entity\Post $post
     * @return void
     */
    public function add(PostEntity $post)
    {
        $em = $this->getEntityManager();

        $em->persist($post);
        $em->flush();
    }

    /**
     * Updates the given post in the database
     *
     * @throws \InvalidArgumentException
     * @param \Epixa\ForumBundle\Entity\Post $post
     * @return void
     */
    public function update(PostEntity $post)
    {
        $em = $this->getEntityManager();
        if (!$em->contains($post)) {
            throw new InvalidArgumentException('Post is not managed');
        }

        $em->flush();
    }

    /**
     * Deletes the given post from the database
     * 
     * @param \Epixa\ForumBundle\Entity\Post $post
     * @return void
     */
    public function delete(PostEntity $post)
    {
        $em = $this->getEntityManager();
        $em->remove($post);
        $em->flush();
    }
}