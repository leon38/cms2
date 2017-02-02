<?php
namespace CMS\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CMS\Bundle\CoreBundle\Entity\Repository\UserMetaRepository")
 * @ORM\Table(name="usermeta")
 */
class UserMeta
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="metas", cascade={"remove"})
     * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="cascade")
     */
    private $user;

    /**
     * @ORM\Column(name="meta_key", type="string", length=255)
     */
    private $meta_key;

    /**
     * @ORM\Column(name="meta_value", type="text")
     */
    private $meta_value;

    /**
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;



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
     * Set meta_key
     *
     * @param string $metaKey
     * @return UserMeta
     */
    public function setMetaKey($metaKey)
    {
        $this->meta_key = $metaKey;

        return $this;
    }

    /**
     * Get meta_key
     *
     * @return string
     */
    public function getMetaKey()
    {
        return $this->meta_key;
    }

    /**
     * Set meta_value
     *
     * @param string $metaValue
     * @return UserMeta
     */
    public function setMetaValue($metaValue)
    {
        $this->meta_value = $metaValue;

        return $this;
    }

    /**
     * Get meta_value
     *
     * @return string
     */
    public function getMetaValue()
    {
        return $this->meta_value;
    }

    /**
     * Set user
     *
     * @param \CMS\Bundle\CoreBundle\Entity\User $user
     * @return UserMeta
     */
    public function setUser(\CMS\Bundle\CoreBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \CMS\Bundle\CoreBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function __toString()
    {
        return $this->meta_key;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return UserMeta
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
