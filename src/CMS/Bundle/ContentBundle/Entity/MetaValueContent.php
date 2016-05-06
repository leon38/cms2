<?php
namespace CMS\Bundle\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * FieldValue
 *
 * @ORM\Table(name="metavalues_content")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
*/
class MetaValueContent
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Content", inversedBy="metavalues")
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id")
     */
    private $content;
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Meta", inversedBy="metavaluescontent")
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
     * @return MetaValueContent
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
     * @return MetaValueContent
     */
    public function setContent(Content $content)
    {
        $this->content = $content;
        $this->content->addMetavalue($this);
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
     * Set meta
     *
     * @param Meta $meta
     * @return MetaValueContent
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
}