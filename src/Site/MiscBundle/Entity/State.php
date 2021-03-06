<?php

namespace Site\MiscBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;

/**
 * Site\MiscBundle\Entity\State
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class State extends EntityChoiceList implements ChoiceListInterface
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $abbr
     *
     * @ORM\Column(name="abbr", type="string", length=2, unique=true)
    */
    private $abbr;

    /**
     * @ORM\ManyToMany(targetEntity="\Site\ChurchBundle\Entity\Church")
     * @ORM\JoinTable(name="state_churches",
     *      joinColumns={@ORM\JoinColumn(name="state", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="church_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $churches;

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
     * @return State
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
     * Set abbr
     *
     * @param string $abbr
     * @return State
     */
    public function setAbbr($abbr)
    {
        $this->abbr = $abbr;
    
        return $this;
    }

    /**
     * Get abbr
     *
     * @return string 
     */
    public function getAbbr()
    {
        return $this->abbr;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->churches = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __tostring() {
        return $this->abbr;
    }
    
    /**
     * Get churches
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChurches()
    {
        return $this->churches;
    }

    /**
     * Add churches
     *
     * @param \Site\ChurchBundle\Entity\Church $churches
     * @return State
     */
    public function addChurch(\Site\ChurchBundle\Entity\Church $churches)
    {
        $this->churches[] = $churches;
    
        return $this;
    }

    /**
     * Remove churches
     *
     * @param \Site\ChurchBundle\Entity\Church $churches
     */
    public function removeChurch(\Site\ChurchBundle\Entity\Church $churches)
    {
        $this->churches->removeElement($churches);
    }


    /**
     * Add churches
     *
     * @param \Site\ChurchBundle\Entity\Church $churches
     * @return State
     */
    public function addChurche(\Site\ChurchBundle\Entity\Church $churches)
    {
        $this->churches[] = $churches;
    
        return $this;
    }

    /**
     * Remove churches
     *
     * @param \Site\ChurchBundle\Entity\Church $churches
     */
    public function removeChurche(\Site\ChurchBundle\Entity\Church $churches)
    {
        $this->churches->removeElement($churches);
    }
}