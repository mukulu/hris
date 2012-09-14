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

use Hris\UserBundle\Entity\User;
use Hris\OrganisationunitBundle\Entity\OrganisationunitGroup;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Hris\OrganisationunitBundle\Entity\Organisationunit
 *
 * @ORM\Table(name="hris_organisationunit",uniqueConstraints={@ORM\UniqueConstraint(name="organisationunits_with_one_parent_idx",columns={"longname", "parent_id"})})
 * @ORM\Entity(repositoryClass="Hris\OrganisationunitBundle\Entity\OrganisationunitRepository")
 */
class Organisationunit
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
     * @var Hris\UserBundle\Entity\User $user
     * 
     * @ORM\ManyToMany(targetEntity="Hris\UserBundle\Entity\User", mappedBy="organisationunit")
     */
    private $user;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\OrganisationunitGroup $organisationunitGroup
     * 
     * @ORM\ManyToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitGroup", mappedBy="organisationunit")
     */
    private $organisationunitGroup;

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=25, nullable=true, unique=true)
     */
    private $code;

    /**
     * @var string $uid
     *
     * @ORM\Column(name="uid", type="string", length=11, nullable=false, unique=true)
     */
    private $uid;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $parent
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     * })
     * @ORM\OrderBy({"longname" = "ASC"})
     */
    private $parent;

    /**
     * @var string $shortname
     *
     * @ORM\Column(name="shortname", type="string", length=20, nullable=false, unique=true)
     */
    private $shortname;

    /**
     * @var string $longname
     *
     * @ORM\Column(name="longname", type="string", length=64, nullable=false)
     */
    private $longname;

    /**
     * @var boolean $active
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active;

    /**
     * @var \DateTime $openingdate
     *
     * @ORM\Column(name="openingdate", type="date", nullable=true)
     */
    private $openingdate;

    /**
     * @var \DateTime $closingdate
     *
     * @ORM\Column(name="closingdate", type="date", nullable=true)
     */
    private $closingdate;

    /**
     * @var string $geocode
     *
     * @ORM\Column(name="geocode", type="string", length=255, nullable=true)
     */
    private $geocode;

    /**
     * @var string $coordinates
     *
     * @ORM\Column(name="coordinates", type="text", nullable=true)
     */
    private $coordinates;

    /**
     * @var string $featuretype
     *
     * @ORM\Column(name="featuretype", type="string", length=20, nullable=true)
     */
    private $featuretype;
    
    /**
     * @var \DateTime $datecreated
     *
     * @ORM\Column(name="datecreated", type="datetime", nullable=false)
     */
    private $datecreated;

    /**
     * @var \DateTime $lastupdated
     *
     * @ORM\Column(name="lastupdated", type="datetime", nullable=true)
     */
    private $lastupdated;

    /**
     * @var string $address
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=150, nullable=true)
     */
    private $email;

    /**
     * @var string $phonenumber
     *
     * @ORM\Column(name="phonenumber", type="string", length=150, nullable=true)
     */
    private $phonenumber;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;


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
     * Set code
     *
     * @param string $code
     * @return Organisationunit
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
     * Set uid
     *
     * @param string $uid
     * @return Organisationunit
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
     * Set shortname
     *
     * @param string $shortname
     * @return Organisationunit
     */
    public function setShortname($shortname)
    {
        $this->shortname = $shortname;
    
        return $this;
    }

    /**
     * Get shortname
     *
     * @return string 
     */
    public function getShortname()
    {
        return $this->shortname;
    }

    /**
     * Set longname
     *
     * @param string $longname
     * @return Organisationunit
     */
    public function setLongname($longname)
    {
        $this->longname = $longname;
    
        return $this;
    }

    /**
     * Get longname
     *
     * @return string 
     */
    public function getLongname()
    {
        return $this->longname;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Organisationunit
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set openingdate
     *
     * @param \DateTime $openingdate
     * @return Organisationunit
     */
    public function setOpeningdate($openingdate)
    {
        $this->openingdate = $openingdate;
    
        return $this;
    }

    /**
     * Get openingdate
     *
     * @return \DateTime 
     */
    public function getOpeningdate()
    {
        return $this->openingdate;
    }

    /**
     * Set closingdate
     *
     * @param \DateTime $closingdate
     * @return Organisationunit
     */
    public function setClosingdate($closingdate)
    {
        $this->closingdate = $closingdate;
    
        return $this;
    }

    /**
     * Get closingdate
     *
     * @return \DateTime 
     */
    public function getClosingdate()
    {
        return $this->closingdate;
    }

    /**
     * Set geocode
     *
     * @param string $geocode
     * @return Organisationunit
     */
    public function setGeocode($geocode)
    {
        $this->geocode = $geocode;
    
        return $this;
    }

    /**
     * Get geocode
     *
     * @return string 
     */
    public function getGeocode()
    {
        return $this->geocode;
    }

    /**
     * Set coordinates
     *
     * @param string $coordinates
     * @return Organisationunit
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;
    
        return $this;
    }

    /**
     * Get coordinates
     *
     * @return string 
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * Set featuretype
     *
     * @param string $featuretype
     * @return Organisationunit
     */
    public function setFeaturetype($featuretype)
    {
        $this->featuretype = $featuretype;
    
        return $this;
    }

    /**
     * Get featuretype
     *
     * @return string 
     */
    public function getFeaturetype()
    {
        return $this->featuretype;
    }

    /**
     * Set lastupdated
     *
     * @param \DateTime $lastupdated
     * @return Organisationunit
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
     * Set address
     *
     * @param string $address
     * @return Organisationunit
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Organisationunit
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phonenumber
     *
     * @param string $phonenumber
     * @return Organisationunit
     */
    public function setPhonenumber($phonenumber)
    {
        $this->phonenumber = $phonenumber;
    
        return $this;
    }

    /**
     * Get phonenumber
     *
     * @return string 
     */
    public function getPhonenumber()
    {
        return $this->phonenumber;
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
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->organisationunitGroup = new \Doctrine\Common\Collections\ArrayCollection();
        $this->active = TRUE;
    }
    
    /**
     * Add user
     *
     * @param Hris\UserBundle\Entity\User $user
     * @return Organisationunit
     */
    public function addUser(\Hris\UserBundle\Entity\User $user)
    {
        $this->user[] = $user;
    
        return $this;
    }

    /**
     * Remove user
     *
     * @param Hris\UserBundle\Entity\User $user
     */
    public function removeUser(\Hris\UserBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add organisationunitGroup
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitGroup $organisationunitGroup
     * @return Organisationunit
     */
    public function addOrganisationunitGroup(\Hris\OrganisationunitBundle\Entity\OrganisationunitGroup $organisationunitGroup)
    {
        $this->organisationunitGroup[] = $organisationunitGroup;
    
        return $this;
    }

    /**
     * Remove organisationunitGroup
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitGroup $organisationunitGroup
     */
    public function removeOrganisationunitGroup(\Hris\OrganisationunitBundle\Entity\OrganisationunitGroup $organisationunitGroup)
    {
        $this->organisationunitGroup->removeElement($organisationunitGroup);
    }

    /**
     * Get organisationunitGroup
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getOrganisationunitGroup()
    {
        return $this->organisationunitGroup;
    }

    /**
     * Set parent
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $parent
     * @return Organisationunit
     */
    public function setParent(\Hris\OrganisationunitBundle\Entity\Organisationunit $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return Organisationunit
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
}