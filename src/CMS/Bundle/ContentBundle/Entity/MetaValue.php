<?php
namespace CMS\Bundle\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * FieldValue
 *
 * @ORM\Table(name="metavalues")
 * @ORM\Entity(repositoryClass="CMS\Bundle\ContentBundle\Entity\Repository\MetaValueRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class MetaValue
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Content", inversedBy="metavalues", cascade={"persist", "remove"})
     */
    private $content;
    
    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="metavalues", cascade={"persist", "remove"})
     */
    private $category;
    
    /**
     * @ORM\ManyToOne(targetEntity="Meta", inversedBy="metavalues")
     */
    private $meta;
    
    /**
     * @var string value
     *
     * @ORM\Column(name="value",type="text")
     */
    private $value;

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
     * Set value
     *
     * @param string $value
     *
     * @return MetaValue
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
     * @param \CMS\Bundle\ContentBundle\Entity\Content $content
     *
     * @return MetaValue
     */
    public function setContent(\CMS\Bundle\ContentBundle\Entity\Content $content = null)
    {
        $this->content = $content;
        $this->content->addMetavalue($this);
        return $this;
    }

    /**
     * Get content
     *
     * @return \CMS\Bundle\ContentBundle\Entity\Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set category
     *
     * @param \CMS\Bundle\ContentBundle\Entity\Category $category
     *
     * @return MetaValue
     */
    public function setCategory(\CMS\Bundle\ContentBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \CMS\Bundle\ContentBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set meta
     *
     * @param \CMS\Bundle\ContentBundle\Entity\Meta $meta
     *
     * @return MetaValue
     */
    public function setMeta(\CMS\Bundle\ContentBundle\Entity\Meta $meta = null)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * Get meta
     *
     * @return \CMS\Bundle\ContentBundle\Entity\Meta
     */
    public function getMeta()
    {
        return $this->meta;
    }
}
