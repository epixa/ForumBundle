<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
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
 * @ORM\Entity
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
     * @ORM\Column(name="name", type="string")
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(name="date_created", type="datetime")
     * @var \DateTime
     */
    protected $dateCreated;


    /**
     * Initializes a new Category
     *
     * The creation date is set to now.
     */
    public function __construct()
    {
        $this->dateCreated = new DateTime();
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
}