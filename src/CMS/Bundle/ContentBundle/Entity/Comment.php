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
}