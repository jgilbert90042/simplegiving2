<?php

namespace Site\LogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Site\LogBundle\Entity\LogEntry
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class LogEntry
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
     * @var string $bundle
     *
     * @ORM\Column(name="bundle", type="string", length=255)
     */
     private $bundle;

    /**
     * @var string $controller
     *
     * @ORM\Column(name="controller", type="string", length=255)
     */
    private $controller;

    /**
     * @var string $action
     *
     * @ORM\Column(name="action", type="string", length=255)
     */
    private $action;

    /**
     * @var integer $user
     *
     * @ORM\ManyToOne(targetEntity="\Site\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string $targetEntity
     *
     * @ORM\Column(name="targetEntity", type="string", length=255)
     */
    private $targetEntity;

    /**
     * @var integer $targetId
     *
     * @ORM\Column(name="targetId", type="integer")
     */
    private $targetId;

    /**
     * @var string $recipientEntity
     *
     * @ORM\Column(name="recipientEntity", type="string", length=255)
     */
     private $recipientEntity;

    /**
     * @var integer $recipientId
     *
     * @ORM\Column(name="recipientId", type="integer")
     */
     private $recipientId;

    /**
     * @var string $notes
     *
     * @ORM\Column(name="notes", type="text")
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
     * Set bundle
     *
     * @param string $bundle
     */
    public function setBundle($bundle)
    {
        $this->bundle = $bundle;
    }

    /**
     * Get bundle
     *
     * @return string 
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * Set controller
     *
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * Get controller
     *
     * @return string 
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Set action
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set targetEntity
     *
     * @param string $targetEntity
     */
    public function setTargetEntity($targetEntity)
    {
        $this->targetEntity = $targetEntity;
    }

    /**
     * Get targetEntity
     *
     * @return string 
     */
    public function getTargetEntity()
    {
        return $this->targetEntity;
    }

    /**
     * Set targetId
     *
     * @param integer $targetId
     */
    public function setTargetId($targetId)
    {
        $this->targetId = $targetId;
    }

    /**
     * Get targetId
     *
     * @return integer 
     */
    public function getTargetId()
    {
        return $this->targetId;
    }

    /**
     * Set recipientEntity
     *
     * @param string $recipientEntity
     */
    public function setRecipientEntity($recipientEntity)
    {
        $this->recipientEntity = $recipientEntity;
    }

    /**
     * Get recipientEntity
     *
     * @return string 
     */
    public function getRecipientEntity()
    {
        return $this->recipientEntity;
    }

    /**
     * Set recipientId
     *
     * @param integer $recipientId
     */
    public function setRecipientId($recipientId)
    {
        $this->recipientId = $recipientId;
    }

    /**
     * Get recipientId
     *
     * @return integer 
     */
    public function getRecipientId()
    {
        return $this->recipientId;
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
}