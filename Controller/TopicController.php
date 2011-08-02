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
     * Shows a specific topic including all associated posts
     * 
     * @Route("/{id}", requirements={"id"="\d+"})
     * @Template()
     */
    public function viewAction($id)
    {
        return array('id' => $id);
    }
}
