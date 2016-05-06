<?php
namespace CMS\Bundle\DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="widgetentity")
 * @ORM\HasLifecycleCallbacks
 */
class WidgetEntity {

	/**
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id()
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=120)
	 */
	protected $title;

	/**
	 * @ORM\Column(type="text")
	 */
	protected $description;

	/**
	 * @ORM\Column(type="array")
	 */
	protected $args;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $position;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $updated;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $added;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return WidgetEntity
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return WidgetEntity
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set args
     *
     * @param array $args
     * @return WidgetEntity
     */
    public function setArgs($args)
    {
        $this->args = $args;

        return $this;
    }

    /**
     * Get args
     *
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return WidgetEntity
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set added
     *
     * @param \DateTime $added
     * @return WidgetEntity
     */
    public function setAdded($added)
    {
        $this->added = $added;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setUpdatedValue()
    {
    	$this->updated = new \DateTime('now');
    }

    /**
     * @ORM\PrePersist()
     */
    public function setAddedValue()
    {
    	$this->added = new \DateTime('now');
    }

    /**
     * Get added
     *
     * @return \DateTime
     */
    public function getAdded()
    {
        return $this->added;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return WidgetEntity
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }
}
