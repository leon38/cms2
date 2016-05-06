<?php
namespace CMS\Bundle\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CMS\Bundle\ContactBundle\Entity\Repository\MessageRepository")
 * @ORM\Table(name="messages")
 * @ORM\HasLifecycleCallBacks
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="sender", type="string", length=255)
     */
    private $sender;

    /**
     * @ORM\Column(name="receivers", type="array")
     */
    private $receivers;

    /**
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @ORM\Column(name="sent_date", type="datetime")
     */
    private $sent_date;

    /**
     * @ORM\Column(name="status", type="integer")
     */
    private $status = 2;

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
     * Set sender
     *
     * @param string $sender
     * @return Message
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
     * Set receivers
     *
     * @param array $receivers
     * @return Message
     */
    public function setReceivers($receivers)
    {
        $this->receivers = $receivers;

        return $this;
    }

    /**
     * Get receivers
     *
     * @return array
     */
    public function getReceivers()
    {
        return $this->receivers;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Message
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
     * Set message
     *
     * @param string $message
     * @return Message
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set sent_date
     *
     * @param \DateTime $sentDate
     * @return Message
     */
    public function setSentDate($sentDate)
    {
        $this->sent_date = $sentDate;

        return $this;
    }

    /**
     * Get sent_date
     *
     * @return \DateTime
     */
    public function getSentDate()
    {
        return $this->sent_date;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Message
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @ORM\PrePersist
     */
    public function initSentDate()
    {
        $this->sent_date = new \DateTime();
    }
}
