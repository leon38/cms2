<?php
namespace CMS\Bundle\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="field")
 */
class Field
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string type
     *
     * @ORM\Column(name="type",type="string")
     */
    private $type;

    /**
     * @var object field
     *
     * @ORM\Column(name="field_object",type="object")
     */
    private $field;

    /**
     * @var string title
     *
     * @ORM\Column(name="title",type="string")
     */
    private $title;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
     private $name;

    /**
     * @var boolean published
     *
     * @ORM\Column(name="published",type="boolean")
     */
    private $published;

    /**
     * @var \DateTime $created
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToMany(targetEntity="ContentTaxonomy", inversedBy="fields")
     * @ORM\JoinTable(name="fields_taxonomy")
     */
    private $contentTaxonomy;

    /**
     * @ORM\OneToMany(targetEntity="FieldValue", mappedBy="field", cascade={"remove", "persist"})
     */
    private $fieldvalues;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contentTaxonomy = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set type
     *
     * @param string $type
     * @return Field
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set field
     *
     * @param \stdClass $field
     * @return Field
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return \stdClass
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Field
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
     * @return Field
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
     * Set published
     *
     * @param boolean $published
     * @return Field
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Field
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Add contentTaxonomy
     *
     * @param \CMS\Bundle\ContentBundle\Entity\ContentTaxonomy $contentTaxonomy
     * @return Field
     */
    public function addContentTaxonomy(\CMS\Bundle\ContentBundle\Entity\ContentTaxonomy $contentTaxonomy)
    {
        $this->contentTaxonomy[] = $contentTaxonomy;

        return $this;
    }

    /**
     * Remove contentTaxonomy
     *
     * @param \CMS\Bundle\ContentBundle\Entity\ContentTaxonomy $contentTaxonomy
     */
    public function removeContentTaxonomy(\CMS\Bundle\ContentBundle\Entity\ContentTaxonomy $contentTaxonomy)
    {
        $this->contentTaxonomy->removeElement($contentTaxonomy);
    }

    /**
     * Get contentTaxonomy
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContentTaxonomy()
    {
        return $this->contentTaxonomy;
    }

    /**
     * Add fieldvalues
     *
     * @param \CMS\Bundle\ContentBundle\Entity\FieldValue $fieldvalues
     * @return Field
     */
    public function addFieldvalue(\CMS\Bundle\ContentBundle\Entity\FieldValue $fieldvalues)
    {
        $this->fieldvalues[] = $fieldvalues;

        return $this;
    }

    /**
     * Remove fieldvalues
     *
     * @param \CMS\Bundle\ContentBundle\Entity\FieldValue $fieldvalues
     */
    public function removeFieldvalue(\CMS\Bundle\ContentBundle\Entity\FieldValue $fieldvalues)
    {
        $this->fieldvalues->removeElement($fieldvalues);
    }

    /**
     * Get fieldvalues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFieldvalues()
    {
        return $this->fieldvalues;
    }

    public function __get($name)
    {
        if(is_object($this->getField())) {
            foreach ($this->getField()->getOptions() as $key => $option) {
                if ($key == $name) {
                    return $option;
                }
            }
        }
    }

    public function __set($name, $value)
    {
        if(is_array($this->getField()->getOptions())) {
            foreach ($this->getField()->getOptions() as $key => $option) {
                if ($key == $name) {
                    $option[$key]['value'] = $value;
                    return $this;
                }
            }
        }
    }

    public function __toString()
    {
        return $this->title;
    }
}
