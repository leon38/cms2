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
 * @ORM\Table(name="widget")
 * @ORM\HasLifecycleCallbacks
 */
class Widget
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
     * @var object field
     *
     * @ORM\Column(name="widget_object",type="object")
     */
    private $widget;
    
    /**
     * @ORM\ManyToOne(targetEntity="CMS\Bundle\CoreBundle\Entity\Sidebar", inversedBy="widgets")
     */
    private $sidebar;
    

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
     * @return Widget
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
     * @return Widget
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
     * Set widget
     *
     * @param \stdClass $widget
     *
     * @return Widget
     */
    public function setWidget($widget)
    {
        $this->widget = $widget;

        return $this;
    }

    /**
     * Get widget
     *
     * @return \stdClass
     */
    public function getWidget()
    {
        return $this->widget;
    }
}
