<?php
namespace CMS\Bundle\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

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
  private $id;
  
  /**
   * @var string $title
   * @JMS\Expose()
   * @JMS\Type("string")
   * @ORM\Column(name="title", type="string", length=255)
   */
  private $title;
  
  /**
   * @var text $description
   *
   * @ORM\Column(name="description", type="text", nullable=true)
   */
  private $description;
  
  /**
   * @Gedmo\TreeLeft
   * @ORM\Column(name="lft", type="integer")
   */
  private $lft;
  
  /**
   * @Gedmo\TreeRight
   * @ORM\Column(name="rgt", type="integer")
   */
  private $rgt;
  
  /**
   * @Gedmo\TreeParent
   * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
   * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
   */
  private $parent;
  
  /**
   * @var entity ordre
   * @ORM\Column(type="integer", nullable=true)
   */
  private $ordre;
  
  /**
   * @Gedmo\TreeLevel
   * @ORM\Column(name="lvl", type="integer")
   */
  private $level;
  
  /**
   * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
   * @ORM\OrderBy({"lft" = "ASC"})
   */
  private $children;
  
  /**
   * @ORM\ManyToOne(targetEntity="CMS\Bundle\CoreBundle\Entity\Language")
   * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
   */
  private $language;
  
  /**
   * @ORM\ManyToMany(targetEntity="Content", mappedBy="categories")
   * @ORM\JoinTable(name="categories_contents")
   * @ORM\OrderBy({"created" = "DESC"})
   */
  private $contents;
  
  /**
   * @var string url
   * @Gedmo\Slug(fields={"title"}, updatable=false, separator="-")
   * @ORM\Column(name="url", type="string", length=255, unique=true)
   * @JMS\Expose()
   * @JMS\Type("string")
   */
  private $url;
  
  /**
   * @ORM\OneToMany(targetEntity="Category", mappedBy="referenceCategory")
   */
  private $translations;
  
  /**
   * @ORM\ManyToOne(targetEntity="Category", inversedBy="translations")
   * @ORM\JoinColumn(name="reference_id", referencedColumnName="id")
   */
  private $referenceCategory;
  
  /**
   * @var boolean published
   *
   * @ORM\Column(name="published", type="boolean")
   */
  private $published;
  
  /**
   * @ORM\OneToMany(targetEntity="MetaValueCategory", mappedBy="category", cascade={"remove", "persist"})
   */
  private $metavalues;
  
  /**
   * @var \DateTime $created
   * @JMS\Expose
   * @JMS\Type("DateTime<'d/m/Y'>")
   * @Gedmo\Timestampable(on="create")
   * @ORM\Column(name="created", type="datetime")
   */
  private $created;
  
  /**
   * @var \DateTime $modified
   *
   * @Gedmo\Timestampable(on="update")
   * @ORM\Column(name="modified", type="datetime")
   */
  private $modified;
  
  /**
   * @ORM\ManyToOne(targetEntity="CMS\Bundle\MediaBundle\Entity\Media")
   * @ORM\JoinColumn(name="banner", referencedColumnName="id")
   */
  private $banner;
  
  
  /**
   * Constructor
   */
  public function __construct()
  {
    $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    $this->contents = new \Doctrine\Common\Collections\ArrayCollection();
    $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
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
   * @return Category
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
   * Get description
   *
   * @return string
   */
  public function getDescription()
  {
    return $this->description;
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
   * Get lft
   *
   * @return integer
   */
  public function getLft()
  {
    return $this->lft;
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
   * Get rgt
   *
   * @return integer
   */
  public function getRgt()
  {
    return $this->rgt;
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
   * Get ordre
   *
   * @return integer
   */
  public function getOrdre()
  {
    return $this->ordre;
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
   * Get level
   *
   * @return integer
   */
  public function getLevel()
  {
    return $this->level;
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
   * Get url
   *
   * @return string
   */
  public function getUrl()
  {
    return $this->url;
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
   * Get published
   *
   * @return boolean
   */
  public function getPublished()
  {
    return $this->published;
  }
  
  /**
   * Set parent
   *
   * @param \CMS\Bundle\ContentBundle\Entity\Category $parent
   * @return Category
   */
  public function setParent(\CMS\Bundle\ContentBundle\Entity\Category $parent = null)
  {
    $this->parent = $parent;
    
    return $this;
  }
  
  /**
   * Get parent
   *
   * @return \CMS\Bundle\ContentBundle\Entity\Category
   */
  public function getParent()
  {
    return $this->parent;
  }
  
  /**
   * Add children
   *
   * @param \CMS\Bundle\ContentBundle\Entity\Category $children
   * @return Category
   */
  public function addChild(\CMS\Bundle\ContentBundle\Entity\Category $children)
  {
    $this->children[] = $children;
    
    return $this;
  }
  
  /**
   * Remove children
   *
   * @param \CMS\Bundle\ContentBundle\Entity\Category $children
   */
  public function removeChild(\CMS\Bundle\ContentBundle\Entity\Category $children)
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
   * Set language
   *
   * @param \CMS\Bundle\CoreBundle\Entity\Language $language
   * @return Category
   */
  public function setLanguage(\CMS\Bundle\CoreBundle\Entity\Language $language = null)
  {
    $this->language = $language;
    
    return $this;
  }
  
  /**
   * Get language
   *
   * @return \CMS\Bundle\ContentBundle\Entity\Language
   */
  public function getLanguage()
  {
    return $this->language;
  }
  
  /**
   * Add contents
   *
   * @param \CMS\Bundle\ContentBundle\Entity\Content $contents
   * @return Category
   */
  public function addContent(\CMS\Bundle\ContentBundle\Entity\Content $content)
  {
    $this->contents[] = $content;
    $content->addCategory($this);
    
    return $this;
  }
  
  /**
   * Remove contents
   *
   * @param \CMS\Bundle\ContentBundle\Entity\Content $contents
   */
  public function removeContent(\CMS\Bundle\ContentBundle\Entity\Content $content)
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
   * @param \CMS\Bundle\ContentBundle\Entity\Category $translations
   * @return Category
   */
  public function addTranslation(\CMS\Bundle\ContentBundle\Entity\Category $translations)
  {
    $this->translations[] = $translations;
    
    return $this;
  }
  
  /**
   * Remove translations
   *
   * @param \CMS\Bundle\ContentBundle\Entity\Category $translations
   */
  public function removeTranslation(\CMS\Bundle\ContentBundle\Entity\Category $translations)
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
   * Set referenceCategory
   *
   * @param \CMS\Bundle\ContentBundle\Entity\Category $referenceCategory
   * @return Category
   */
  public function setReferenceCategory(\CMS\Bundle\ContentBundle\Entity\Category $referenceCategory = null)
  {
    $this->referenceCategory = $referenceCategory;
    
    return $this;
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
   * Add metavalues
   *
   * @param \CMS\Bundle\ContentBundle\Entity\MetaValueCategory $metavalues
   * @return Category
   */
  public function addMetavalue(\CMS\Bundle\ContentBundle\Entity\MetaValueCategory $metavalues)
  {
    $this->metavalues[] = $metavalues;
    
    return $this;
  }
  
  /**
   * Remove metavalues
   *
   * @param \CMS\Bundle\ContentBundle\Entity\MetaValueCategory $metavalues
   */
  public function removeMetavalue(\CMS\Bundle\ContentBundle\Entity\MetaValueCategory $metavalues)
  {
    $this->metavalues->removeElement($metavalues);
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
    return ($this->getLevel() > 0) ? str_repeat(html_entity_decode('&nbsp;', ENT_QUOTES, 'UTF-8'), ($this->getLevel() - 1) * 3) . $this->getTitle() : $this->getTitle();
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
   * Get created
   *
   * @return \DateTime
   */
  public function getCreated()
  {
    return $this->created;
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
   * Get modified
   *
   * @return \DateTime
   */
  public function getModified()
  {
    return $this->modified;
  }
  
  /**
   * Set banner
   *
   * @param \CMS\Bundle\MediaBundle\Entity\Media $banner
   *
   * @return Category
   */
  public function setBanner(\CMS\Bundle\MediaBundle\Entity\Media $banner = null)
  {
    $this->banner = $banner;
    
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
  
  public function getWebPath()
  {
    return null === $this->banner ? null : $this->banner->getWebPath();
  }
}
