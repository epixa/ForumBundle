<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Entity\Topic;

use Epixa\ForumBundle\Entity\Category,
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Component\Validator\Constraints as Assert,
    DateTime,
    InvalidArgumentException;

/**
 * A representation of a standard forum topic
 *
 * @category   EpixaForumBundle
 * @package    Entity
 * @subpackage Topic
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 *
 * @ORM\Entity(repositoryClass="Epixa\ForumBundle\Repository\Topic")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 *  "standard" = "StandardTopic"
 * })
 * @ORM\Table(name="epixa_forum_topic")
 */
class StandardTopic
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(name="title", type="string")
     * @Assert\NotBlank()
     * @Assert\MaxLength("255")
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(name="comment", type="text")
     * @Assert\MaxLength("5000")
     * @var null|string
     */
    protected $comment = null;

    /**
     * @ORM\Column(name="total_posts", type="integer")
     * @var int
     */
    protected $totalPosts = 0;

    /**
     * @ORM\Column(name="date_created", type="datetime")
     * @var \DateTime
     */
    protected $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity="Epixa\ForumBundle\Entity\Category", inversedBy="topics")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     * @var \Epixa\ForumBundle\Entity\Category
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="Epixa\ForumBundle\Entity\Post", mappedBy="topic")
     * @var \Epixa\ForumBundle\Entity\Post[]
     */
    protected $posts;


    /**
     * Initializes a new Topic
     *
     * The creation date is set to now, the posts collection is initialized, and the category is set.
     * 
     * @param \Epixa\ForumBundle\Entity\Category $category
     */
    public function __construct(Category $category)
    {
        $this->setDateCreated('now');
        $this->setCategory($category);
        $this->posts = new ArrayCollection();
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
     * Gets the topic title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the topic title
     *
     * @param string $title
     * @return StandardTopic *Fluent interface*
     */
    public function setTitle($title)
    {
        $this->title = (string)$title;
        return $this;
    }

    /**
     * Gets the comment content associated with this topic
     * 
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Sets the comment content associated with this topic
     * 
     * @param string $comment
     * @return StandardTopic *Fluent interface*
     */
    public function setComment($comment)
    {
        $comment = trim((string)$comment);
        if ($comment == '') {
            $comment = null;
        }

        $this->comment = $comment;
        return $this;
    }

    /**
     * Gets the total posts associated with this topic
     *
     * @return integer
     */
    public function getTotalPosts()
    {
        return $this->totalPosts;
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
     * @return StandardTopic *Fluent interface*
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
     * Gets the category of this topic
     *
     * @return \Epixa\ForumBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Sets the category of this topic
     * 
     * @param \Epixa\ForumBundle\Entity\Category $category
     * @return StandardTopic *Fluent interface*
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
        return $this;
    }
}