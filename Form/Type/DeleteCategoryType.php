<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilder,
    Epixa\ForumBundle\Model\CategoryDeletionOptions,
    Epixa\ForumBundle\Repository\Category as CategoryRepository,
    Symfony\Component\Form\FormEvents,
    Symfony\Component\Form\Event\DataEvent;

/**
 * Form type for deleting categories
 *
 * @category   EpixaForumBundle
 * @package    Form
 * @subpackage Type
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class DeleteCategoryType extends AbstractType
{
    /**
     * Instructs the construction of forms of this type
     *
     * @param \Symfony\Component\Form\FormBuilder $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        // creates the inheriting select field whenever the data (deletion options model) is set
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(DataEvent $event) use ($builder){
            $data = $event->getData();
            if (!$data instanceof CategoryDeletionOptions) {
                return;
            }

            $event->getForm()->add($builder->create('inheritingCategory', 'entity', array(
                'label' => 'Move all posts to:',
                'class' => 'Epixa\ForumBundle\Entity\Category',
                'query_builder' => function(CategoryRepository $repo) use ($data){
                    $qb = $repo->getSelectQueryBuilder();
                    $repo->excludeCategory($qb, $data->getTargetCategory());
                    return $qb;
                }
            ))->getForm());
        });
    }

    /**
     * Gets the default options to use for this form type
     *
     * @param array $options
     * @return array
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Epixa\ForumBundle\Model\CategoryDeletionOptions'
        );
    }

    /**
     * Gets the unique name of this form type
     *
     * @return string
     */
    public function getName()
    {
        return 'epxia_forum_delete_category';
    }
}