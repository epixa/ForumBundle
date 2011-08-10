<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Symfony\Component\HttpFoundation\Request,
    Epixa\ForumBundle\Model\NewTopic as NewTopicModel,
    Epixa\ForumBundle\Form\Type\TopicType,
    Epixa\ForumBundle\Form\Type\NewTopicType;

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
     * @Route("/{id}/{page}", requirements={"id"="\d+", "page"="\d+"}, name="view_topic_page")
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
     * @Route("/add/{categoryId}", requirements={"categoryId"="\d+"}, name="add_topic")
     * @Template()
     *
     * @param integer $categoryId
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function addAction($categoryId, Request $request)
    {
        $category = $this->getCategoryService()->get($categoryId);
        
        $newTopic = new NewTopicModel($category);

        $form = $this->createForm(new NewTopicType(), $newTopic);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $topic = $this->getTopicService()->add($newTopic);

                $this->get('session')->setFlash('notice', 'Topic created');
                return $this->redirect($this->generateUrl('view_topic', array('id' => $topic->getId())));
            }
        }

        return array(
            'form' => $form->createView(),
            'category' => $category
        );
    }

    /**
     * @Route("/edit/{id}", requirements={"id"="\d+"}, name="edit_topic")
     * @Template()
     *
     * @param integer $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function editAction($id, Request $request)
    {
        $service = $this->getTopicService();
        $topic = $service->get($id);

        $form = $this->createForm(new TopicType(), $topic);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $service->update($topic);

                $this->get('session')->setFlash('notice', 'Topic updated');
                return $this->redirect($this->generateUrl('view_topic', array('id' => $topic->getId())));
            }
        }

        return array(
            'form' => $form->createView(),
            'topic' => $topic
        );
    }

    /**
     * Gets the category service
     *
     * @return \Epixa\ForumBundle\Service\Category
     */
    public function getCategoryService()
    {
        return $this->get('epixa_forum.service.category');
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
