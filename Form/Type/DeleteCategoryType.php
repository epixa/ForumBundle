<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilder,
    Epixa\ForumBundle\Model\CategoryDeletionOptions;

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
        if (!isset($options['data']) || !($options['data'] instanceof CategoryDeletionOptions)) {
            throw new \LogicException('No valid options provided');
        }

        /* @var \Epixa\ForumBundle\Model\CategoryDeletionOptions $deletionOptions */
        $deletionOptions = $options['data'];

        $builder->add('inheritingCategoryId', 'choice', array(
            'label' => 'Move all posts to:',
            'choice_list' => $deletionOptions->getInheritingCategoryChoices()
        ));
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