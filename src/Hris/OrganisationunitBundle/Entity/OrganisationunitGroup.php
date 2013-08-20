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

use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Hris\OrganisationunitBundle\Entity\OrganisationunitGroupset;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\OrganisationunitBundle\Entity\OrganisationunitGroup
 *
 * @ORM\Table(name="hris_organisationunitgroup")
 * @Gedmo\Loggable
 * @ORM\Entity(repositoryClass="Hris\OrganisationunitBundle\Entity\OrganisationunitGroupRepository")
 */
class OrganisationunitGroup
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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    
    /**
     * @var string $dhisUid
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="dhisUid", type="string", length=11, unique=true, nullable=true)
     */
    private $dhisUid;

    /**
     * @var string $code
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="code", type="string", length=50, nullable=true, unique=true)
     */
    private $code;
    
    /**
     * @var Organisationunit $organisationunit
     *
     * @ORM\ManyToMany(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit", inversedBy="organisationunitGroup")
     * @ORM\JoinTable(name="hris_organisationunitgroup_members",
     *   joinColumns={
     *     @ORM\JoinColumn(name="organisationunitgroup_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="organisationunit_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     * @ORM\OrderBy({"longname" = "ASC"})
     */
    private $organisationunit;
    
    /**
     * @var OrganisationunitGroupset $organisationunitGroupset
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitGroupset",inversedBy="organisationunitGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organisationunitgroupset_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $organisationunitGroupset;

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
     * Set name
     *
     * @param string $name
     * @return OrganisationunitGroup
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
     * Set uid
     *
     * @param string $uid
     * @return OrganisationunitGroup
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
     * @return OrganisationunitGroup
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
     * @return OrganisationunitGroup
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
     * @return OrganisationunitGroup
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
     * Add organisationunit
     *
     * @param Organisationunit $organisationunit
     * @return OrganisationunitGroup
     */
    public function addOrganisationunit(Organisationunit $organisationunit)
    {
        $this->organisationunit[$organisationunit->getId()] = $organisationunit;
    
        return $this;
    }

    /**
     * Remove organisationunit
     *
     * @param Organisationunit $organisationunit
     */
    public function removeOrganisationunit(Organisationunit $organisationunit)
    {
        $this->organisationunit->removeElement($organisationunit);
    }

    /**
     * Get organisationunit
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganisationunit()
    {
        return $this->organisationunit;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->organisationunit = new ArrayCollection();
        $this->uid = uniqid();
    }
    
    /**
     * Set dhisUid
     *
     * @param string $dhisUid
     * @return OrganisationunitGroup
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
     * Set description
     *
     * @param string $description
     * @return Organisationunit
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
     * Set organisationunitGroupset
     *
     * @param OrganisationunitGroupset $organisationunitGroupset
     * @return OrganisationunitGroup
     */
    public function setOrganisationunitGroupset(OrganisationunitGroupset $organisationunitGroupset = null)
    {
        $this->organisationunitGroupset = $organisationunitGroupset;
    
        return $this;
    }

    /**
     * Get organisationunitGroupset
     *
     * @return OrganisationunitGroupset
     */
    public function getOrganisationunitGroupset()
    {
        return $this->organisationunitGroupset;
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