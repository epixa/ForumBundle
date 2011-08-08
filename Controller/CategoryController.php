<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Controller managing forum categories
 *
 * @category   EpixaForumBundle
 * @package    Controller
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class CategoryController extends Controller
{
    /**
     * Shows the index of call categories
     * 
     * @Template()
     */
    public function indexAction()
    {
        $categories = $this->getCategoryService()->getAll();
        
        return array(
            'categories' => $categories
        );
    }

    /**
     * Shows the topics in a specific category
     * 
     * @Route("/{id}", requirements={"id"="\d+"}, name="view_category")
     * @Route("/{id}/{page}", requirements={"page"="\d+"}, name="view_category_page")
     * @Template()
     *
     * @param integer $id   The unique identifier of the requested category
     * @param integer $page The page of topics to display for this category
     */
    public function viewAction($id, $page = 1)
    {
        $category = $this->getCategoryService()->get($id);

        return array(
            'category' => $category,
            'topics' => $this->getTopicService()->getByCategory($category, $page),
            'page' => $page
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
}
