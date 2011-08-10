<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Symfony\Component\HttpFoundation\Request,
    Epixa\ForumBundle\Entity\Post as PostEntity,
    Epixa\ForumBundle\Form\Type\PostType;

/**
 * Controller managing forum posts
 *
 * @category   EpixaForumBundle
 * @package    Controller
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class PostController extends Controller
{
    /**
     * @Route("/add/{topicId}", requirements={"topicId"="\d+"}, name="add_post")
     * @Template()
     *
     * @param integer $topicId
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function addAction($topicId, Request $request)
    {
        $topic = $this->getTopicService()->get($topicId);

        $post = new PostEntity($topic);

        $form = $this->createForm(new PostType(), $post);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $this->getPostService()->add($post);

                $this->get('session')->setFlash('notice', 'Post created');
                $baseUrl = $this->generateUrl('view_topic', array('id' => $topic->getId()));
                return $this->redirect($baseUrl . '#post-' . $post->getId());
            }
        }

        return array(
            'form' => $form->createView(),
            'topic' => $topic
        );
    }

    /**
     * @Route("/edit/{id}", requirements={"id"="\d+"}, name="edit_post")
     * @Template()
     *
     * @param integer $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function editAction($id, Request $request)
    {
        $service = $this->getPostService();
        $post = $service->get($id);

        $form = $this->createForm(new PostType(), $post);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $service->update($post);

                $this->get('session')->setFlash('notice', 'Post updated');
                $baseUrl = $this->generateUrl('view_topic', array('id' => $post->getTopic()->getId()));
                return $this->redirect($baseUrl . '#post-' . $post->getId());
            }
        }

        return array(
            'form' => $form->createView(),
            'post' => $post
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
