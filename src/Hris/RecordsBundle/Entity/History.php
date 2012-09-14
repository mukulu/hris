<?php
/*
 *
 * Copyright 2012John Francis Mukulu <john.f.mukulu@gmail.com>
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
 *
 */
namespace Hris\RecordsBundle\Entity;

use Hris\RecordsBundle\Entity\Record;
use Doctrine\ORM\Mapping as ORM;

/**
 * Hris\RecordsBundle\Entity\History
 *
 * @ORM\Table(name="hris_record_history")
 * @ORM\Entity(repositoryClass="Hris\RecordsBundle\Entity\HistoryRepository")
 */
class History
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
     * @var Hris\RecordsBundle\Entity\Record $record
     *
     * @ORM\ManyToOne(targetEntity="Hris\RecordsBundle\Entity\Record")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="record_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $record;

    /**
     * @var string $instance
     *
     * @ORM\Column(name="instance", type="string", length=64)
     */
    private $instance;

    /**
     * @var string $history
     *
     * @ORM\Column(name="history", type="string", length=64)
     */
    private $history;

    /**
     * @var \DateTime $startdate
     *
     * @ORM\Column(name="startdate", type="datetime")
     */
    private $startdate;

    /**
     * @var string $reason
     *
     * @ORM\Column(name="reason", type="string", length=255)
     */
    private $reason;

    /**
     * @var string $username
     *
     * @ORM\Column(name="username", type="string", length=64)
     */
    private $username;
    
    /**
     * @var \DateTime $datecreated
     *
     * @ORM\Column(name="datecreated", type="datetime")
     */
    private $datecreated;
    
    /**
     * @var \DateTime $lastupdated
     *
     * @ORM\Column(name="lastupdated", type="datetime")
     */
    private $lastupdated;
    
    /**
     * @var string $uid
     *
     * @ORM\Column(name="uid", type="string", length=11)
     */
    private $uid;


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
     * Set instance
     *
     * @param string $instance
     * @return History
     */
    public function setInstance($instance)
    {
        $this->instance = $instance;
    
        return $this;
    }

    /**
     * Get instance
     *
     * @return string 
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Set history
     *
     * @param string $history
     * @return History
     */
    public function setHistory($history)
    {
        $this->history = $history;
    
        return $this;
    }

    /**
     * Get history
     *
     * @return string 
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * Set startdate
     *
     * @param \DateTime $startdate
     * @return History
     */
    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;
    
        return $this;
    }

    /**
     * Get startdate
     *
     * @return \DateTime 
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * Set reason
     *
     * @param string $reason
     * @return History
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    
        return $this;
    }

    /**
     * Get reason
     *
     * @return string 
     */
    public function getReason()
    {
        return $this->reason;
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
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return History
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
     * @return History
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
     * Set record
     *
     * @param Hris\RecordsBundle\Entity\Record $record
     * @return History
     */
    public function setRecord(\Hris\RecordsBundle\Entity\Record $record = null)
    {
        $this->record = $record;
    
        return $this;
    }

    /**
     * Get record
     *
     * @return Hris\RecordsBundle\Entity\Record 
     */
    public function getRecord()
    {
        return $this->record;
    }
}