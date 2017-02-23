<?php
namespace CMS\Bundle\ContentBundle\Entity;

use CMS\Bundle\CoreBundle\Entity\Language;
use CMS\Bundle\MediaBundle\Entity\Media;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CMS\Bundle\ContentBundle\Entity\Repository\CategoryRepository")
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="category")
 * @JMS\ExclusionPolicy("all")
 */
class Category
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
     * @var string $title
     * @JMS\Expose()
     * @JMS\Type("string")
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    protected $lft;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    protected $rgt;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $parent;

    /**
     * @var int ordre
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $ordre;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    protected $level;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="CMS\Bundle\CoreBundle\Entity\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    protected $language;

    /**
     * @ORM\ManyToMany(targetEntity="Content", mappedBy="categories")
     * @ORM\JoinTable(name="categories_contents")
     * @ORM\OrderBy({"created" = "DESC"})
     */
    protected $contents;

    /**
     * @var string url
     * @Gedmo\Slug(fields={"title"}, updatable=false, separator="-")
     * @ORM\Column(name="url", type="string", length=255, unique=true)
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    protected $url;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="referenceCategory")
     */
    protected $translations;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="translations")
     * @ORM\JoinColumn(name="reference_id", referencedColumnName="id")
     */
    protected $referenceCategory;

    /**
     * @var boolean published
     *
     * @ORM\Column(name="published", type="boolean")
     */
    protected $published;

    /**
     * @ORM\OneToMany(targetEntity="MetaValue", mappedBy="category", cascade={"remove", "persist"})
     */
    protected $metavalues;

    /**
     * @var \DateTime $created
     * @JMS\Expose
     * @JMS\Type("DateTime<'d/m/Y'>")
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime $modified
     * @JMS\Expose
     * @JMS\Type("DateTime<'d/m/Y'>")
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="modified", type="datetime")
     */
    protected $modified;

    /**
     * @ORM\ManyToOne(targetEntity="CMS\Bundle\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="banner", referencedColumnName="id", nullable=true)
     */
    protected $banner;

    protected $metaValuesTemp;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->contents = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->metavalues = new ArrayCollection();
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
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     * @return Category
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return Category
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set ordre
     *
     * @param integer $ordre
     * @return Category
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Category
     */
    public function setUrl($url)
    {
        $this->url = $url;

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
     * Set published
     *
     * @param boolean $published
     * @return Category
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get parent
     *
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param Category $parent
     * @return Category
     */
    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Add children
     *
     * @param Category $children
     * @return Category
     */
    public function addChild(Category $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param Category $children
     */
    public function removeChild(Category $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Get language
     *
     * @return Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set language
     *
     * @param Language $language
     * @return Category
     */
    public function setLanguage(Language $language = null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Add contents
     *
     * @param Content $content
     * @return Category
     * @internal param Content $contents
     */
    public function addContent(Content $content)
    {
        $this->contents[] = $content;
        $content->addCategory($this);

        return $this;
    }

    /**
     * Remove contents
     *
     * @param Content $content
     * @internal param Content $contents
     */
    public function removeContent(Content $content)
    {
        $this->contents->removeElement($content);
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
     * Add translations
     *
     * @param Category $translation
     * @return Category
     */
    public function addTranslation(Category $translation)
    {
        $this->translations[] = $translation;

        return $this;
    }

    /**
     * Remove translations
     *
     * @param Category $translations
     */
    public function removeTranslation(Category $translations)
    {
        $this->translations->removeElement($translations);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Get referenceCategory
     *
     * @return \CMS\Bundle\ContentBundle\Entity\Category
     */
    public function getReferenceCategory()
    {
        return $this->referenceCategory;
    }

    /**
     * Set referenceCategory
     *
     * @param Category $referenceCategory
     * @return Category
     */
    public function setReferenceCategory(Category $referenceCategory = null)
    {
        $this->referenceCategory = $referenceCategory;

        return $this;
    }

    /**
     * Add metavalues
     *
     * @param MetaValue $metavalue
     * @return Category
     * @internal param MetaValue $metavalues
     */
    public function addMetavalue(MetaValue $metavalue)
    {
        $this->metavalues[] = $metavalue;

        return $this;
    }

    /**
     * Remove metavalues
     *
     * @param MetaValue $metavalue
     * @internal param MetaValueCategory $metavalues
     */
    public function removeMetavalue(MetaValue $metavalue)
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

    public function __toString()
    {
        return $this->getTitle();
    }

    public function toStringLevel()
    {
        return ($this->getLevel() > 1) ? '|'.str_repeat(html_entity_decode('_', ENT_QUOTES, 'UTF-8'), $this->getLevel()-1) . ' ' .  $this->getTitle() : $this->getTitle();
    }

    public function toStringLevelList()
    {
        return ($this->getLevel() > 0) ? str_repeat(html_entity_decode('.&nbsp;&nbsp;&nbsp;', ENT_QUOTES, 'UTF-8'), $this->getLevel()) . '|_ ' .  $this->getTitle() : $this->getTitle();
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return Category
     */
    public function setLevel($level)
    {
        $this->level = $level;

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
     * Set title
     *
     * @param string $title
     * @return Category
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Category
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     *
     * @return Category
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get banner
     *
     * @return \CMS\Bundle\MediaBundle\Entity\Media
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * Set banner
     *
     * @param Media $banner
     *
     * @return Category
     */
    public function setBanner(Media $banner = null)
    {
        $this->banner = $banner;

        return $this;
    }

    public function getWebPath()
    {
        return null === $this->banner ? null : $this->banner->getWebPath();
    }

    /**
     * @return mixed
     */
    public function getMetaValuesTemp()
    {
        return $this->metaValuesTemp;
    }

    /**
     * @param mixed $metaValuesTemp
     */
    public function setMetaValuesTemp($metaValuesTemp)
    {
        $this->metaValuesTemp = $metaValuesTemp;
    }
}
