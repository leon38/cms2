<?php
namespace CMS\Bundle\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="CMS\Bundle\ContentBundle\Entity\Repository\ContentRepository")
 * @ORM\Table(name="content")
 * @ORM\HasLifecycleCallbacks
 */
class Content
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
    private $title;

    /**
     * @var text $description
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="CMS\Bundle\CoreBundle\Entity\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $language;

    /**
     * @ORM\ManyToOne(targetEntity="ContentTaxonomy", inversedBy="contents", cascade={"persist"})
     * @ORM\JoinColumn(name="taxonomy_id", referencedColumnName="id")
     */
    private $taxonomy;

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="contents")
     * @ORM\JoinTable(name="categories_contents")
     */
    private $categories;

    /**
     * @var \DateTime $created
     *
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
     * @var boolean $published
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;

    /**
     * @var string url
     * @ORM\Column(name="url", type="string", length=255, unique=true)
     */
     private $url;

    /**
     * @ORM\OneToMany(targetEntity="Content", mappedBy="referenceContent")
     */
    private $translations;

    /**
     * @ORM\ManyToOne(targetEntity="Content", inversedBy="translations")
     * @ORM\JoinColumn(name="reference_id", referencedColumnName="id")
     */
    private $referenceContent;

    /**
     * @ORM\OneToMany(targetEntity="FieldValue", mappedBy="content", cascade={"remove", "persist"}, indexBy="id")
     */
    private $fieldvalues;

    /**
     * @ORM\OneToMany(targetEntity="MetaValueContent", mappedBy="content", cascade={"remove", "persist"})
     */
    private $metavalues;

    /**
     * @ORM\ManyToOne(targetEntity="CMS\Bundle\CoreBundle\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(name="author", referencedColumnName="id")
     */
    private $author;

    /**
     * @ORM\Column(name="thumbnail", type="string", length=255, nullable=true)
     */
    private $thumbnail;

     /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    private $temp;


    private $fieldValuesTemp;
    private $metaValuesTemp;

    /**
     * Constructor
     */
    public function __construct()
    {
        // $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        // $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        // $this->fieldvalues = new \Doctrine\Common\Collections\ArrayCollection();
        // $this->metavalues = new \Doctrine\Common\Collections\ArrayCollection();

    }

    public function __toString()
    {
        return $this->title;
    }

    public function getAbsolutePath()
    {
        return null === $this->thumbnail ? null : $this->getUploadRootDir().$this->thumbnail;
    }

    public function getWebPath()
    {
        return null === $this->thumbnail ? null : $this->getUploadDir().$this->thumbnail;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/thumbs/';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = date('Y').'/'.date('m').'/'.$filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir().date('Y').'/'.date('m'), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();
        if ($file) {
            unlink($file);
        }
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
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
     * @return Content
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
     * @return Content
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
     * Set created
     *
     * @param \DateTime $created
     * @return Content
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
     * @return Content
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
     * Set published
     *
     * @param boolean $published
     * @return Content
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
        switch($this->published) {
            case 0:
                return 'cms.content.status.draft';
            case 1:
                return 'cms.content.status.published';
            case 2:
                return 'cms.content.status.pending';
        }
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Content
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
     * Set language
     *
     * @param \CMS\Bundle\CoreBundle\Entity\Language $language
     * @return Content
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
     * Set taxonomy
     *
     * @param \CMS\Bundle\ContentBundle\Entity\ContentTaxonomy $taxonomy
     * @return Content
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
     * Add categories
     *
     * @param \CMS\Bundle\ContentBundle\Entity\Category $categories
     * @return Content
     */
    public function addCategory(\CMS\Bundle\ContentBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \CMS\Bundle\ContentBundle\Entity\Category $categories
     */
    public function removeCategory(\CMS\Bundle\ContentBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add translations
     *
     * @param \CMS\Bundle\ContentBundle\Entity\Content $translations
     * @return Content
     */
    public function addTranslation(\CMS\Bundle\ContentBundle\Entity\Content $translations)
    {
        $this->translations[] = $translations;

        return $this;
    }

    /**
     * Remove translations
     *
     * @param \CMS\Bundle\ContentBundle\Entity\Content $translations
     */
    public function removeTranslation(\CMS\Bundle\ContentBundle\Entity\Content $translations)
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
     * Set referenceContent
     *
     * @param \CMS\Bundle\ContentBundle\Entity\Content $referenceContent
     * @return Content
     */
    public function setReferenceContent(\CMS\Bundle\ContentBundle\Entity\Content $referenceContent = null)
    {
        $this->referenceContent = $referenceContent;

        return $this;
    }

    /**
     * Get referenceContent
     *
     * @return \CMS\Bundle\ContentBundle\Entity\Content
     */
    public function getReferenceContent()
    {
        return $this->referenceContent;
    }

    /**
     * Add fieldvalues
     *
     * @param \CMS\Bundle\ContentBundle\Entity\FieldValue $fieldvalues
     * @return Content
     */
    public function addFieldvalue(\CMS\Bundle\ContentBundle\Entity\FieldValue $fieldvalues)
    {
        $this->fieldvalues[$fieldvalues->getField()->getId()] = $fieldvalues;

        return $this;
    }

    /**
     * Remove fieldvalues
     *
     * @param \CMS\Bundle\ContentBundle\Entity\FieldValue $fieldvalues
     */
    public function removeFieldvalue(\CMS\Bundle\ContentBundle\Entity\FieldValue $fieldvalues)
    {
        $this->fieldvalues->removeElement($fieldvalues);
    }

    /**
     * Get fieldvalues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFieldvalues()
    {
        return $this->fieldvalues;
    }

    public function setFieldvalues(\Doctrine\Common\Collections\ArrayCollection $fieldvalues)
    {
        $this->fieldvalues = $fieldvalues;
        return $this;
    }

    /**
     * Add metavalues
     *
     * @param \CMS\Bundle\ContentBundle\Entity\MetaValueContent $metavalues
     * @return Content
     */
    public function addMetavalue(\CMS\Bundle\ContentBundle\Entity\MetaValueContent $metavalues)
    {
        $this->metavalues[] = $metavalues;
        return $this;
    }

    /**
     * Remove metavalues
     *
     * @param \CMS\Bundle\ContentBundle\Entity\MetaValueContent $metavalues
     */
    public function removeMetavalue(\CMS\Bundle\ContentBundle\Entity\MetaValueContent $metavalues)
    {
        $this->metavalues->removeElement($metavalues);
    }

    public function setMetavalues(\Doctrine\Common\Collections\ArrayCollection $metavalues)
    {
        $this->metavalues = $metavalues;
        return $this;
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

    public function __get($name)
    {
        if(is_array($this->getFieldvalues())) {
            foreach ($this->getFieldvalues() as $fieldvalue) {
                if ($fieldvalue->getField()->getName() == $name) {
                    return $fieldvalue;
                }
            }
        }

        if(is_array($this->getMetavalues())) {
            foreach ($this->getMetavalues() as $metavalue) {
                if ($metavalue->getMeta()->getName() == $name) {
                    return $metavalue;
                }
            }
        }
    }

    public function __set($name, $value)
    {
        foreach($this->getTaxonomy()->getFields() as $field) {
            if ($field->getName() == $name) {
                $update = false;
                foreach($this->fieldvalues as $fieldvalue) {
                    if ($fieldvalue->getField()->getId() == $field->getId()) {
                        $fieldvalue->setValue($value);
                        $update = true;
                    }
                }
                if (!$update) {
                    $fieldvalue = new FieldValue();
                    $fieldvalue->setField($field);
                    $fieldvalue->setValue($value);
                    $fieldvalue->setContent($this);
                }
            }
        }

        foreach($this->getTaxonomy()->getMetas() as $meta) {
            if ($meta->getName() == $name) {
                $update = false;
                foreach($this->metavalues as $metavalue) {
                    if ($metavalue->getMeta()->getId() == $meta->getId()) {
                        $metavalue->setValue($value);
                        $update = true;
                    }
                }
                if (!$update) {
                    $metavalue = new MetaValueContent();
                    $metavalue->setMeta($meta);
                    $metavalue->setValue($value);
                    $metavalue->setContent($this);
                }
            }
        }
    }


    public function getMetaValuesTemp()
    {
        return $this->metaValuesTemp;
    }


    public function getFieldValuesTemp()
    {
        return $this->fieldValuesTemp;
    }

    /**
     * Set author
     *
     * @param \CMS\Bundle\CoreBundle\Entity\User $author
     *
     * @return Content
     */
    public function setAuthor(\CMS\Bundle\CoreBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \CMS\Bundle\CoreBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set thumbnail
     *
     * @param string $thumbnail
     *
     * @return Content
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function getPermalink()
    {
        return "/".$this->url;
    }
}
