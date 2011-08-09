<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Model;

use Epixa\ForumBundle\Entity\Topic as TopicEntity,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * Model representing a new topic
 *
 * @category   EpixaForumBundle
 * @package    Model
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class NewTopic extends TopicEntity
{
    /**
     * @Assert\NotBlank()
     * @Assert\MinLength("10")
     * @Assert\MaxLength("255")
     * @var null|string
     */
    protected $content = null;


    /**
     * Sets the new topic's content
     * 
     * @param string $content
     * @return NewTopic *Fluent interface*
     */
    public function setContent($content)
    {
        $this->content = (string)$content;
        return $this;
    }

    /**
     * Gets the new topic's content
     * 
     * @return string|null
     */
    public function getContent()
    {
        return $this->content;
    }
}