<?php
/**
 * User: DCA
 * Date: 05/08/2016
 * Time: 11:58
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="CMS\Bundle\ContentBundle\Entity\Repository\FieldRepository")
 * @ORM\Table(name="sidebar")
 * @ORM\HasLifecycleCallbacks
 */
class Sidebar
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string $title
     * @ORM\Column(name="title", length=100)
     */
    private $title;
    
    /**
     * @var string $name
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;
    
    /**
     * @ORM\OneToMany(targetEntity="CMS\Bundle\CoreBundle\Entity\Widget", mappedBy="sidebar")
     */
    private $widgets;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->widgets = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     *
     * @return Sidebar
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
     * Set name
     *
     * @param string $name
     *
     * @return Sidebar
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add widget
     *
     * @param \CMS\Bundle\CoreBundle\Entity\Widget $widget
     *
     * @return Sidebar
     */
    public function addWidget(\CMS\Bundle\CoreBundle\Entity\Widget $widget)
    {
        $this->widgets[] = $widget;

        return $this;
    }

    /**
     * Remove widget
     *
     * @param \CMS\Bundle\CoreBundle\Entity\Widget $widget
     */
    public function removeWidget(\CMS\Bundle\CoreBundle\Entity\Widget $widget)
    {
        $this->widgets->removeElement($widget);
    }

    /**
     * Get widgets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWidgets()
    {
        return $this->widgets;
    }
}
