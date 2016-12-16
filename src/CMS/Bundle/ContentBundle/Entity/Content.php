<?php
namespace CMS\Bundle\ContentBundle\Entity;

use CMS\Bundle\CoreBundle\Entity\Language;
use CMS\Bundle\CoreBundle\Entity\User;
use CMS\Bundle\MediaBundle\Entity\Media;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="CMS\Bundle\ContentBundle\Entity\Repository\ContentRepository")
 * @ORM\EntityListeners({ "CMS\Bundle\ContentBundle\Listener\ContentListener" })
 * @ORM\Table(name="content")
 * @ORM\HasLifecycleCallbacks
 * @JMS\ExclusionPolicy("all")
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
     * @JMS\Expose
     * @JMS\Type("string")
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var string $description
     * @JMS\Expose
     * @JMS\Type("string")
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="CMS\Bundle\CoreBundle\Entity\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    protected $language;

    /**
     * @ORM\ManyToOne(targetEntity="ContentTaxonomy", inversedBy="contents", cascade={"persist"})
     * @ORM\JoinColumn(name="taxonomy_id", referencedColumnName="id")
     */
    protected $taxonomy;

    /**
     * @var ArrayCollection|Category[]
     * @JMS\Expose
     * @JMS\Type("ArrayCollection")
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="contents")
     * @ORM\JoinTable(name="categories_contents")
     */
    protected $categories;

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
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="modified", type="datetime")
     */
    protected $modified;

    /**
     * @var boolean $published
     *
     * @ORM\Column(name="published", type="boolean")
     */
    protected $published;

    /**
     * @var string url
     * @JMS\Expose
     * @JMS\Type("string")
     * @ORM\Column(name="url", type="string", length=255, unique=true)
     */
    protected $url;

    /**
     * @var ArrayCollection|Content[]
     * @ORM\OneToMany(targetEntity="Content", mappedBy="referenceContent")
     * @JMS\Expose()
     */
    protected $translations;

    /**
     * @ORM\ManyToOne(targetEntity="Content", inversedBy="translations")
     * @ORM\JoinColumn(name="reference_id", referencedColumnName="id")
     */
    protected $referenceContent;

    /**
     * @ORM\OneToMany(targetEntity="FieldValue", mappedBy="content", cascade={"remove", "persist"}, indexBy="id", fetch="EAGER")
     */
    protected $fieldvalues;

    /**
     * @ORM\OneToMany(targetEntity="MetaValue", mappedBy="content", cascade={"remove", "persist"})
     */
    protected $metavalues;

    /**
     * @JMS\Expose
     * @JMS\Type("CMS\Bundle\CoreBundle\Entity\User")
     * @ORM\ManyToOne(targetEntity="CMS\Bundle\CoreBundle\Entity\User", inversedBy="posts", fetch="EAGER")
     * @ORM\JoinColumn(name="author", referencedColumnName="id")
     */
    protected $author;

    /**
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity="CMS\Bundle\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="thumbnail", referencedColumnName="id")
     */
    protected $thumbnail;

    /**
     * @Assert\File(maxSize="6000000")
     */
    protected $file;

    /**
     * @var Boolean $featured
     *
     * @ORM\Column(name="featured", type="boolean")
     */
    protected $featured = false;

    /**
     * @var float $temps_lecture Temps de lecture de l'article
     *
     * @ORM\Column(name="temps_lecture", type="float")
     */
    protected $temps_lecture;

    /**
     * @JMS\Expose
     * @JMS\Type("boolean")
     */
    protected $hasThumbnail;

    /**
     * @var String $chapo
     *
     * @ORM\Column(name="chapo", type="text")
     */
    protected $chapo;

    /**
     * @var ArrayCollection|Comment[] $comments
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="content")
     */
    protected $comments;

    protected $temp;


    protected $fieldValuesTemp;
    public $fieldValuesHtml;
    protected $metaValuesTemp;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->fieldvalues = new ArrayCollection();
        $this->metavalues = new ArrayCollection();

    }

    /**
     * @ORM\PostLoad()
     */
    public function postLoad()
    {
        $nb_fields = count($this->fieldvalues);

        for ($i=0; $i < $nb_fields; $i++) {
           $this->fieldvalues[$i]->setValue(@unserialize($this->fieldvalues[$i]->getValue()));
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getWebPath()
    {
        return null === $this->thumbnail ? null : $this->thumbnail->getWebPath();
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
        switch ($this->published) {
            case 0:
                return 'cms.content.status.draft';
            case 1:
                return 'cms.content.status.published';
            case 2:
                return 'cms.content.status.pending';
            default:
                return 'cms.content.status.published';
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
     * @param Language $language
     * @return Content
     */
    public function setLanguage(Language $language = null)
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
     * @param ContentTaxonomy $taxonomy
     * @return Content
     */
    public function setTaxonomy(ContentTaxonomy $taxonomy = null)
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
     * @param Category $category
     * @return Content
     */
    public function addCategory(Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param Category $category
     */
    public function removeCategory(Category $category)
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
    public function addTranslation(Content $translations)
    {
        $this->translations[] = $translations;

        return $this;
    }

    /**
     * Remove translations
     *
     * @param Content $translation
     */
    public function removeTranslation(Content $translation)
    {
        $this->translations->removeElement($translation);
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
    public function setReferenceContent(Content $referenceContent = null)
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
     * @param FieldValue $fieldvalue
     * @return Content
     * @internal param FieldValue $fieldvalues
     */
    public function addFieldvalue(FieldValue $fieldvalue)
    {
        if ($fieldvalue->getField() !== null) {
            $this->fieldvalues[$fieldvalue->getField()->getId()] = $fieldvalue;
        } else {
            $this->fieldvalues[] = $fieldvalue;
        }

        return $this;
    }

    /**
     * Remove fieldvalue
     *
     * @param FieldValue $fieldvalue
     */
    public function removeFieldvalue(FieldValue $fieldvalue)
    {
        $this->fieldvalues->removeElement($fieldvalue);
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

    public function setFieldvalues(ArrayCollection $fieldvalues)
    {
        $this->fieldvalues = $fieldvalues;

        return $this;
    }

    /**
     * Add metavalues
     *
     * @param MetaValue $metavalue
     * @return Content
     * @internal param MetaValue $metavalues
     */
    public function addMetavalue(MetaValue $metavalue)
    {
        $this->metavalues[] = $metavalue;

        return $this;
    }

    /**
     * Remove metavalue
     *
     * @param MetaValue $metavalue
     */
    public function removeMetavalue(MetaValue $metavalue)
    {
        $this->metavalues->removeElement($metavalue);
    }

    public function setMetavalues(ArrayCollection $metavalues)
    {
        $this->metavalues = $metavalues;

        return $this;
    }

    /**
     * Get metavalues
     *
     * @return ArrayCollection
     */
    public function getMetavalues()
    {
        return $this->metavalues;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (is_array($this->getFieldvalues())) {
            foreach ($this->getFieldvalues() as $fieldvalue) {
                if ($fieldvalue->getField()->getName() == $name) {
                    return $fieldvalue;
                }
            }
        }

        if (is_array($this->getMetavalues())) {
            foreach ($this->getMetavalues() as $metavalue) {
                if ($metavalue->getMeta()->getName() == $name) {
                    return $metavalue;
                }
            }
        }

        return null;
    }

    /**
     * @param string $name
     * @return String
     */
    public function getFieldValue($name)
    {

        foreach ($this->getFieldvalues() as $fieldvalue) {
            if ($fieldvalue->getField()->getName() == $name) {
                return $fieldvalue->getValue();
            }
        }
        return null;
    }

    public function get($name)
    {
        if (isset($this->fieldValuesHtml[$name])) {
            return $this->fieldValuesHtml[$name];
        }

        foreach ($this->getMetavalues() as $metavalue) {
            if ($metavalue->getMeta()->getName() == $name) {
                return $metavalue;
            }
        }
        return false;
    }

    public function __set($name, $value)
    {
        if (is_array($this->getMetavalues())) {
            foreach ($this->getMetavalues() as $metavalue) {
                if ($metavalue->getMeta()->getName() == $name) {
                    $metavalue->setValue($value);
                }
            }
        }
    }


    public function getMetaValuesTemp()
    {
        return $this->metaValuesTemp;
    }

    public function setMetaValuesTemp($metaValuesTemp)
    {
        $this->metaValuesTemp = $metaValuesTemp;
    }


    public function getFieldValuesTemp()
    {
        return $this->fieldValuesTemp;
    }

    public function setFieldValuesTemp($fieldValuesTemp)
    {
        $this->fieldValuesTemp = $fieldValuesTemp;
    }

    /**
     * Set author
     *
     * @param User $author
     *
     * @return Content
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return User
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

    /**
     * @ORM\PostLoad()
     */
    public function getHasThumbnail()
    {
        $this->hasThumbnail = ($this->thumbnail != '');

        return $this->hasThumbnail;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("fields")
     *
     * @return ArrayCollection|FieldValue[]
     */
    public function getFieldsValues()
    {
        $res = array();
        foreach ($this->fieldvalues as $fieldvalue) {
            $res[$fieldvalue->getField()->getName()] = $fieldvalue->getValue();
        }

        return $res;
    }

    /**
     * @internal Category $category
     * @return string
     */
    public function getCategoriesClass()
    {
        $cat_temp = array();
        foreach ($this->categories as $category) {
            $cat_temp[] = $category->getUrl();
        }

        return implode(' ', $cat_temp);
    }

    /**
     * Renvoie le titre des catégories du contenu
     * séparés par le sépérateur passé en paramètre
     *
     * @param string $separator
     * @param bool $link
     * @return string
     */
    public function getCategoriesArticle($separator = ' / ', $link = false)
    {
        $cat_temp = array();
        $i = 0;
        foreach ($this->categories as $category) {
            $cat_temp[$i] = ($link) ? '<a href="/'.$category->getUrl().'">' : '';
            $cat_temp[$i] .= $category->getTitle();
            $cat_temp[$i] .= ($link) ? '</a>' : '';
        }

        return implode($separator, $cat_temp);
    }

    /**
     * Set featured
     *
     * @param boolean $featured
     *
     * @return Content
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;

        return $this;
    }

    /**
     * Get featured
     *
     * @return boolean
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * Set chapo
     *
     * @param string $chapo
     *
     * @return Content
     */
    public function setChapo($chapo)
    {
        $this->chapo = $chapo;

        return $this;
    }

    /**
     * Get chapo
     *
     * @return string
     */
    public function getChapo()
    {
        return $this->chapo;
    }

    /**
     * Set tempsLecture
     *
     * @param float $tempsLecture
     *
     * @return Content
     */
    public function setTempsLecture($tempsLecture)
    {
        $this->temps_lecture = $tempsLecture;

        return $this;
    }

    /**
     * Get tempsLecture
     *
     * @return float
     */
    public function getTempsLecture()
    {
        return $this->temps_lecture;
    }
}
