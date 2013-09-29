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

use Hris\OrganisationunitBundle\Entity\OrganisationunitStructure;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\OrganisationunitBundle\Entity\OrganisationunitLevel
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="hris_organisationunitlevel")
 * @ORM\Entity(repositoryClass="Hris\OrganisationunitBundle\Entity\OrganisationunitLevelRepository")
 */
class OrganisationunitLevel
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
     * @var integer $level
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="level", type="integer", unique=true)
     */
    private $level;

    /**
     * @var string $name
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="name", type="string", length=128, unique=true)
     */
    private $name;

    /**
     * @var string $description
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var boolean $dataentrylevel
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="dataentrylevel", type="boolean")
     */
    private $dataentrylevel;
    
    /**
     * @var OrganisationunitStructure $organisationunitStructure
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", mappedBy="level",cascade={"ALL"})
     */
    private $organisationunitStructure;

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
     * Set level
     *
     * @param integer $level
     * @return OrganisationunitLevel
     */
    public function setLevel($level)
    {
        $this->level = $level;
    
        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return OrganisationunitLevel
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
     * Set lastupdated
     *
     * @param \DateTime $lastupdated
     * @return OrganisationunitLevel
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
     * @return OrganisationunitLevel
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
     * Set uid
     *
     * @param string $uid
     * @return OrganisationunitLevel
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
     * Set description
     *
     * @param string $description
     * @return OrganisationunitLevel
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
     * Set dataentrylevel
     *
     * @param boolean $dataentrylevel
     * @return OrganisationunitLevel
     */
    public function setDataentrylevel($dataentrylevel)
    {
        $this->dataentrylevel = $dataentrylevel;
    
        return $this;
    }

    /**
     * Get dataentrylevel
     *
     * @return boolean 
     */
    public function getDataentrylevel()
    {
        return $this->dataentrylevel;
    }

    /**
     * Add organisationunitStructure
     *
     * @param OrganisationunitStructure $organisationunitStructure
     * @return OrganisationunitLevel
     */
    public function addOrganisationunitStructure(OrganisationunitStructure $organisationunitStructure)
    {
        $this->organisationunitStructure[$organisationunitStructure->getId()] = $organisationunitStructure;
    
        return $this;
    }

    /**
     * Remove organisationunitStructure
     *
     * @param OrganisationunitStructure $organisationunitStructure
     */
    public function removeOrganisationunitStructure(OrganisationunitStructure $organisationunitStructure)
    {
        $this->organisationunitStructure->removeElement($organisationunitStructure);
    }

    /**
     * Get organisationunitStructure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganisationunitStructure()
    {
        return $this->organisationunitStructure;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->uid = uniqid();
        $this->organisationunitStructure = new ArrayCollection();
        $this->dataentrylevel = FALSE;
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