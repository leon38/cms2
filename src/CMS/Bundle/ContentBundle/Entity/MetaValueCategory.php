<?php
namespace CMS\Bundle\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * FieldValue
 *
 * @ORM\Table(name="metavalues_category")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class MetaValueCategory
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="metavalues", cascade={"persist"})
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Meta", inversedBy="metavaluescategory")
     * @ORM\JoinColumn(name="meta_id", referencedColumnName="id")
     */
    private $meta;
    /**
     * @var string value
     *
     * @ORM\Column(name="value",type="string", nullable=true)
     */
    private $value;
    /**
     * Set value
     *
     * @param string $value
     * @return CMMetaValueCategory
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
     * Set category
     *
     * @param Category $category
     * @return MetaValueCategory
     */
    public function setCategory(Category $category)
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
     * @return MetaValueCategory
     */
    public function setMeta(Meta $meta)
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
    public function __toString()
    {
        return $this->category->getName();
    }
}