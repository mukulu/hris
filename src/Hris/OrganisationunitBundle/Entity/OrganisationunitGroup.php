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
namespace Hris\OrganisationunitBundle\Entity;

use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Hris\OrganisationunitBundle\Entity\OrganisationunitGroupset;
use Doctrine\ORM\Mapping as ORM;

/**
 * Hris\OrganisationunitBundle\Entity\OrganisationunitGroup
 *
 * @ORM\Table(name="hris_organisationunitgroup")
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=false, unique=true)
     */
    private $name;

    /**
     * @var string $uid
     *
     * @ORM\Column(name="uid", type="string", length=11, nullable=false, unique=true)
     */
    private $uid;

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=50, nullable=true, unique=true)
     */
    private $code;

    /**
     * @var \DateTime $lastupdated
     *
     * @ORM\Column(name="lastupdated", type="datetime", nullable=true)
     */
    private $lastupdated;

    /**
     * @var \DateTime $datecreated
     *
     * @ORM\Column(name="datecreated", type="datetime", nullable=false)
     */
    private $datecreated;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
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
     * @var Hris\OrganisationunitBundle\Entity\OrganisationunitGroupset $organisationunitGroupset
     *
     * @ORM\ManyToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitGroupset", mappedBy="organisationunitGroup")
     */
    private $organisationunitGroupset;


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
     * Constructor
     */
    public function __construct()
    {
        $this->organisationunit = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add organisationunit
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     * @return OrganisationunitGroup
     */
    public function addOrganisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit)
    {
        $this->organisationunit[] = $organisationunit;
    
        return $this;
    }

    /**
     * Remove organisationunit
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     */
    public function removeOrganisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit)
    {
        $this->organisationunit->removeElement($organisationunit);
    }

    /**
     * Get organisationunit
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getOrganisationunit()
    {
        return $this->organisationunit;
    }

    /**
     * Add organisationunitGroupset
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitGroupset $organisationunitGroupset
     * @return OrganisationunitGroup
     */
    public function addOrganisationunitGroupset(\Hris\OrganisationunitBundle\Entity\OrganisationunitGroupset $organisationunitGroupset)
    {
        $this->organisationunitGroupset[] = $organisationunitGroupset;
    
        return $this;
    }

    /**
     * Remove organisationunitGroupset
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitGroupset $organisationunitGroupset
     */
    public function removeOrganisationunitGroupset(\Hris\OrganisationunitBundle\Entity\OrganisationunitGroupset $organisationunitGroupset)
    {
        $this->organisationunitGroupset->removeElement($organisationunitGroupset);
    }

    /**
     * Get organisationunitGroupset
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getOrganisationunitGroupset()
    {
        return $this->organisationunitGroupset;
    }
}