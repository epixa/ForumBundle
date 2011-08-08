<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Service;

use Epixa\ForumBundle\Entity\Post as PostEntity,
    Epixa\ForumBundle\Entity\Topic as TopicEntity;

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
     * Updates post stats for the given post
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
             set total_posts = total_posts + 1,
                 latest_post_id = %s
             where id = %s',
            $db->quote($post->getId()),
            $db->quote($post->getTopic()->getId())
        );

        $db->exec($sql);
    }

    /**
     * Gets a page of posts associated with the given topic
     * 
     * @param \Epixa\ForumBundle\Entity\Topic $topic
     * @param int $page
     * @return array
     */
    public function getByTopic(TopicEntity $topic, $page = 1)
    {
        /* @var \Epixa\ForumBundle\Repository\Post $repo */
        $repo = $this->getEntityManager()->getRepository('Epixa\ForumBundle\Entity\Post');
        $qb = $repo->getStandardQueryBuilder();

        $repo->restrictToTopic($qb, $topic);
        $repo->restrictToPage($qb, $page);

        return $qb->getQuery()->getResult();
    }
}