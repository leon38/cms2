<?php
namespace CMS\Bundle\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="CMS\Bundle\MenuBundle\Entity\Repository\EntryRepository")
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="menu_entries")
 */
class Entry
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(length=64)
     */
    protected $title;

    /**
     * @ORM\Column(name="status", type="boolean")
     */
     private $status;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer")
     */
    protected $lvl;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    protected $lft;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    protected $rgt;


    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer")
     * @ORM\ManyToOne(targetEntity="Entry")
     * @ORM\JoinColumn(name="root_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Entry", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Entry", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="MenuTaxonomy", inversedBy="entries")
     * @ORM\JoinColumn(name="menu_taxonomy", referencedColumnName="id")
     * @ORM\OrderBy({"title" = "ASC"})
     */
    protected $menu_taxonomy;

    /**
     * @ORM\ManyToOne(targetEntity="CMS\Bundle\ContentBundle\Entity\Content")
     * @ORM\JoinColumn(name="content", referencedColumnName="id")
     * @ORM\OrderBy({"title" = "ASC"})
     */
    protected $content;

    /**
     * @ORM\ManyToOne(targetEntity="CMS\Bundle\ContentBundle\Entity\Category")
     * @ORM\JoinColumn(name="category", referencedColumnName="id")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="CMS\Bundle\ContentBundle\Entity\ContentTaxonomy")
     * @ORM\JoinColumn(name="taxonomy", referencedColumnName="id")
     * @ORM\OrderBy({"title" = "ASC"})
     */
    protected $taxonomy;

    /**
     * @ORM\Column(name="external_url", type="string", length=255, nullable=true)
     */
    protected $external_url;

    protected $ordre;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
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
     *
     * @return Entry
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
     * Set lft
     *
     * @param integer $lft
     *
     * @return Entry
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
     * Set lvl
     *
     * @param integer $lvl
     *
     * @return Entry
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     *
     * @return Entry
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
     * Set externalUrl
     *
     * @param string $externalUrl
     *
     * @return Entry
     */
    public function setExternalUrl($externalUrl)
    {
        $this->external_url = $externalUrl;

        return $this;
    }

    /**
     * Get externalUrl
     *
     * @return string
     */
    public function getExternalUrl()
    {
        return $this->external_url;
    }

    /**
     * Set parent
     *
     * @param \CMS\Bundle\MenuBundle\Entity\Entry $parent
     *
     * @return Entry
     */
    public function setParent(\CMS\Bundle\MenuBundle\Entity\Entry $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \CMS\Bundle\MenuBundle\Entity\Entry
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param \CMS\Bundle\MenuBundle\Entity\Entry $child
     *
     * @return Entry
     */
    public function addChild(\CMS\Bundle\MenuBundle\Entity\Entry $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \CMS\Bundle\MenuBundle\Entity\Entry $child
     */
    public function removeChild(\CMS\Bundle\MenuBundle\Entity\Entry $child)
    {
        $this->children->removeElement($child);
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
     * Set menuTaxonomy
     *
     * @param \CMS\Bundle\MenuBundle\Entity\MenuTaxonomy $menuTaxonomy
     *
     * @return Entry
     */
    public function setMenuTaxonomy(\CMS\Bundle\MenuBundle\Entity\MenuTaxonomy $menuTaxonomy = null)
    {
        $this->menu_taxonomy = $menuTaxonomy;

        return $this;
    }

    /**
     * Get menuTaxonomy
     *
     * @return \CMS\Bundle\MenuBundle\Entity\MenuTaxonomy
     */
    public function getMenuTaxonomy()
    {
        return $this->menu_taxonomy;
    }

    /**
     * Set content
     *
     * @param \CMS\Bundle\ContentBundle\Entity\Content $content
     *
     * @return Entry
     */
    public function setContent(\CMS\Bundle\ContentBundle\Entity\Content $content = null)
    {
        $this->content = $content;

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
     * @return Entry
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
     * Set taxonomy
     *
     * @param \CMS\Bundle\ContentBundle\Entity\ContentTaxonomy $taxonomy
     *
     * @return Entry
     */
    public function setTaxonomy(\CMS\Bundle\ContentBundle\Entity\ContentTaxonomy $taxonomy = null)
    {
        $this->taxonomy = $taxonomy;

        return $this;
    }

    /**
     * Get taxonomy
     *
     * @return \CMS\Bundle\ContentBundle\Entity\ContentTaxonomy
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

    /**
     * Set root
     *
     * @param integer $root
     *
     * @return Entry
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get rootId
     *
     * @return integer
     */
    public function getRoot()
    {
        return $this->root;
    }

    public function __toString()
    {
        return ($this->getLvl() > 0) ? str_repeat(html_entity_decode('&nbsp;', ENT_QUOTES, 'UTF-8'), ($this->getLvl() - 1) * 3) . $this->getTitle() : $this->getTitle();
    }

    /**
     * Set root
     *
     * @param integer $root
     *
     * @return Entry
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get rootId
     *
     * @return integer
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    public function hasChildren()
    {
        return count($this->getChildren());
    }

    public function getUrl()
    {
        if ($this->content == null) {
            if ($this->category == null) {
                if ($this->taxonomy == null) {
                    if ($this->external_url == "") {
                        return null;
                    }
                    return array('url' => $this->external_url, 'external' => true);
                }
                return array('url' => $this->taxonomy->getAlias(), 'external' => false);
            }
            return array('url' => $this->category->getUrl(), 'external' => false);
        }
        return array('url' => $this->content->getUrl(), 'external' => false);
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Entry
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }
}
