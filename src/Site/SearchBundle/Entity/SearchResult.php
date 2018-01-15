<?php

namespace Site\SearchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Site\SearchBundle\Entity\SearchResult
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class SearchResult
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
     * @ORM\ManyToOne(targetEntity="\Site\DonorBundle\Entity\Donor")
     * @ORM\JoinColumn(name="donor", referencedColumnName="id")
     */
    private $donor;

    /**
     * @ORM\ManyToOne(targetEntity="\Site\BeneficiaryBundle\Entity\Beneficiary")
     * @ORM\JoinColumn(name="beneficiary", referencedColumnName="id")
     */
    private $beneficiary;

    /**
     * @var DateTime $added
     *
     * @ORM\Column(name="added", type="datetime")
     */
     private $added;

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
     * Set added
     *
     * @param \DateTime $added
     * @return SearchResult
     */
    public function setAdded($added)
    {
        $this->added = $added;
    
        return $this;
    }

    /**
     * Get added
     *
     * @return \DateTime 
     */
    public function getAdded()
    {
        return $this->added;
    }

    /**
     * Set donor
     *
     * @param \Site\DonorBundle\Entity\Donor $donor
     * @return SearchResult
     */
    public function setDonor(\Site\DonorBundle\Entity\Donor $donor = null)
    {
        $this->donor = $donor;
    
        return $this;
    }

    /**
     * Get donor
     *
     * @return \Site\DonorBundle\Entity\Donor 
     */
    public function getDonor()
    {
        return $this->donor;
    }

    /**
     * Set beneficiary
     *
     * @param \Site\BeneficiaryBundle\Entity\Beneficiary $beneficiary
     * @return SearchResult
     */
    public function setBeneficiary(\Site\BeneficiaryBundle\Entity\Beneficiary $beneficiary = null)
    {
        $this->beneficiary = $beneficiary;
    
        return $this;
    }

    /**
     * Get beneficiary
     *
     * @return \Site\BeneficiaryBundle\Entity\Beneficiary 
     */
    public function getBeneficiary()
    {
        return $this->beneficiary;
    }
}
