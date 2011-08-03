<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Service;

use Doctrine\ORM\NoResultException;

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

    /**
     * Gets a specific category by its unique identifier
     * 
     * @throws \Doctrine\ORM\NoResultException
     * @param integer $id
     * @return \Epixa\ForumBundle\Entity\Category
     */
    public function get($id)
    {
        $repo = $this->getEntityManager()->getRepository('Epixa\ForumBundle\Entity\Category');
        $category = $repo->find($id);
        if (!$category) {
            throw new NoResultException('That category cannot be found');
        }

        return $category;
    }
}