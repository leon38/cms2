<?php
/**
 * User: DCA
 * Date: 05/08/2016
 * Time: 11:58
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Entity;

use Doctrine\Common\NotifyPropertyChanged;
use Doctrine\Common\PropertyChangedListener;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ChangeTrackingPolicy;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="CMS\Bundle\CoreBundle\Entity\Repository\WidgetRepository")
 * @ORM\Table(name="widget")
 * @ORM\HasLifecycleCallbacks
 * @ChangeTrackingPolicy("NOTIFY")
 */
class Widget implements NotifyPropertyChanged
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
    
    private $_listeners = array();
    
    public function addPropertyChangedListener(PropertyChangedListener $listener)
    {
        $this->_listeners[] = $listener;
    }
    
    protected function _onPropertyChanged($propName, $oldValue, $newValue)
    {
        if ($this->_listeners) {
            foreach ($this->_listeners as $listener) {
                $listener->propertyChanged($this, $propName, $oldValue, $newValue);
            }
        }
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
        $this->_onPropertyChanged('widget', $this->widget, $widget);

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

    /**
     * Set sidebar
     *
     * @param \CMS\Bundle\CoreBundle\Entity\Sidebar $sidebar
     *
     * @return Widget
     */
    public function setSidebar(\CMS\Bundle\CoreBundle\Entity\Sidebar $sidebar = null)
    {
        $this->sidebar = $sidebar;

        return $this;
    }

    /**
     * Get sidebar
     *
     * @return \CMS\Bundle\CoreBundle\Entity\Sidebar
     */
    public function getSidebar()
    {
        return $this->sidebar;
    }
    
    public function __get($name)
    {
        $widgetObj = $this->getWidget();
        if(is_object($widgetObj)) {
            $options = $widgetObj->getOptions();
            foreach ($options as $key => $option) {
                if ($key == $name) {
                    return $option;
                }
            }
        }
    }
    
    public function __set($name, $value)
    {
        $widgetObj = $this->getWidget();
        if(is_array($widgetObj->getOptions())) {
            $options = $widgetObj->getOptions();
            foreach ($options as $key => $option) {
                if ($key == $name) {
                    $option[$key]['value'] = $value;
                    return $this;
                }
            }
        }
    }
}
