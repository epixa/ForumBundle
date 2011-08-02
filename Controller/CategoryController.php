<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Epixa\ForumBundle\Service\Category;

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
        /* @var $categoryService \Epixa\ForumBundle\Service\Category */
        $categoryService = $this->get('epixa_forum.service.category');
        $categories = $categoryService->getAll();
        
        return array(
            'categories' => $categories
        );
    }

    /**
     * Shows the topics in a specific category
     * 
     * @Route("/{id}", requirements={"id"="\d+"}, name="view_category")
     * @Template()
     */
    public function viewAction($id)
    {
        return array('id' => $id);
    }
}
