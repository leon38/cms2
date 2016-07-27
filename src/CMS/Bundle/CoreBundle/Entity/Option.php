<?php
namespace CMS\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CMS\Bundle\CoreBundle\Entity\Repository\OptionRepository")
 * @ORM\Table(name="options")
 */
class Option
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="option_name", type="string", length=64, unique=true)
     */
    private $option_name;
    
    /**
     * @ORM\Column(name="option_value", type="text")
     */
    private $option_value;
    
    /**
     * @ORM\Column(name="type", type="string")
     */
    private $type = "text";
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="general", type="boolean")
     */
    private $general;
    
    
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
     * Set option_name
     *
     * @param string $optionName
     * @return Option
     */
    public function setOptionName($optionName)
    {
        $this->option_name = $optionName;
        
        return $this;
    }
    
    /**
     * Get option_name
     *
     * @return string
     */
    public function getOptionName()
    {
        return $this->option_name;
    }
    
    /**
     * Set option_value
     *
     * @param string $optionValue
     * @return Option
     */
    public function setOptionValue($optionValue)
    {
        $this->option_value = $optionValue;
        
        return $this;
    }
    
    /**
     * Get option_value
     *
     * @return string
     */
    public function getOptionValue()
    {
        return $this->option_value;
    }
    
    public function set($option_name, $option_value)
    {
        $this->option_name = $option_name;
        $this->option_value = $option_value;
    }
    
    /**
     * Set general
     *
     * @param boolean $general
     * @return Option
     */
    public function setGeneral($general)
    {
        $this->general = $general;
        
        return $this;
    }
    
    /**
     * Get general
     *
     * @return boolean
     */
    public function getGeneral()
    {
        return $this->general;
    }
    
    public function __toString()
    {
        return $this->option_value;
    }
    
    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Option
     */
    public function setType($type)
    {
        $this->type = $type;
        
        return $this;
    }
    
    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }
}
