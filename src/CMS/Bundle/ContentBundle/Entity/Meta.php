<?php
namespace CMS\Bundle\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
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
    private $id;
    /**
     * @var string type
     *
     * @ORM\Column(name="type",type="string")
     */
    private $type;
    /**
     * @var text name
     *
     * @ORM\Column(name="name",type="text")
     */
    private $name;
    /**
     * @var text value
     *
     * @ORM\Column(name="value",type="text")
     */
    private $value;
     /**
     * @var boolean published
     *
     * @ORM\Column(name="published",type="boolean")
     */
    private $published;
    /**
     * @var \DateTime $created
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToMany(targetEntity="ContentTaxonomy", inversedBy="metas")
     * @ORM\JoinTable(name="metas_taxonomy")
     */
    private $contentTaxonomy;

	/**
     * @ORM\OneToMany(targetEntity="MetaValueContent", mappedBy="meta", cascade={"remove"})
     */
    private $metavaluescontent;
    /**
     * @ORM\OneToMany(targetEntity="MetaValueCategory", mappedBy="meta", cascade={"remove"})
     */
    private $metavaluescategory;
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
     * @return CMMeta
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
     * Set value
     *
     * @param string $value
     * @return CMMeta
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
     * @return CMMeta
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
     * @ORM\PrePersist()
     * @param \DateTime $created
     * @return CMMeta
     */
    public function setCreated()
    {
        $this->created = new \DateTime();

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
     * Add metavaluescontent
     *
     * @param MetaValueContent $metavaluescontent
     * @return CMMeta
     */
    public function addMetavaluescontent(MetaValueContent $metavaluescontent)
    {
        $this->metavaluescontent[] = $metavaluescontent;

        return $this;
    }
    /**
     * Remove metavaluescontent
     *
     * @param MetaValueContent $metavaluescontent
     */
    public function removeMetavaluescontent(MetaValueContent $metavaluescontent)
    {
        $this->metavaluescontent->removeElement($metavaluescontent);
    }
    /**
     * Get metavaluescontent
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetavaluescontent()
    {
        return $this->metavaluescontent;
    }
    /**
     * Add metavaluescategory
     *
     * @param MetaValueCategory $metavaluescategory
     * @return CMMeta
     */
    public function addMetavaluescategory(MetaValueCategory $metavaluescategory)
    {
        $this->metavaluescategory[] = $metavaluescategory;

        return $this;
    }
    /**
     * Remove metavaluescategory
     *
     * @param MetaValueCategory $metavaluescategory
     */
    public function removeMetavaluescategory(MetaValueCategory $metavaluescategory)
    {
        $this->metavaluescategory->removeElement($metavaluescategory);
    }
    /**
     * Get metavaluescategory
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetavaluescategory()
    {
        return $this->metavaluescategory;
    }
    public function displayMetaInform($value='') {
        $html  = '<div class="control-group">';
        $html .= '<label class="control-label">'.$this->type.'</label>';
        $html .= '<div class="controls">';
        $html .= '<input type="text" name="'.$this->name.'" value="'.$value.'" />';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
    /**
     * Set name
     *
     * @param string $name
     * @return CMMeta
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
     * Add contentTaxonomy
     *
     * @param \CMS\Bundle\ContentBundle\Entity\ContentTaxonomy $contentTaxonomy
     *
     * @return Meta
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
}
