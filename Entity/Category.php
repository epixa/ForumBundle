<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Component\Validator\Constraints as Assert,
    DateTime,
    InvalidArgumentException;

/**
 * A representation of a forum category
 *
 * @category   EpixaForumBundle
 * @package    Entity
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 *
 * @ORM\Entity(repositoryClass="Epixa\ForumBundle\Repository\Category")
 * @ORM\Table(name="epixa_forum_category")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length="255")
     * @Assert\NotBlank()
     * @Assert\MinLength("4")
     * @Assert\MaxLength("255")
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(name="total_topics", type="integer")
     * @var int
     */
    protected $totalTopics = 0;

    /**
     * @ORM\Column(name="date_created", type="datetime")
     * @var \DateTime
     */
    protected $dateCreated;

    /**
     * @ORM\OneToMany(targetEntity="Epixa\ForumBundle\Entity\Topic\StandardTopic", mappedBy="category")
     * @var Topic\StandardTopic[]
     */
    protected $topics;


    /**
     * Initializes a new Category
     *
     * The creation date is set to now and the topics collection is initialized.
     */
    public function __construct()
    {
        $this->setDateCreated('now');
        $this->topics = new ArrayCollection();
    }

    /**
     * Gets the unique identifier for this entity
     * 
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the category name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the category name
     *
     * @param string $name
     * @return Category *Fluent interface*
     */
    public function setName($name)
    {
        $this->name = (string)$name;
        return $this;
    }

    /**
     * Gets the total topics associated with this category
     *
     * @return integer
     */
    public function getTotalTopics()
    {
        return $this->totalTopics;
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
     * @return Category *Fluent interface*
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
     * Converts the category to a string
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}