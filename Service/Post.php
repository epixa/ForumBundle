<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Service;

use Epixa\ForumBundle\Entity\Post as PostEntity;

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
}