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

use Doctrine\ORM\Mapping as ORM;

use Hris\RecordsBundle\Entity\Record;

/**
 * Hris\RecordsBundle\Entity\Training
 *
 * @ORM\Table(name="hris_record_training")
 * @ORM\Entity(repositoryClass="Hris\RecordsBundle\Entity\TrainingRepository")
 */
class Training
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
     * @ORM\Column(name="uid", type="string", length=13, unique=true)
     */
    private $uid;
    
    /**
     * @var Hris\RecordsBundle\Entity\Record $record
     *
     * @ORM\ManyToOne(targetEntity="Hris\RecordsBundle\Entity\Record")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="record_id", referencedColumnName="id")
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
     * @var string $coursename
     *
     * @ORM\Column(name="coursename", type="string", length=255)
     */
    private $coursename;

    /**
     * @var string $courselocation
     *
     * @ORM\Column(name="courselocation", type="string", length=255)
     */
    private $courselocation;

    /**
     * @var string $sponsor
     *
     * @ORM\Column(name="sponsor", type="string", length=255)
     */
    private $sponsor;

    /**
     * @var string $startdate
     *
     * @ORM\Column(name="startdate", type="string", length=255)
     */
    private $startdate;

    /**
     * @var string $enddate
     *
     * @ORM\Column(name="enddate", type="string", length=255)
     */
    private $enddate;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return Training
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
     * Set instance
     *
     * @param string $instance
     * @return Training
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
     * Set coursename
     *
     * @param string $coursename
     * @return Training
     */
    public function setCoursename($coursename)
    {
        $this->coursename = $coursename;
    
        return $this;
    }

    /**
     * Get coursename
     *
     * @return string 
     */
    public function getCoursename()
    {
        return $this->coursename;
    }

    /**
     * Set courselocation
     *
     * @param string $courselocation
     * @return Training
     */
    public function setCourselocation($courselocation)
    {
        $this->courselocation = $courselocation;
    
        return $this;
    }

    /**
     * Get courselocation
     *
     * @return string 
     */
    public function getCourselocation()
    {
        return $this->courselocation;
    }

    /**
     * Set sponsor
     *
     * @param string $sponsor
     * @return Training
     */
    public function setSponsor($sponsor)
    {
        $this->sponsor = $sponsor;
    
        return $this;
    }

    /**
     * Get sponsor
     *
     * @return string 
     */
    public function getSponsor()
    {
        return $this->sponsor;
    }

    /**
     * Set startdate
     *
     * @param string $startdate
     * @return Training
     */
    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;
    
        return $this;
    }

    /**
     * Get startdate
     *
     * @return string 
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * Set enddate
     *
     * @param string $enddate
     * @return Training
     */
    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;
    
        return $this;
    }

    /**
     * Get enddate
     *
     * @return string 
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Training
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
     * @return Training
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
     * @return Training
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
     * Set record
     *
     * @param Hris\RecordsBundle\Entity\Record $record
     * @return Training
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