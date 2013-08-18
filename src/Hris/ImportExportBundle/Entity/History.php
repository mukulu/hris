<?php

namespace Hris\ImportExportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * History
 *
 * @ORM\Table(name="hris_importexport_history")
 * @ORM\Entity(repositoryClass="Hris\ImportExportBundle\Entity\HistoryRepository")
 */
class History
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="session_type", type="string", length=64)
     */
    private $session_type;

    /**
     * @var string
     *
     * @ORM\Column(name="uid", type="string", length=13)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="object", type="string", length=64)
     */
    private $object;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=64)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=64)
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="starttime", type="datetime")
     */
    private $starttime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finishtime", type="datetime")
     */
    private $finishtime;


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
     * Set session_type
     *
     * @param string $sessionType
     * @return History
     */
    public function setSessionType($sessionType)
    {
        $this->session_type = $sessionType;
    
        return $this;
    }

    /**
     * Get session_type
     *
     * @return string 
     */
    public function getSessionType()
    {
        return $this->session_type;
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return History
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    
        return $this;
    }

    /**
     * Get uid
     *
     * @return string 
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set object
     *
     * @param string $object
     * @return History
     */
    public function setObject($object)
    {
        $this->object = $object;
    
        return $this;
    }

    /**
     * Get object
     *
     * @return string 
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return History
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return History
     */
    public function setCount($count)
    {
        $this->count = $count;
    
        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return History
     */
    public function setUser($user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set starttime
     *
     * @param \DateTime $starttime
     * @return History
     */
    public function setStarttime($starttime)
    {
        $this->starttime = $starttime;
    
        return $this;
    }

    /**
     * Get starttime
     *
     * @return \DateTime 
     */
    public function getStarttime()
    {
        return $this->starttime;
    }

    /**
     * Set finishtime
     *
     * @param \DateTime $finishtime
     * @return History
     */
    public function setFinishtime($finishtime)
    {
        $this->finishtime = $finishtime;
    
        return $this;
    }

    /**
     * Get finishtime
     *
     * @return \DateTime 
     */
    public function getFinishtime()
    {
        return $this->finishtime;
    }

    /**
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        $importHistory = 'Object:'.$this->getObject().' Status:'.$this->getStatus().' Count:'.$this->getCount();
        return $importHistory;
    }
}
