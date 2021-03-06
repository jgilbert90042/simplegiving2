<?php

namespace Site\FundingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Site\FundingBundle\Entity\Funding
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Funding
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
     * @var integer $donor
     *
     * @ORM\ManyToOne(targetEntity="\Site\DonorBundle\Entity\Donor")
     * @ORM\JoinColumn(name="donor", referencedColumnName="id")
     */
    private $donor;

    /**
     * @var integer $beneficiary
     *
     * @ORM\ManyToOne(targetEntity="\Site\BeneficiaryBundle\Entity\Beneficiary")
     * @ORM\JoinColumn(name="beneficiary", referencedColumnName="id")
     */
    private $beneficiary;

    /**
     * @var float $amount
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @var string $currency
     *
     * @ORM\Column(name="currency", type="string", nullable=true)
     */
    private $currency;

    /**
     * @var integer $status
     *
     * @ORM\ManyToOne(targetEntity="\Site\FundingBundle\Entity\FundingStatus")
     * @ORM\JoinColumn(name="status", referencedColumnName="id")
     */
    private $status;

    /**
     * @var integer $method
     *
     * @ORM\ManyToOne(targetEntity="\Site\FundingBundle\Entity\FundingMethod")
     * @ORM\JoinColumn(name="method", referencedColumnName="id")
     */
    private $method;

    /**
     * @var DateTime $initiated
     *
     * @ORM\Column(name="initiated", type="datetime")
     */
     private $initiated;

    /**
     * @var DateTime $updated
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
     private $updated;

    /**
     * @var string $notes
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
     private $notes;

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
     * Set amount
     *
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * Get amount
     *
     * @return float 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set initiated
     *
     * @param datetime $initiated
     */
    public function setInitiated($initiated)
    {
        $this->initiated = $initiated;
    }

    /**
     * Get initiated
     *
     * @return datetime 
     */
    public function getInitiated()
    {
        return $this->initiated;
    }

    /**
     * Set updated
     *
     * @param datetime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * Get updated
     *
     * @return datetime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set notes
     *
     * @param text $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * Get notes
     *
     * @return text 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set donor
     *
     * @param Site\DonorBundle\Entity\Donor $donor
     */
    public function setDonor(\Site\DonorBundle\Entity\Donor $donor)
    {
        $this->donor = $donor;
    }

    /**
     * Get donor
     *
     * @return Site\DonorBundle\Entity\Donor 
     */
    public function getDonor()
    {
        return $this->donor;
    }

    /**
     * Set beneficiary
     *
     * @param Site\BeneficiaryBundle\Entity\Beneficiary $beneficiary
     */
    public function setBeneficiary(\Site\BeneficiaryBundle\Entity\Beneficiary $beneficiary)
    {
        $this->beneficiary = $beneficiary;
    }

    /**
     * Get beneficiary
     *
     * @return Site\BeneficiaryBundle\Entity\Beneficiary 
     */
    public function getBeneficiary()
    {
        return $this->beneficiary;
    }

    /**
     * Set status
     *
     * @param Site\FundingBundle\Entity\FundingStatus $status
     */
    public function setStatus(\Site\FundingBundle\Entity\FundingStatus $status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return Site\FundingBundle\Entity\FundingStatus 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set method
     *
     * @param \Site\FundingBundle\Entity\FundingMethod $method
     * @return Funding
     */
    public function setMethod(\Site\FundingBundle\Entity\FundingMethod $method = null)
    {
        $this->method = $method;
    
        return $this;
    }

    /**
     * Get method
     *
     * @return \Site\FundingBundle\Entity\FundingMethod 
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set currency
     *
     * @param string $currency
     * @return Funding
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    
        return $this;
    }

    /**
     * Get currency
     *
     * @return string 
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}
