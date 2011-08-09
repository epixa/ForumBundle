<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Event;

use Doctrine\Common\EventSubscriber,
    Doctrine\ORM\Event\LifecycleEventArgs,
    Epixa\ForumBundle\Service\Post as PostService;

/**
 * Manages the post statistics whenever a post is added, updated, or deleted.
 *
 * This does have a negative impact on performance as it requires additional
 * queries to be executed during a database transaction.
 *
 * @category   EpixaForumBundle
 * @package    Controller
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class PostStatsSubscriber implements EventSubscriber
{
    /**
     * @var \Epixa\ForumBundle\Service\Post|null
     */
    protected $postService = null;


    /**
     * Sets the post service
     * 
     * @param \Epixa\ForumBundle\Service\Post $service
     * @return PostStatsSubscriber *Fluent interface*
     */
    public function setPostService(PostService $service)
    {
        $this->postService = $service;
        return $this;
    }

    /**
     * Gets the post service
     *
     * @throws \RuntimeException
     * @return \Epixa\ForumBundle\Service\Post
     */
    public function getPostService()
    {
        if ($this->postService === null) {
            throw new \RuntimeException('No post service set');
        }
        
        return $this->postService;
    }
    
    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'postPersist'
        );
    }

    /**
     * Updates the post stats on forum topics whenever a new post is created
     *
     * Executes following the insert of a new post in a unit of work
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     * @return void
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if ($entity instanceof \Epixa\ForumBundle\Entity\Post) {
            $service = $this->getPostService();

            // If the service container inject the entity manager
            $service->setEntityManager($eventArgs->getEntityManager());
            $service->updateNewPostStats($entity);
        }
    }
}