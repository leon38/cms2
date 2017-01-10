<?php
namespace CMS\Bundle\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Tests\StringableObject;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\Table(name="menu_taxonomy")
 */
class MenuTaxonomy
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     */
     protected $title;

    /**
     * @ORM\Column(name="slug", type="string", length=255)
     * @Gedmo\Slug(fields={"title"}, updatable=false, separator="-")
     */
    protected $slug;

    /**
     * @ORM\Column(name="status", type="boolean")
     */
     private $status = true;

	/**
  	 * @ORM\OneToMany(targetEntity="Entry", mappedBy="menu_taxonomy")
     * @ORM\OrderBy({"lft"="ASC"})
	 */
    protected $entries;

    /**
     * @ORM\ManyToOne(targetEntity="CMS\Bundle\CoreBundle\Entity\Language")
     * @ORM\JoinColumn(name="language", referencedColumnName="id")
     */
     private $language;
    
    /**
     * @var String
     * @ORM\Column(name="position", type="string", length=100, nullable=true)
     */
    private $position;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->entries = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
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
     * @return MenuTaxonomy
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
     * Set slug
     *
     * @param string $slug
     *
     * @return MenuTaxonomy
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add entry
     *
     * @param \CMS\Bundle\MenuBundle\Entity\Entry $entry
     *
     * @return MenuTaxonomy
     */
    public function addEntry(\CMS\Bundle\MenuBundle\Entity\Entry $entry)
    {
        $this->entries[] = $entry;

        return $this;
    }

    /**
     * Remove entry
     *
     * @param \CMS\Bundle\MenuBundle\Entity\Entry $entry
     */
    public function removeEntry(\CMS\Bundle\MenuBundle\Entity\Entry $entry)
    {
        $this->entries->removeElement($entry);
    }

    /**
     * Get entries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return MenuTaxonomy
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

    /**
     * Set language
     *
     * @param \CMS\Bundle\CoreBundle\Entity\Language $language
     * @return MenuTaxonomy
     */
    public function setLanguage(\CMS\Bundle\CoreBundle\Entity\Language $language = null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return \CMS\Bundle\CoreBundle\Entity\Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return MenuTaxonomy
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }
}
