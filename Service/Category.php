<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Service;

use Doctrine\ORM\NoResultException,
    Epixa\ForumBundle\Entity\Category as CategoryEntity,
    Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList,
    Epixa\ForumBundle\Model\CategoryDeletionOptions;

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
     * @return \Epixa\ForumBundle\Entity\Category[]
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

    /**
     * Adds the given category to the database
     * 
     * @param \Epixa\ForumBundle\Entity\Category $category
     * @return void
     */
    public function add(CategoryEntity $category)
    {
        $em = $this->getEntityManager();

        $em->persist($category);
        $em->flush();
    }

    /**
     * Updates the given category in the database
     *
     * @throws \InvalidArgumentException
     * @param \Epixa\ForumBundle\Entity\Category $category
     * @return void
     */
    public function update(CategoryEntity $category)
    {
        $em = $this->getEntityManager();
        if (!$em->contains($category)) {
            throw new \InvalidArgumentException('Category is not managed');
        }

        $em->flush();
    }

    /**
     * Deletes the given category from the database
     *
     * All topics associated with the deleted category are moved to an "inheriting" category that is defined
     * in the deletion options object.
     *
     * @throws \InvalidArgumentException|\RuntimeException
     * @param \Epixa\ForumBundle\Model\CategoryDeletionOptions $options
     * @return void
     */
    public function delete(CategoryDeletionOptions $options)
    {
        $inheritingCategory = $options->getInheritingCategory();
        $targetCategory = $options->getTargetCategory();
        
        /* @var \Doctrine\DBAL\Connection $db */
        $em = $this->getEntityManager();
        $db = $em->getConnection();

        $moveTopicsSql = sprintf(
            'update epixa_forum_topic
             set category_id = %s
             where category_id = %s',
            $db->quote($inheritingCategory->getId()),
            $db->quote($targetCategory->getId())
        );

        $topicCountSql = sprintf(
            'update epixa_forum_category nc, epixa_forum_category oc
             set nc.total_topics = nc.total_topics + oc.total_topics
             where nc.id = %s and oc.id = %s',
            $db->quote($inheritingCategory->getId()),
            $db->quote($targetCategory->getId())
        );

        $db->beginTransaction();
        try {
            $db->exec($topicCountSql);
            $db->exec($moveTopicsSql);
            $em->remove($targetCategory);
            $em->flush();

            $db->commit();
        } catch (\Exception $e) {
            $db->rollback();
            throw new \RuntimeException('Transaction failed', null, $e);
        }
    }

    /**
     * Gets a choice list of categories
     *
     * If a category is provided, it is not included in the returned by the choice list.
     *
     * @param \Epixa\ForumBundle\Entity\Category|null $excludedCategory
     * @return \Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList
     */
    public function getCategoryChoiceList(CategoryEntity $excludedCategory = null)
    {
        /* @var \Epixa\ForumBundle\Repository\Category $repo */
        $em = $this->getEntityManager();
        $repo = $em->getRepository('Epixa\ForumBundle\Entity\Category');
        $qb = $repo->getSelectQueryBuilder();
        $repo->excludeCategory($qb, $excludedCategory);


        return new EntityChoiceList($em, 'Epixa\ForumBundle\Entity\Category', null, $qb);
    }
}