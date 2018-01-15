<?php

namespace Site\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Site\MessageBundle\Entity\Message
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Message
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
     * @ORM\ManyToOne(targetEntity="\Site\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @var integer $sender
     *
     * @ORM\ManyToOne(targetEntity="\Site\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="sender", referencedColumnName="id")
     */
    private $sender;

    /**
     * @var datetime $sent
     *
     * @ORM\Column(name="sent", type="datetime")
     */
    private $sent;

    /**
     * @var integer $status
     *
     * @ORM\ManyToOne(targetEntity="\Site\MessageBundle\Entity\MessageStatus")
     * @ORM\JoinColumn(name="status", referencedColumnName="id")
     */
    private $status;

    /**
     * @var string $subject
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
     private $subject;

    /**
     * @var string $body
     *
     * @ORM\Column(name="body", type="text")
     */
     private $body;

    /**
     * @ORM\Column(name="reviewed", type="boolean")
     */
    private $reviewed;


    public function __construct()
    {
        $this->reviewed = 0;
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
     * Set text
     *
     * @param text $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Get text
     *
     * @return text 
     */
    public function getText()
    {
        return $this->text;
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
     * Set status
     *
     * @param Site\MessageBundle\Entity\MessageStatus $status
     */
    public function setStatus(\Site\MessageBundle\Entity\MessageStatus $status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return Site\MessageBundle\Entity\MessageStatus 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set subject
     *
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set body
     *
     * @param text $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Get body
     *
     * @return text 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set sender
     *
     * @param Site\UserBundle\Entity\User $sender
     */
    public function setSender(\Site\UserBundle\Entity\User $sender)
    {
        $this->sender = $sender;
    }

    /**
     * Get sender
     *
     * @return Site\UserBundle\Entity\User 
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set sent
     *
     * @param datetime $sent
     */
    public function setSent($sent)
    {
        $this->sent = $sent;
    }

    /**
     * Get sent
     *
     * @return datetime 
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * Set reviewed
     *
     * @param boolean $reviewed
     * @return Message
     */
    public function setReviewed($reviewed)
    {
        $this->reviewed = $reviewed;
    
        return $this;
    }

    /**
     * Get reviewed
     *
     * @return boolean 
     */
    public function getReviewed()
    {
        return $this->reviewed;
    }
}