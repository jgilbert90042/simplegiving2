<?php

namespace Site\BeneficiaryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Site\BeneficiaryBundle\Entity\Beneficiary
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Beneficiary
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
     * @var integer $verifier
     *
     * @ORM\ManyToOne(targetEntity="\Site\VerificationBundle\Entity\Verifier")
     * @ORM\JoinColumn(name="verifier", referencedColumnName="id")
     */
    private $verifier;

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
     * @var string $ssn
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $ssn;

    /**
     * @var string $address
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string $city
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="\Site\MiscBundle\Entity\State")
     * @ORM\JoinColumn(name="state", referencedColumnName="abbr")
     * @ORM\Column(type="string", length=2)
     */
    private $state;

    /**
     * @var string $zip
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $zip;

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
     * @var integer $marital_status
     *
     * @ORM\ManyToOne(targetEntity="\Site\MiscBundle\Entity\MaritalStatus")
     * @ORM\JoinColumn(name="marital_status", referencedColumnName="id")
     */
    private $marital_status;

    /**
     * @var integer $dependants
     *
     * @ORM\Column(type="integer")
     */
    private $dependants;

    /**
     * @var float $need
     *
     * @ORM\Column(name="need", type="float")
     */
    private $need;

    /**
     * @var float $needOrig
     *
     * @ORM\Column(name="needOrig", type="float")
     */
    private $needOrig;

    /**
     * @var string $needDesc
     *
     * @ORM\Column(name="needDesc", type="text", nullable=true)
     */
    private $needDesc;

    /**
     * @var integer $needUrgency
     *
     * @ORM\Column(name="needUrgency", type="integer")
     */
    private $needUrgency;

    /**
     * @ORM\Column(name="searchable", type="boolean")
     */
    private $searchable;

    /**
     * @var DateTime $updated
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
     private $updated;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->state = '';
        $this->dependants = 0;
        $this->need = 0;
        $this->needOrig = 0;
        $this->needUrgency = 0;
        $this->searchable = 0;
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
     * Set first_name
     *
     * @param string $firstName
     * @return Beneficiary
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
     * @return Beneficiary
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
     * Set ssn
     *
     * @param integer $ssn
     * @return Beneficiary
     */
    public function setSsn($ssn)
    {
        $this->ssn = $ssn;
    
        return $this;
    }

    /**
     * Get ssn
     *
     * @return integer 
     */
    public function getSsn()
    {
        return $this->ssn;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Beneficiary
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Beneficiary
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Beneficiary
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set zip
     *
     * @param string $zip
     * @return Beneficiary
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    
        return $this;
    }

    /**
     * Get zip
     *
     * @return string 
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Beneficiary
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
     * @return Beneficiary
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
     * Set dependants
     *
     * @param integer $dependants
     * @return Beneficiary
     */
    public function setDependants($dependants)
    {
        $this->dependants = $dependants;
    
        return $this;
    }

    /**
     * Get dependants
     *
     * @return integer 
     */
    public function getDependants()
    {
        return $this->dependants;
    }

    /**
     * Set need
     *
     * @param float $need
     * @return Beneficiary
     */
    public function setNeed($need)
    {
        $this->need = $need;
    
        return $this;
    }

    /**
     * Get need
     *
     * @return float 
     */
    public function getNeed()
    {
        return $this->need;
    }

    /**
     * Set needOrig
     *
     * @param float $needOrig
     * @return Beneficiary
     */
    public function setNeedOrig($needOrig)
    {
        $this->needOrig = $needOrig;
    
        return $this;
    }

    /**
     * Get needOrig
     *
     * @return float 
     */
    public function getNeedOrig()
    {
        return $this->needOrig;
    }

    /**
     * Set needDesc
     *
     * @param string $needDesc
     * @return Beneficiary
     */
    public function setNeedDesc($needDesc)
    {
        $this->needDesc = $needDesc;
    
        return $this;
    }

    /**
     * Get needDesc
     *
     * @return string 
     */
    public function getNeedDesc()
    {
        return $this->needDesc;
    }

    /**
     * Set needUrgency
     *
     * @param integer $needUrgency
     * @return Beneficiary
     */
    public function setNeedUrgency($needUrgency)
    {
        $this->needUrgency = $needUrgency;
    
        return $this;
    }

    /**
     * Get needUrgency
     *
     * @return integer 
     */
    public function getNeedUrgency()
    {
        return $this->needUrgency;
    }

    /**
     * Set searchable
     *
     * @param boolean $searchable
     * @return Beneficiary
     */
    public function setSearchable($searchable)
    {
        $this->searchable = $searchable;

        return $this;
    }

    /**
     * Get searchable
     *
     * @return boolean 
     */
    public function getSearchable()
    {
        return $this->searchable;
    }

    /**
     * Set user
     *
     * @param \Site\UserBundle\Entity\User $user
     * @return Beneficiary
     */
    public function setUser(\Site\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Site\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set verifier
     *
     * @param \Site\VerificationBundle\Entity\Verifier $verifier
     * @return Beneficiary
     */
    public function setVerifier(\Site\VerificationBundle\Entity\Verifier $verifier = null)
    {
        $this->verifier = $verifier;
    
        return $this;
    }

    /**
     * Get verifier
     *
     * @return \Site\VerificationBundle\Entity\Verifier 
     */
    public function getVerifier()
    {
        return $this->verifier;
    }

    /**
     * Set marital_status
     *
     * @param \Site\MiscBundle\Entity\MaritalStatus $maritalStatus
     * @return Beneficiary
     */
    public function setMaritalStatus(\Site\MiscBundle\Entity\MaritalStatus $maritalStatus = null)
    {
        $this->marital_status = $maritalStatus;
    
        return $this;
    }

    /**
     * Get marital_status
     *
     * @return \Site\MiscBundle\Entity\MaritalStatus 
     */
    public function getMaritalStatus()
    {
        return $this->marital_status;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Beneficiary
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}