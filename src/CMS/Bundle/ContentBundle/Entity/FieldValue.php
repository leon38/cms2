<?php
namespace CMS\Bundle\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * FieldValue
 *
 * @ORM\Table(name="fieldsvalues")
 * @ORM\Entity(repositoryClass="CMS\Bundle\ContentBundle\Entity\Repository\FieldValueRepository")
 * @ORM\HasLifecycleCallbacks()
*/
class FieldValue
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Content", inversedBy="fieldvalues", cascade={"persist", "remove"})
     */
    private $content;
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Field", inversedBy="fieldvalues")
     */
    private $field;
    
    /**
     * @var string value
     *
     * @ORM\Column(name="value",type="string")
     */
    private $value;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function serializeFieldValue()
    {
        $this->value = serialize($this->value);
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fieldsvalue = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * Set value
     *
     * @param  string       $value
     * @return CMFieldValue
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
    /**
     * Set content
     *
     * @param  \CMS\ContentBundle\Entity\CMContent $content
     * @return CMFieldValue
     */
    public function setContent(Content $content)
    {
        $this->content = $content;
        $this->content->addFieldvalue($this);
        return $this;
    }
    /**
     * Get content
     *
     * @return \CMS\ContentBundle\Entity\Content
     */
    public function getContent()
    {
        return $this->content;
    }
    /**
     * Set field
     *
     * @param  \CMS\ContentBundle\Entity\Field $field
     * @return FieldValue
     */
    public function setField(Field $field)
    {
        $this->field = $field;
        return $this;
    }
    /**
     * Get field
     *
     * @return \CMS\ContentBundle\Entity\Field
     */
    public function getField()
    {
        return $this->field;
    }
    public function __toString() {
        return (string)$this->value;
    }
}
