<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Service;

use Doctrine\ORM\NoResultException;
use Epixa\ForumBundle\Entity\Category as CategoryEntity;

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

    /**
     * Gets a page of topics associated with the given category
     * 
     * @param \Epixa\ForumBundle\Entity\Category $category
     * @param int $page
     * @return array
     */
    public function getByCategory(CategoryEntity $category, $page = 1)
    {
        /* @var \Epixa\ForumBundle\Repository\Topic $repo */
        $repo = $this->getEntityManager()->getRepository('Epixa\ForumBundle\Entity\Topic');
        $qb = $repo->getStandardQueryBuilder();

        $repo->includeLatestPost($qb);
        $repo->restrictToCategory($qb, $category);
        $repo->restrictToPage($qb, $page);

        return $qb->getQuery()->getResult();
    }
}