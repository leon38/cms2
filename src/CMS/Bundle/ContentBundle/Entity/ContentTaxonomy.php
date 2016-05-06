<?php
namespace CMS\Bundle\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="content_taxonomy")
 */
class ContentTaxonomy
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

   /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"}, updatable=false, separator="-")
     * @ORM\Column(name="alias", type="string")
     */
     private $alias;

    /**
     * @ORM\OneToMany(targetEntity="Content", mappedBy="taxonomy")
     */
    private $contents;

    /**
     * @ORM\ManyToMany(targetEntity="Field", mappedBy="contentTaxonomy")
     * @ORM\JoinTable(name="fields_taxonomy")
     */
    private $fields;

    /**
     * @ORM\ManyToMany(targetEntity="Meta", mappedBy="contentTaxonomy")
     * @ORM\JoinTable(name="metas_taxonomy")
     */
    private $metas;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contents = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return ContentTaxonomy
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
     * Set template
     *
     * @param string $template
     * @return ContentTaxonomy
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Add contents
     *
     * @param \CMS\Bundle\ContentBundle\Entity\Content $contents
     * @return ContentTaxonomy
     */
    public function addContent(\CMS\Bundle\ContentBundle\Entity\Content $contents)
    {
        $this->contents[] = $contents;

        return $this;
    }

    /**
     * Remove contents
     *
     * @param \CMS\Bundle\ContentBundle\Entity\Content $contents
     */
    public function removeContent(\CMS\Bundle\ContentBundle\Entity\Content $contents)
    {
        $this->contents->removeElement($contents);
    }

    /**
     * Get contents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Add fields
     *
     * @param \CMS\Bundle\ContentBundle\Entity\Field $fields
     * @return ContentTaxonomy
     */
    public function addField(\CMS\Bundle\ContentBundle\Entity\Field $fields)
    {
        $this->fields[] = $fields;

        return $this;
    }

    /**
     * Remove fields
     *
     * @param \CMS\Bundle\ContentBundle\Entity\Field $fields
     */
    public function removeField(\CMS\Bundle\ContentBundle\Entity\Field $fields)
    {
        $this->fields->removeElement($fields);
    }

    /**
     * Get fields
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return ContentTaxonomy
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

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Add meta
     *
     * @param \CMS\Bundle\ContentBundle\Entity\Meta $meta
     *
     * @return ContentTaxonomy
     */
    public function addMeta(\CMS\Bundle\ContentBundle\Entity\Meta $meta)
    {
        $this->metas[] = $meta;

        return $this;
    }

    /**
     * Remove meta
     *
     * @param \CMS\Bundle\ContentBundle\Entity\Meta $meta
     */
    public function removeMeta(\CMS\Bundle\ContentBundle\Entity\Meta $meta)
    {
        $this->metas->removeElement($meta);
    }

    /**
     * Get metas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetas()
    {
        return $this->metas;
    }
}
