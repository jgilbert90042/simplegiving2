<?php

namespace Site\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Site\UserBundle\Entity\User
 *
 * @ORM\Table(name="site_users")
 * @ORM\Entity
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $salt;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var datetime $created
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;


    /**
     * @ORM\Column(type="string", length=15)
     */
    private $created_ip;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="blockedFromMe")
     */
    private $blocked;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="blocked")
     * @ORM\JoinTable(name="blocked",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="blocked_user_id", referencedColumnName="id")}
     *      )
     */
    private $blockedFromMe;

    public function __construct()
    {
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
        $this->blocked = new \Doctrine\Common\Collections\ArrayCollection();
        $this->blockedFromMe = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array($this->role);
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @inheritDoc
     */
    public function equals(UserInterface $user)
    {
        return $this->username === $user->getUsername();
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
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set role
     *
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return User
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
     * Set created_ip
     *
     * @param string $createdIp
     * @return User
     */
    public function setCreatedIp($createdIp)
    {
        $this->created_ip = $createdIp;
    
        return $this;
    }

    /**
     * Get created_ip
     *
     * @return string 
     */
    public function getCreatedIp()
    {
        return $this->created_ip;
    }

    /**
     * Add blocked
     *
     * @param \Site\UserBundle\Entity\User $blocked
     * @return User
     */
    public function addBlocked(\Site\UserBundle\Entity\User $blocked)
    {
        $this->blocked[] = $blocked;
    
        return $this;
    }

    /**
     * Remove blocked
     *
     * @param \Site\UserBundle\Entity\User $blocked
     */
    public function removeBlocked(\Site\UserBundle\Entity\User $blocked)
    {
        $this->blocked->removeElement($blocked);
    }

    /**
     * Get blocked
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBlocked()
    {
        return $this->blocked;
    }

    /**
     * Add blockedFromMe
     *
     * @param \Site\UserBundle\Entity\User $blockedFromMe
     * @return User
     */
    public function addBlockedFromMe(\Site\UserBundle\Entity\User $blockedFromMe)
    {
        $this->blockedFromMe[] = $blockedFromMe;
    
        return $this;
    }

    /**
     * Remove blockedFromMe
     *
     * @param \Site\UserBundle\Entity\User $blockedFromMe
     */
    public function removeBlockedFromMe(\Site\UserBundle\Entity\User $blockedFromMe)
    {
        $this->blockedFromMe->removeElement($blockedFromMe);
    }

    /**
     * Get blockedFromMe
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBlockedFromMe()
    {
        return $this->blockedFromMe;
    }
}