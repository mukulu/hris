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
namespace Hris\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Hris\UserBundle\Entity\User
 *
 * @ORM\Table(name="hris_user")
 * @ORM\Entity(repositoryClass="Hris\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string $uid
     *
     * @ORM\Column(name="uid", type="string", length=11, nullable=false, unique=true)
     */
    private $uid;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     *
     * @ORM\ManyToMany(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit", inversedBy="user")
     * @ORM\JoinTable(name="hris_user_organisationunits",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="organisationunit_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     * @ORM\OrderBy({"longname" = "ASC"})
     */
    private $organisationunit;
    
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /*
     * @todo
     * 	- Organisation unit (many)
     * 	- Phone numbers
     * 	- Job title
     * 	- date added
     *  - last updated
     */
    
    public function __construct()
    {
    	parent::__construct();
    	$this->organisationunit = new ArrayCollection();
    	$this->datecreated = new \DateTime('now');
    }

    /**
     * Add organisationunit
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     * @return User
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
     * Set lastupdated
     *
     * @param \DateTime $lastupdated
     * @return User
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
     * @return User
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
     * @return User
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
}