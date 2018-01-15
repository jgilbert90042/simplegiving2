<?php

namespace Site\BeneficiaryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Site\BeneficiaryBundle\Entity\Urgency
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Urgency
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
     * @var integer $urgency
     *
     * @ORM\Column(type="string", length=255)
     */
    private $urgency;


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
     * Set urgency
     *
     * @param string $urgency
     * @return Urgency
     */
    public function setUrgency($urgency)
    {
        $this->urgency = $urgency;
    
        return $this;
    }

    /**
     * Get urgency
     *
     * @return string 
     */
    public function getUrgency()
    {
        return $this->urgency;
    }
}