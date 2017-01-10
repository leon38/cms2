<?php
/**
 * User: DCA
 * Date: 16/12/2016
 * Time: 15:01
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CMS\Bundle\ContentBundle\Entity\Repository\CommentRepository")
 * @ORM\Table(name="comment")
 * @ORM\HasLifecycleCallbacks
 **/
class Comment
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $pseudo
     * @ORM\Column(name="pseudo", type="string")
     * @Assert\NotBlank(message="cms.content.comment.not_blank.pseudo")
     */
    protected $pseudo;

    /**
     * @var string $email
     * @ORM\Column(name="email", type="string")
     * @Assert\NotBlank(message="cms.content.comment.not_blank.email")
     * @Assert\Email(message="cms.content.valid.email")
     */
    protected $email;

    /**
     * @var \DateTime $date_added
     * @ORM\Column(name="date_added", type="datetime")
     */
    protected $date_added;

    /**
     * @var String $message
     * @ORM\Column(name="message", type="string")
     * @Assert\NotBlank(message="cms.content.comment.not_blank.message")
     */
    protected $message;

    /**
     * @var Content $content
     * @ORM\ManyToOne(targetEntity="Content", inversedBy="comments")
     */
    protected $content;

    /**
     * @var int likes
     * @ORM\Column(name="likes", type="integer")
     */
    protected $likes = 0;


    public function __construct()
    {
        $this->date_added = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param string $pseudo
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }

    /**
     * @param \DateTime $date_added
     */
    public function setDateAdded($date_added)
    {
        $this->date_added = $date_added;
    }

    /**
     * @return String
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param String $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param Content $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param int $likes
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
    }

}