<?php
namespace CMS\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as JMS;


/**
 * @ORM\Entity(repositoryClass="CMS\Bundle\CoreBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields="user_login",message="Your E-Mail adress has already been registered")
 * @JMS\ExclusionPolicy("all")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    protected $id;

    /**
     * @JMS\Exclude
     * @ORM\Column(name="user_login", type="string", length=60)
     */
    private $user_login;

	/**
	 * @ORM\Column(name="user_pass", type="string", length=64)
	 */
	private $user_pass;

    /**
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

	/**
     * @JMS\Expose
     * @JMS\Type("string")
	 * @ORM\Column(name="user_nicename", type="string", length=50, nullable=true)
	 */
	private $user_nicename;

	/**
	 * @ORM\Column(name="user_email", type="string", length=100)
	 */
	private $user_email;

	/**
	 * @ORM\Column(name="user_url", type="string", length=100, nullable=true)
	 */
	private $user_url;

	/**
	 * @ORM\Column(name="user_registered", type="datetime")
	 */
	 private $user_registered;

	/**
	 * @ORM\Column(name="user_activation_key", type="string", length=255)
	 */
	private $user_activation_key;

	/**
	 * @ORM\Column(name="user_status", type="boolean")
	 */
	private $user_status = 0;

	/**
	 * @ORM\Column(name="display_name", type="string", length=250, nullable=true)
	 */
	private $display_name = "complete_name";

	/**
	 * @ORM\ManyToMany(targetEntity = "Role", mappedBy = "users", cascade={"persist"})
     * @ORM\JoinTable(name="role_user")
	 *
	 * @var ArrayCollection $roles;
	 */
	private $roles;

    /**
     * @ORM\OneToMany(targetEntity="UserMeta", mappedBy="user", cascade={"persist", "remove"})
     */
    private $metas;

    /**
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity="CMS\Bundle\ContentBundle\Entity\Content", mappedBy="author")
     */
     private $posts;

    private $plainPassword;

    public $file;

    public function getAbsolutePath()
    {
        return null === $this->avatar ? null : $this->getUploadRootDir().'/'.$this->avatar;
    }

    public function getWebPath()
    {
        return null === $this->avatar ? null : $this->getUploadDir().'/'.$this->avatar;
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
        return 'uploads/avatars/'.$this->id;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            $this->avatar = $this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // vous devez lancer une exception ici si le fichier ne peut pas
        // être déplacé afin que l'entité ne soit pas persistée dans la
        // base de données comme le fait la méthode move() de UploadedFile
        $this->file->move($this->getUploadRootDir(), $this->id.'.'.$this->file->guessExtension());

        unset($this->file);
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->filenameForRemove = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($this->filenameForRemove) {
            unlink($this->filenameForRemove);
        }
    }
    /**
     * @ORM\PrePersist()
     */
    public function setUserRegisteredValue()
    {
        $this->user_registered = new \DateTime();
    }


	public function getUsername()
	{
		return $this->user_login;
	}

	public function getPassword()
	{
		return $this->user_pass;
	}

	public function getRoles()
	{
		return $this->roles->toArray();
	}


	 /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

   /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->user_status;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->salt  = md5(uniqid(null, true));
        $this->user_activation_key = sha1(uniqid(null, true));
        $this->user_status = 0;
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
     * Set user_login
     *
     * @param string $userLogin
     * @return User
     */
    public function setUserLogin($userLogin)
    {
        $this->user_login = $userLogin;

        return $this;
    }

    /**
     * Get user_login
     *
     * @return string
     */
    public function getUserLogin()
    {
        return $this->user_login;
    }

    /**
     * Set user_pass
     *
     * @param string $userPass
     * @return User
     */
    public function setUserPass($userPass)
    {
        $this->user_pass = $userPass;

        return $this;
    }

    /**
     * Get user_pass
     *
     * @return string
     */
    public function getUserPass()
    {
        return $this->user_pass;
    }

    /**
     * Set user_nicename
     *
     * @param string $userNicename
     * @return User
     */
    public function setUserNicename($userNicename)
    {
        $this->user_nicename = $userNicename;

        return $this;
    }

    /**
     * Get user_nicename
     *
     * @return string
     */
    public function getUserNicename()
    {
        return $this->user_nicename;
    }

    /**
     * Set user_email
     *
     * @param string $userEmail
     * @return User
     */
    public function setUserEmail($userEmail)
    {
        $this->user_email = $userEmail;

        return $this;
    }

    /**
     * Get user_email
     *
     * @return string
     */
    public function getUserEmail()
    {
        return $this->user_email;
    }

    /**
     * Set user_url
     *
     * @param string $userUrl
     * @return User
     */
    public function setUserUrl($userUrl)
    {
        $this->user_url = $userUrl;

        return $this;
    }

    /**
     * Get user_url
     *
     * @return string
     */
    public function getUserUrl()
    {
        return $this->user_url;
    }

    /**
     * Set user_registered
     *
     * @param \DateTime $userRegistered
     * @return User
     */
    public function setUserRegistered($userRegistered)
    {
        $this->user_registered = $userRegistered;

        return $this;
    }

    /**
     * Get user_registered
     *
     * @return \DateTime
     */
    public function getUserRegistered()
    {
        return $this->user_registered;
    }

    /**
     * Set user_activation_key
     *
     * @param string $userActivationKey
     * @return User
     */
    public function setUserActivationKey($userActivationKey)
    {
        if ($userActivationKey !== null) {
            $this->user_activation_key = $userActivationKey;
        } else {
            $this->user_activation_key = sha1(uniqid(null, true));
        }

        return $this;
    }

    /**
     * Get user_activation_key
     *
     * @return string
     */
    public function getUserActivationKey()
    {
        return $this->user_activation_key;
    }

    /**
     * Set user_status
     *
     * @param boolean $userStatus
     * @return User
     */
    public function setUserStatus($userStatus)
    {
        $this->user_status = $userStatus;

        return $this;
    }

    /**
     * Get user_status
     *
     * @return boolean
     */
    public function getUserStatus()
    {
        return $this->user_status;
    }

    /**
     * Set display_name
     *
     * @param string $displayName
     * @return User
     */
    public function setDisplayName($displayName)
    {
        $this->display_name = $displayName;

        return $this;
    }

    /**
     * Get display_name
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->display_name;
    }

    /**
     * Add roles
     *
     * @param \CMS\Bundle\CoreBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\CMS\Bundle\CoreBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \CMS\Bundle\CoreBundle\Entity\Role $roles
     */
    public function removeRole(\CMS\Bundle\CoreBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    public function __toString()
    {
        switch($this->display_name) {
            case 'complete_name':
                if ($this->__get('firstname') !== null and $this->__get('lastname') !== null)
                    return $this->__get('firstname')->getMetaValue().' '.$this->__get('lastname')->getMetaValue();
                else if ($this->user_nicename != '')
                    return $this->user_nicename;
                else
                    $this->user_email;
            case 'login':
                return $this->user_login;
            case 'nicename':
                return $this->user_nicename;
            default:
                return $this->user_email;
        }
    }

    /**
     * Add metas
     *
     * @param \CMS\Bundle\CoreBundle\Entity\UserMeta $metas
     * @return User
     */
    public function addMeta(\CMS\Bundle\CoreBundle\Entity\UserMeta $metas)
    {
        $this->metas[] = $metas;

        return $this;
    }

    /**
     * Remove metas
     *
     * @param \CMS\Bundle\CoreBundle\Entity\UserMeta $metas
     */
    public function removeMeta(\CMS\Bundle\CoreBundle\Entity\UserMeta $metas)
    {
        $this->metas->removeElement($metas);
    }

    /**
     * Get metas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetas()
    {
        return $this->metas;
    }

    public function getMeta($meta_key) {
        foreach($this->metas as $meta) {
            if ($meta->getMetaKey() == $meta_key) {
                return $meta->getMetaValue();
            }
        }
        return '';
    }

    public function __get($meta_key)
    {
        foreach($this->metas as $meta) {
            if ($meta->getMetaKey() == $meta_key) {
                return $meta;
            }
        }
        return null;
    }

    public function __set($meta_key, $meta_value)
    {
        foreach($this->metas as $meta) {
            preg_match("/metas_(.*)/", $meta_key, $output_array);
            if (!empty($output_array)) {
                $meta_key = $output_array[1];
            }
            if ($meta->getMetaKey() == $meta_key) {
                $meta->setMetaValue($meta_value);
            }
        }
        return $this;
    }

    public function __isset($meta_key)
    {
        foreach($this->metas as $meta) {
            preg_match("/metas_(.*)/", $meta_key, $output_array);
            if (!empty($output_array)) {
                return true;
            }
        }
        return false;
    }

    public function rolesDisplay()
    {
        return implode(', ', $this->getRoles());
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set plain password
     *
     * @param  string $plainPassword
     * @return User
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Get plain password
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Add post
     *
     * @param \CMS\Bundle\ContentBundle\Entity\Content $post
     *
     * @return User
     */
    public function addPost(\CMS\Bundle\ContentBundle\Entity\Content $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \CMS\Bundle\ContentBundle\Entity\Content $post
     */
    public function removePost(\CMS\Bundle\ContentBundle\Entity\Content $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }
}
