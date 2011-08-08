<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
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
     */
    protected $id;

    /**
     * @ORM\Column(name="content", type="text", nullable = "true")
     */
    protected $content = null;

    /**
     * @ORM\Column(name="date_created", type="datetime")
     */
    protected $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity="Topic", inversedBy="posts")
     * @ORM\JoinColumn(name="topic_id", referencedColumnName="id", onDelete="CASCADE")
     * @var Topic
     */
    protected $topic;


    /**
     * Initializes a new Post
     *
     * The creation date is set to now and the topic is set.
     *
     * @param Topic $topic
     */
    public function __construct(Topic $topic)
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
     * Gets the content of this post
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets the content of this post
     * 
     * @param string $content
     * @return Post *Fluent interface*
     */
    public function setContent($content)
    {
        $this->content = (string)$content;
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
     * @return Topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Sets the topic of this post
     * 
     * @param Topic $topic
     * @return Post *Fluent interface*
     */
    protected function setTopic(Topic $topic)
    {
        $this->topic = $topic;
        return $this;
    }
}