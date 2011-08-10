<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Service;

use Doctrine\ORM\NoResultException,
    Epixa\ForumBundle\Entity\Category as CategoryEntity,
    Epixa\ForumBundle\Entity\Topic as TopicEntity,
    Epixa\ForumBundle\Entity\Post as PostEntity,
    Epixa\ForumBundle\Model\NewTopic as NewTopicModel,
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
     * @return \Epixa\ForumBundle\Entity\Topic
     */
    public function get($id)
    {
        $repo = $this->getEntityManager()->getRepository('Epixa\ForumBundle\Entity\Topic');
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
        $repo = $this->getEntityManager()->getRepository('Epixa\ForumBundle\Entity\Topic');
        $qb = $repo->getStandardQueryBuilder();

        $repo->includeLatestPost($qb);
        $repo->restrictToCategory($qb, $category);
        $repo->restrictToPage($qb, $page);

        return $qb->getQuery()->getResult();
    }

    /**
     * Adds the given new topic to the database
     *
     * New topics also include the content for the first post in the topic.
     *
     * @param \Epixa\ForumBundle\Model\NewTopic $newTopic
     * @return \Epixa\ForumBundle\Entity\Topic
     */
    public function add(NewTopicModel $newTopic)
    {
        $topic = new TopicEntity($newTopic->getCategory());
        $topic->setTitle($newTopic->getTitle());

        $post = new PostEntity($topic);
        $post->setContent($newTopic->getContent());

        $em = $this->getEntityManager();

        $em->persist($topic);
        $em->persist($post);
        $em->flush();

        return $topic;
    }

    /**
     * Updates the given topic in the database
     * 
     * @throws \InvalidArgumentException
     * @param \Epixa\ForumBundle\Entity\Topic $topic
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