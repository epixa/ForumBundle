<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Entity;

use Epixa\ForumBundle\Entity\Topic\StandardTopic,
    Doctrine\ORM\Mapping as ORM,
    Symfony\Component\Validator\Constraints as Assert,
    DateTime,
    InvalidArgumentException;

/**
 * A representation of a forum post
 *
 * @category   EpixaForumBundle
 * @package    Entity
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 * 
 * @ORM\Entity(repositoryClass="Epixa\ForumBundle\Repository\Post")
 * @ORM\Table(name="epixa_forum_post")
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(name="comment", type="text")
     * @Assert\NotBlank
     * @Assert\MaxLength("5000")
     * @var string
     */
    protected $comment;

    /**
     * @ORM\Column(name="date_created", type="datetime")
     * @var \DateTime
     */
    protected $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity="Epixa\ForumBundle\Entity\Topic\StandardTopic", inversedBy="posts")
     * @ORM\JoinColumn(name="topic_id", referencedColumnName="id", onDelete="CASCADE")
     * @var Topic\StandardTopic
     */
    protected $topic;


    /**
     * Initializes a new Post
     *
     * The creation date is set to now and the topic is set.
     *
     * @param Topic\StandardTopic $topic
     */
    public function __construct(StandardTopic $topic)
    {
        $this->setDateCreated('now');
        $this->setTopic($topic);
    }
    
    /**
     * Gets the unique identifier of this entity
     * 
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the comment content of this post
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Sets the comment content of this post
     * 
     * @param string $comment
     * @return Post *Fluent interface*
     */
    public function setComment($comment)
    {
        $this->comment = (string)$comment;
        return $this;
    }

    /**
     * Gets the date that this entity was created
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Sets the date this entity was created
     *
     * @throws \InvalidArgumentException
     * @param \DateTime|string|integer $date
     * @return Post *Fluent interface*
     */
    public function setDateCreated($date)
    {
        if (is_string($date)) {
            $date = new DateTime($date);
        } else if (is_int($date)) {
            $date = new DateTime(sprintf('@%d', $date));
        } else if (!$date instanceof DateTime) {
            throw new InvalidArgumentException(sprintf(
                'Expecting string, integer or DateTime, but got `%s`',
                is_object($date) ? get_class($date) : gettype($date)
            ));
        }

        $this->dateCreated = $date;
        return $this;
    }

    /**
     * Gets the topic of this post
     * 
     * @return \Epixa\ForumBundle\Entity\Topic\StandardTopic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Sets the topic of this post
     * 
     * @param \Epixa\ForumBundle\Entity\Topic\StandardTopic $topic
     * @return Post *Fluent interface*
     */
    protected function setTopic(StandardTopic $topic)
    {
        $this->topic = $topic;
        return $this;
    }
}