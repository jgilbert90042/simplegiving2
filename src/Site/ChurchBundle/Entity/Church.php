<?php

namespace Site\ChurchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Site\ChurchBundle\Entity\Church
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Church
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
     * @var integer $name
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string $address
     *
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @var string $city
     *
     * @ORM\Column(type="string", length=255)
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
     * @ORM\Column(type="string", length=10)
     */
    private $zip;

    /**
     * 
     *
     * @ORM\ManyToOne(targetEntity="\Site\ChurchBundle\Entity\ChurchDenomination")
     * @ORM\JoinColumn(name="denomination", referencedColumnName="id")
     */
    private $denomination;

    /**
     * @var string $phone
     *
     * @ORM\Column(type="string", length=25)
     */
    private $phone;

    /**
     * @var string $fax
     *
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $fax;

    /**
     * @var string $email
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string $website
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @ORM\ManyToMany(targetEntity="\Site\VerificationBundle\Entity\Verifier")
     * @ORM\JoinTable(name="churches_verifier",
     *      joinColumns={@ORM\JoinColumn(name="church", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="verifier", referencedColumnName="id", unique=true)}
     *      )
     */
     private $verifiers;

    /**
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;


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
     * Set name
     *
     * @param string $name
     * @return Church
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Church
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
     * @return Church
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
     * Set phone
     *
     * @param string $phone
     * @return Church
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
     * Set email
     *
     * @param string $email
     * @return Church
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
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
     * Set website
     *
     * @param string $website
     * @return Church
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    
        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return Church
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
     * Set zip
     *
     * @param string $zip
     * @return Church
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
     * Constructor
     */
    public function __construct()
    {
        $this->verifiers = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add verifiers
     *
     * @param \Site\VerificationBundle\Entity\Verifier $verifiers
     * @return Church
     */
    public function addVerifier(\Site\VerificationBundle\Entity\Verifier $verifiers)
    {
        $this->verifiers[] = $verifiers;
    
        return $this;
    }

    /**
     * Remove verifiers
     *
     * @param \Site\VerificationBundle\Entity\Verifier $verifiers
     */
    public function removeVerifier(\Site\VerificationBundle\Entity\Verifier $verifiers)
    {
        $this->verifiers->removeElement($verifiers);
    }

    /**
     * Get verifiers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVerifiers()
    {
        return $this->verifiers;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     * @return Church
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    
        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean 
     */
    public function getVisible()
    {
        return $this->visible;
    }



    /**
     * Set state
     *
     * @param string $state
     * @return Church
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
     * Set denomination
     *
     * @param \Site\ChurchBundle\Entity\ChurchDenomination $denomination
     * @return Church
     */
    public function setDenomination(\Site\ChurchBundle\Entity\ChurchDenomination $denomination = null)
    {
        $this->denomination = $denomination;
    
        return $this;
    }

    /**
     * Get denomination
     *
     * @return \Site\ChurchBundle\Entity\ChurchDenomination 
     */
    public function getDenomination()
    {
        return $this->denomination;
    }
}