<?php

namespace Site\VerificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Site\VerificationBundle\Entity\Verifier
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Verifier
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     private $id;

    /**
     * @var integer $user
     *
     * @ORM\OneToOne(targetEntity="\Site\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string $PPButton
     *
     * @ORM\Column(name="PPButton", type="string", length=255, nullable=true)
     */
    private $PPButton;

    /**
     * @var string $PPEmail
     *
     * @ORM\Column(name="PPEmail", type="string", length=255, nullable=true)
     */
    private $PPEmail;

    /**
     * @ORM\ManyToOne(targetEntity="\Site\ChurchBundle\Entity\Church")
     * @ORM\JoinColumn(name="church", referencedColumnName="id")
     */
    private $church;

     /**
     * @var string $first_name
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $first_name;

     /**
     * @var string $last_name
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $last_name;

    /**
     * @var string $position
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $position;

    /**
     * @var string $phone
     *
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $phone;

    /**
     * @var string $fax
     *
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $fax;

    /**
     * @ORM\Column(name="activated", type="boolean")
     */
    private $activated;

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
     * Set user
     *
     * @param Site\UserBundle\Entity\User $user
     */
    public function setUser(\Site\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return Site\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return Verifier
     */
    public function setPosition($position)
    {
        $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Verifier
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set first_name
     *
     * @param string $firstName
     * @return Verifier
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;
    
        return $this;
    }

    /**
     * Get first_name
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set last_name
     *
     * @param string $lastName
     * @return Verifier
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;
    
        return $this;
    }

    /**
     * Get last_name
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return Verifier
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    
        return $this;
    }

    /**
     * Get fax
     *
     * @return string 
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set PPEmail
     *
     * @param string $pPEmail
     * @return Verifier
     */
    public function setPPEmail($pPEmail)
    {
        $this->PPEmail = $pPEmail;
    
        return $this;
    }

    /**
     * Get PPEmail
     *
     * @return string 
     */
    public function getPPEmail()
    {
        return $this->PPEmail;
    }

    /**
     * Set PPButton
     *
     * @param string $pPButton
     * @return Verifier
     */
    public function setPPButton($pPButton)
    {
        $this->PPButton = $pPButton;
    
        return $this;
    }

    /**
     * Get PPButton
     *
     * @return string 
     */
    public function getPPButton()
    {
        return $this->PPButton;
    }

    /**
     * Set activated
     *
     * @param boolean $activated
     * @return Verifier
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;
    
        return $this;
    }

    /**
     * Get activated
     *
     * @return boolean 
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * Set church
     *
     * @param integer $church
     * @return Verifier
     */
    public function setChurch($church)
    {
        $this->church = $church;
    
        return $this;
    }

    /**
     * Get church
     *
     * @return integer 
     */
    public function getChurch()
    {
        return $this->church;
    }
}
