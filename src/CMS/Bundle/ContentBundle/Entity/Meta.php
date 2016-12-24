<?php
namespace CMS\Bundle\ContentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CMS\ContentBundle\Entity\Meta
 *
 * @ORM\Table(name="meta")
 * @ORM\Entity(repositoryClass="CMS\Bundle\ContentBundle\Entity\Repository\MetaRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Meta
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string type
     *
     * @ORM\Column(name="type",type="string")
     */
    protected $type;

    /**
     * @var text name
     *
     * @ORM\Column(name="name",type="string")
     */
    protected $name;

    /**
     * @var text name
     * @Gedmo\Slug(fields={"name"}, updatable=false, separator="-")
     * @ORM\Column(name="alias",type="string")
     */
    protected $alias;

    /**
     * @var text value
     *
     * @ORM\Column(name="value",type="text")
     */
    protected $value;
     /**
     * @var boolean published
     *
     * @ORM\Column(name="published",type="boolean")
     */
    protected $published;
    /**
     * @var \DateTime $created
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

	/**
     * @ORM\OneToMany(targetEntity="MetaValue", mappedBy="meta", cascade={"remove"})
     */
    protected $metavalues;

    /**
     * @var String $default_value
     * @ORM\Column(name="default_value", type="string", nullable=true)
     */
    protected $default_value;

    /**
     * @var int order
     * @ORM\Column(name="meta_order", type="integer")
     */
    protected $order;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->metavalues = new \Doctrine\Common\Collections\ArrayCollection();
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
     *
     * @return Meta
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
     * Set name
     *
     * @param string $name
     *
     * @return Meta
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
     * Set value
     *
     * @param string $value
     *
     * @return Meta
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
     * Set published
     *
     * @param boolean $published
     *
     * @return Meta
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
     *
     * @return Meta
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
     * Add metavalue
     *
     * @param \CMS\Bundle\ContentBundle\Entity\MetaValue $metavalue
     *
     * @return Meta
     */
    public function addMetavalue(\CMS\Bundle\ContentBundle\Entity\MetaValue $metavalue)
    {
        $this->metavalues[] = $metavalue;

        return $this;
    }

    /**
     * Remove metavalue
     *
     * @param \CMS\Bundle\ContentBundle\Entity\MetaValue $metavalue
     */
    public function removeMetavalue(\CMS\Bundle\ContentBundle\Entity\MetaValue $metavalue)
    {
        $this->metavalues->removeElement($metavalue);
    }

    /**
     * Get metavalues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetavalues()
    {
        return $this->metavalues;
    }

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return Meta
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return String
     */
    public function getDefaultValue()
    {
        return $this->default_value;
    }

    /**
     * @param String $default_value
     */
    public function setDefaultValue($default_value)
    {
        $this->default_value = $default_value;
    }


}
