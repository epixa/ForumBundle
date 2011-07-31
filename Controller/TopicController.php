<?php

namespace Epixa\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class TopicController extends Controller
{
    /**
     * @Route("/{id}", requirements={"id"="\d+"})
     * @Template()
     */
    public function viewAction($id)
    {
        return array('id' => $id);
    }
}
