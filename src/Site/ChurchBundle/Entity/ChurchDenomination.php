<?php

namespace Site\ChurchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
//use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
//use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface;
//use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;

/**
 * Site\ChurchBundle\Entity\ChurchDenomination
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ChurchDenomination 
//extends EntityChoiceList  
// implements ChoiceListInterface
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
     * @return ChurchDenomination
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
}
