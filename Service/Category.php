<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Service;

/**
 * Service for managing forum Categories
 *
 * @category   EpixaForumBundle
 * @package    Service
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Category extends AbstractDoctrineService
{
    /**
     * Gets all categories in the system
     * 
     * @return array
     */
    public function getAll()
    {
        $repo = $this->getEntityManager()->getRepository('Epixa\ForumBundle\Entity\Category');
        return $repo->findAll();
    }
}