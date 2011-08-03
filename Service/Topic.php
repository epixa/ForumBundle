<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Service;

use Doctrine\ORM\NoResultException;

/**
 * Service for managing forum Topics
 *
 * @category   EpixaForumBundle
 * @package    Service
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class Topic extends AbstractDoctrineService
{
    /**
     * Gets a specific topic by its unique identifier
     *
     * @throws \Doctrine\ORM\NoResultException
     * @param integer $id
     * @return \Epixa\ForumBundle\Entity\Topic
     */
    public function get($id)
    {
        $repo = $this->getEntityManager()->getRepository('Epixa\ForumBundle\Entity\Topic');
        $topic = $repo->find($id);
        if (!$topic) {
            throw new NoResultException('That topic cannot be found');
        }

        return $topic;
    }
}