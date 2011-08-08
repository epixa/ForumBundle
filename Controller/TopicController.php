<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Controller managing forum topics
 * 
 * @category   EpixaForumBundle
 * @package    Controller
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class TopicController extends Controller
{
    /**
     * Shows a specific topic including paginated associated posts
     * 
     * @Route("/{id}", requirements={"id"="\d+"}, name="view_topic")
     * @Route("/{id}/{page}", requirements={"page"="\d+"}, name="view_topic_page")
     * @Template()
     *
     * @param integer $id   The unique identifier of the requested topic
     * @param integer $page The page of posts to display for this topic
     */
    public function viewAction($id, $page = 1)
    {
        $topic = $this->getTopicService()->get($id);

        return array(
            'topic' => $topic,
            'posts' => $this->getPostService()->getByTopic($topic, $page),
            'page' => $page
        );
    }

    /**
     * Gets the topic service
     *
     * @return \Epixa\ForumBundle\Service\Topic
     */
    public function getTopicService()
    {
        return $this->get('epixa_forum.service.topic');
    }

    /**
     * Gets the post service
     *
     * @return \Epixa\ForumBundle\Service\Post
     */
    public function getPostService()
    {
        return $this->get('epixa_forum.service.post');
    }
}
