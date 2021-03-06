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
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Content", inversedBy="metavalues", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $content;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="metavalues", cascade={"persist", "remove"})
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Meta", inversedBy="metavalues", fetch="EAGER")
     * @ORM\OrderBy(value={"meta_order"= "ASC"})
     */
    protected $meta;

    /**
     * @var string value
     *
     * @ORM\Column(name="value",type="text", nullable=true)
     */
    protected $value;

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
     * @param Content $content
     *
     * @return MetaValue
     */
    public function setContent(Content $content = null)
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
     * @param Category $category
     *
     * @return MetaValue
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set meta
     *
     * @param Meta $meta
     *
     * @return MetaValue
     */
    public function setMeta(Meta $meta = null)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * Get meta
     *
     * @return Meta
     */
    public function getMeta()
    {
        return $this->meta;
    }
}
