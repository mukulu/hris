<?php
/*
 *
 * Copyright 2012 Human Resource Information System
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 * @since 2012
 * @author John Francis Mukulu <john.f.mukulu@gmail.com>
 *
 */
namespace Hris\ImportExportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * History
 *
 * @Gedmo\Loggable
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
     * @Gedmo\Versioned
     * @ORM\Column(name="uid", type="string", length=13)
     */
    private $uid;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="session_type", type="string", length=64)
     */
    private $session_type;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="object", type="string", length=64)
     */
    private $object;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="status", type="string", length=64)
     */
    private $status;

    /**
     * @var integer
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="username", type="string", length=64)
     */
    private $username;

    /**
     * @var \DateTime
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="starttime", type="datetime")
     */
    private $starttime;

    /**
     * @var \DateTime
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="finishtime", type="datetime")
     */
    private $finishtime;

    /**
     * @var \DateTime $datecreated
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="datecreated", type="datetime")
     */
    private $datecreated;

    /**
     * @var \DateTime $lastupdated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="lastupdated", type="datetime", nullable=true)
     */
    private $lastupdated;


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
     * Set username
     *
     * @param string $username
     * @return History
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
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
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return Field
     */
    public function setDatecreated($datecreated)
    {
        $this->datecreated = $datecreated;

        return $this;
    }

    /**
     * Get datecreated
     *
     * @return \DateTime
     */
    public function getDatecreated()
    {
        return $this->datecreated;
    }

    /**
     * Set lastupdated
     *
     * @param \DateTime $lastupdated
     * @return Field
     */
    public function setLastupdated($lastupdated)
    {
        $this->lastupdated = $lastupdated;

        return $this;
    }

    /**
     * Get lastupdated
     *
     * @return \DateTime
     */
    public function getLastupdated()
    {
        return $this->lastupdated;
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
