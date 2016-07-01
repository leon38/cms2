<?php
namespace CMS\Bundle\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ContactForm
 *
 * @ORM\Table(name="contact_form")
 * @ORM\Entity(repositoryClass="CMS\Bundle\ContactBundle\Repository\ContactFormsRepository")
 */
class ContactForm
{
  /**
   * @var int
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var string
   *
   * @ORM\Column(name="title", type="string", length=100)
   */
  private $title;

  /**
   * @var string
   * @Gedmo\Slug(fields={"title", "id"})
   * @ORM\Column(name="alias", type="string", length=100, unique=true)
   */
  private $alias;

  /**
   * @var string
   *
   * @ORM\Column(name="tag", type="string", length=100, unique=true)
   */
  private $tag;

  /**
   * @var string
   *
   * @ORM\Column(name="html_form", type="text")
   */
  private $htmlForm;

  /**
   * @var string
   *
   * @ORM\Column(name="sender", type="string", length=100, unique=true)
   */
  private $sender;

  /**
   * @var string
   *
   * @ORM\Column(name="subject", type="string", length=100, unique=true)
   */
  private $subject;

  /**
   * @var string
   *
   * @ORM\Column(name="html_message", type="text")
   */
  private $htmlMessage;

  /**
   * @var \DateTime
   * @Gedmo\Timestampable(on="create")
   * @ORM\Column(name="created", type="datetime")
   */
  private $created;

  /**
   * @var \DateTime
   * @Gedmo\Timestampable(on="update")
   * @ORM\Column(name="modified", type="datetime")
   */
  private $modified;

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
   * @return ContactForm
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
   * Set alias
   *
   * @param string $alias
   *
   * @return ContactForm
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

  /**
   * Set tag
   *
   * @param string $tag
   *
   * @return ContactForm
   */
  public function setTag($tag)
  {
    $this->tag = $tag;

    return $this;
  }

  /**
   * Get tag
   *
   * @return string
   */
  public function getTag()
  {
    return $this->tag;
  }

  /**
   * Set htmlForm
   *
   * @param string $htmlForm
   *
   * @return ContactForm
   */
  public function setHtmlForm($htmlForm)
  {
    $this->htmlForm = $htmlForm;

    return $this;
  }

  /**
   * Get htmlForm
   *
   * @return string
   */
  public function getHtmlForm()
  {
    return $this->htmlForm;
  }

  /**
   * Set sender
   *
   * @param string $sender
   *
   * @return ContactForm
   */
  public function setSender($sender)
  {
    $this->sender = $sender;

    return $this;
  }

  /**
   * Get sender
   *
   * @return string
   */
  public function getSender()
  {
    return $this->sender;
  }

  /**
   * Set subject
   *
   * @param string $subject
   *
   * @return ContactForm
   */
  public function setSubject($subject)
  {
    $this->subject = $subject;

    return $this;
  }

  /**
   * Get subject
   *
   * @return string
   */
  public function getSubject()
  {
    return $this->subject;
  }

  /**
   * Set htmlMessage
   *
   * @param string $htmlMessage
   *
   * @return ContactForm
   */
  public function setHtmlMessage($htmlMessage)
  {
    $this->htmlMessage = $htmlMessage;

    return $this;
  }

  /**
   * Get htmlMessage
   *
   * @return string
   */
  public function getHtmlMessage()
  {
    return $this->htmlMessage;
  }

  /**
   * Set created
   *
   * @param \DateTime $created
   *
   * @return ContactForm
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
   * @return ContactForm
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
}
