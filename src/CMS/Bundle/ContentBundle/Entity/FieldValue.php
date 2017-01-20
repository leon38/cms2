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
     * @var Field $field
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Field", inversedBy="fieldvalues", fetch="EAGER")
     */
    private $field;

    /**
     * @var string value
     *
     * @ORM\Column(name="value",type="text")
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
     * Set value
     *
     * @param  string       $value
     * @return FieldValue
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
     * @param  Content $content
     * @return FieldValue
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
     * @return Content
     */
    public function getContent()
    {
        return $this->content;
    }
    /**
     * Set field
     *
     * @param  Field $field
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
     * @return Field
     */
    public function getField()
    {
        return $this->field;
    }

    public function __toString() {
        return (string)$this->value;
    }
}
