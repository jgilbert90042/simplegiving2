<?php

namespace Site\SearchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Site\SearchBundle\Entity\SearchProfile
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class SearchProfile
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
     * @ORM\OneToOne(targetEntity="\Site\DonorBundle\Entity\Donor")
     * @ORM\JoinColumn(name="donor", referencedColumnName="id")
     */
    private $donor;

    /**
     * @var DateTime $created
     *
     * @ORM\Column(name="created", type="datetime")
     */
     private $created;

    /**
     * @var DateTime $updated
     *
     * @ORM\Column(name="updated", type="datetime")
     */
     private $updated;


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
     * Set created
     *
     * @param datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Get created
     *
     * @return datetime 
     */
    public function getCreated()
    {
        return $this->created;
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
}
