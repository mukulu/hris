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
namespace Hris\OrganisationunitBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Hris\OrganisationunitBundle\Entity\OrganisationunitGroup;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\OrganisationunitBundle\Entity\OrganisationunitGroupset
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="hris_organisationunitgroupset")
 * @ORM\Entity(repositoryClass="Hris\OrganisationunitBundle\Entity\OrganisationunitGroupsetRepository")
 */
class OrganisationunitGroupset
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
     * @var string $dhisUid
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="dhisUid", type="string", length=11, nullable=true, unique=true)
     */
    private $dhisUid;

    /**
     * @var string $name
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="name", type="string", length=64, unique=true)
     */
    private $name;

    /**
     * @var string $description
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * Compulsory means that ALL Organisationunits must be member of a group in this groupset.
     * used for enforcing membership of orgunits in one of the groups under groupset.
     *
     * @var boolean $compulsory
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="compulsory", type="boolean")
     */
    private $compulsory;

    /**
     * @var string $code
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="code", type="string", length=50, nullable=true,unique=true)
     */
    private $code;

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
     * @var OrganisationunitGroup $organisationunitGroup
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitGroup", mappedBy="organisationunitGroupset",cascade={"ALL"})
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $organisationunitGroup;


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
     * @return OrganisationunitGroupset
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
     * Set description
     *
     * @param string $description
     * @return OrganisationunitGroupset
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set compulsory
     *
     * @param boolean $compulsory
     * @return OrganisationunitGroupset
     */
    public function setCompulsory($compulsory)
    {
        $this->compulsory = $compulsory;
    
        return $this;
    }

    /**
     * Get compulsory
     *
     * @return boolean 
     */
    public function getCompulsory()
    {
        return $this->compulsory;
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return OrganisationunitGroupset
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
     * Set code
     *
     * @param string $code
     * @return OrganisationunitGroupset
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set lastupdated
     *
     * @param \DateTime $lastupdated
     * @return OrganisationunitGroupset
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
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return OrganisationunitGroupset
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
     * Constructor
     */
    public function __construct()
    {
    	$this->compulsory = FALSE;
    	$this->uid = uniqid();
        $this->organisationunitGroup = new ArrayCollection();
    }
    
    /**
     * Add organisationunitGroup
     *
     * @param OrganisationunitGroup $organisationunitGroup
     * @return OrganisationunitGroupset
     */
    public function addOrganisationunitGroup(OrganisationunitGroup $organisationunitGroup)
    {
        $this->organisationunitGroup[$organisationunitGroup->getId()] = $organisationunitGroup;
    
        return $this;
    }

    /**
     * Remove organisationunitGroup
     *
     * @param OrganisationunitGroup $organisationunitGroup
     */
    public function removeOrganisationunitGroup(OrganisationunitGroup $organisationunitGroup)
    {
        $this->organisationunitGroup->removeElement($organisationunitGroup);
    }

    /**
     * Get organisationunitGroup
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganisationunitGroup()
    {
        return $this->organisationunitGroup;
    }

    /**
     * Set dhisUid
     *
     * @param string $dhisUid
     * @return OrganisationunitGroupset
     */
    public function setDhisUid($dhisUid)
    {
        $this->dhisUid = $dhisUid;
    
        return $this;
    }

    /**
     * Get dhisUid
     *
     * @return string 
     */
    public function getDhisUid()
    {
        return $this->dhisUid;
    }

    /**
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}