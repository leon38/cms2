<?php
namespace CMS\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="roles")
 */
class Role implements RoleInterface
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $role_name;

    /**
     * @ORM\Column(name="role_nicename", type="string", length=60)
     */
    private $role_nicename;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="roles")
     * @ORM\JoinTable(name="roles_users")
     */
    protected $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
    * @see RoleInterface
    */
    public function getRole()
    {
        return $this->role_name;
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
     * Set role_name
     *
     * @param string $roleName
     * @return Role
     */
    public function setRoleName($roleName)
    {
        $this->role_name = $roleName;

        return $this;
    }

    /**
     * Get role_name
     *
     * @return string
     */
    public function getRoleName()
    {
        return $this->role_name;
    }

    /**
     * Set role_nicename
     *
     * @param string $roleNicename
     * @return Role
     */
    public function setRoleNicename($roleNicename)
    {
        $this->role_nicename = $roleNicename;

        return $this;
    }

    /**
     * Get role_nicename
     *
     * @return string
     */
    public function getRoleNicename()
    {
        return $this->role_nicename;
    }

    /**
     * Add users
     *
     * @param \CMS\Bundle\CoreBundle\Entity\User $users
     * @return Role
     */
    public function addUser(\CMS\Bundle\CoreBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \CMS\Bundle\CoreBundle\Entity\User $users
     */
    public function removeUser(\CMS\Bundle\CoreBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function __toString()
    {
        return $this->role_nicename;
    }
}
