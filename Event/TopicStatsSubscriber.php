<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Event;

use Doctrine\Common\EventSubscriber,
    Doctrine\ORM\Event\LifecycleEventArgs,
    Epixa\ForumBundle\Service\Topic as TopicService;

/**
 * Manages the topics statistics whenever a topic is added, updated, or deleted.
 *
 * This does have a negative impact on performance as it requires additional
 * queries to be executed during a database transaction.
 *
 * @category   EpixaForumBundle
 * @package    Event
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class TopicStatsSubscriber implements EventSubscriber
{
    /**
     * @var \Epixa\ForumBundle\Service\Topic|null
     */
    protected $topicService = null;


    /**
     * Sets the topic service
     *
     * @param \Epixa\ForumBundle\Service\Topic $service
     * @return TopicStatsSubscriber *Fluent interface*
     */
    public function setTopicService(TopicService $service)
    {
        $this->topicService = $service;
        return $this;
    }

    /**
     * Gets the topic service
     *
     * @throws \RuntimeException
     * @return \Epixa\ForumBundle\Service\Topic
     */
    public function getTopicService()
    {
        if ($this->topicService === null) {
            throw new \RuntimeException('No topic service set');
        }

        return $this->topicService;
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postRemove'
        );
    }

    /**
     * Updates the topic stats whenever a new topic is created
     *
     * Executes following the insert of a new topic in a unit of work
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     * @return void
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if ($entity instanceof \Epixa\ForumBundle\Entity\Topic\StandardTopic) {
            $service = $this->getTopicService();

            // If the service container inject the entity manager
            $service->setEntityManager($eventArgs->getEntityManager());
            $service->updateNewTopicStats($entity);
        }
    }

    /**
     * Updates the topic stats whenever an existing topic is deleted
     *
     * Executes following the deletion of an existing topic in a unit of work
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     * @return void
     */
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if ($entity instanceof \Epixa\ForumBundle\Entity\Topic\StandardTopic) {
            $service = $this->getTopicService();

            // If the service container inject the entity manager
            $service->setEntityManager($eventArgs->getEntityManager());
            $service->updateRemovedTopicStats($entity);
        }
    }
}