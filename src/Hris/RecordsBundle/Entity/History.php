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
namespace Hris\RecordsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Hris\RecordsBundle\Entity\Record;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\RecordsBundle\Entity\History
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="hris_record_history",uniqueConstraints={ @ORM\UniqueConstraint(name="unique_recordhistory_idx",columns={"record_id", "history","startdate"}) })
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
     * @var string $uid
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="uid", type="string", length=13, unique=true)
     */
    private $uid;
    
    /**
     * @var Record $record
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\RecordsBundle\Entity\Record")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="record_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * })
     */
    private $record;

    /**
     * @var Field $field
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\Field")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="field_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * })
     */
    private $field;

    /**
     * @var string $history
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="history", type="string", length=64)
     */
    private $history;

    /**
     * @var string $reason
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="reason", type="string", length=255, nullable=true)
     */
    private $reason;

    /**
     * @var \DateTime $startdate
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="startdate", type="datetime")
     */
    private $startdate;

    /**
     * @var string $username
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="username", type="string", length=64)
     */
    private $username;

    /**
     * @var \DateTime $datecreated
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="datecreated", type="datetime", nullable=false)
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
     * Constructor
     */
    public function __construct()
    {
        $this->uid = uniqid();
        $this->datecreated = new \DateTime('now');
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
     * @param Record $record
     * @return History
     */
    public function setRecord(Record $record = null)
    {
        $this->record = $record;
    
        return $this;
    }

    /**
     * Get record
     *
     * @return Record
     */
    public function getRecord()
    {
        return $this->record;
    }

    /**
     * Set field
     *
     * @param Field $field
     * @return History
     */
    public function setField(Field $field = null)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return Field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        $recordHistory = 'Record:'.$this->getRecord()->__toString().' History:'.$this->getHistory();
        return $recordHistory;
    }

}