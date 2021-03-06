<?php

namespace Site\DonorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Site\DonorBundle\Entity\Donor
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Donor
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
     * @var integer $search_profile
     *
     * @ORM\OneToOne(targetEntity="\Site\SearchBundle\Entity\SearchProfile")
     * @ORM\JoinColumn(name="search_profile", referencedColumnName="id")
     */
    private $search_profile;

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
     * @var string $church
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $church;

     /**
     * @var integer $searchCap
     *
     * @ORM\Column(type="integer")
     */
    private $searchCap;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->searchCap = 20;
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
     * Set first_name
     *
     * @param string $firstName
     * @return Donor
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
     * @return Donor
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
     * Set phone
     *
     * @param string $phone
     * @return Donor
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
     * Set fax
     *
     * @param string $fax
     * @return Donor
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
     * Set church
     *
     * @param string $church
     * @return Donor
     */
    public function setChurch($church)
    {
        $this->church = $church;
    
        return $this;
    }

    /**
     * Get church
     *
     * @return string 
     */
    public function getChurch()
    {
        return $this->church;
    }

    /**
     * Set search_profile
     *
     * @param \Site\SearchBundle\Entity\SearchProfile $searchProfile
     * @return Donor
     */
    public function setSearchProfile(\Site\SearchBundle\Entity\SearchProfile $searchProfile = null)
    {
        $this->search_profile = $searchProfile;
    
        return $this;
    }

    /**
     * Get search_profile
     *
     * @return \Site\SearchBundle\Entity\SearchProfile 
     */
    public function getSearchProfile()
    {
        return $this->search_profile;
    }

    /**
     * Set searchCap
     *
     * @param integer $searchCap
     * @return Donor
     */
    public function setSearchCap($searchCap)
    {
        $this->searchCap = $searchCap;
    
        return $this;
    }

    /**
     * Get searchCap
     *
     * @return integer 
     */
    public function getSearchCap()
    {
        return $this->searchCap;
    }
}