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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Hris\DashboardBundle\Entity\DashboardChart;
use Hris\OrganisationunitBundle\Entity\OrganisationunitStructure;
use Hris\UserBundle\Entity\UserInfo;
use Hris\OrganisationunitBundle\Entity\OrganisationunitGroup;

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
     * @var Hris\UserBundle\Entity\UserInfo $userInfo
     * 
     * @ORM\ManyToMany(targetEntity="Hris\UserBundle\Entity\UserInfo", mappedBy="organisationunit")
     * @ORM\OrderBy({"firstName" = "ASC"})
     */
    private $userInfo;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\OrganisationunitGroup $organisationunitGroup
     * 
     * @ORM\ManyToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitGroup", mappedBy="organisationunit")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $organisationunitGroup;
    
    /**
     * @var Hris\DashboardBundle\Entity\DashboardChart $dashboardChart
     *
     * @ORM\ManyToMany(targetEntity="Hris\DashboardBundle\Entity\DashboardChart", mappedBy="organisationunit")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $dashboardChart;

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=25, nullable=true, unique=true)
     */
    private $code;

    /**
     * @var string $uid
     *
     * @ORM\Column(name="uid", type="string", length=13, nullable=false, unique=true)
     */
    private $uid;
    
    /**
     * @var string $dhisUid
     *
     * @ORM\Column(name="dhisUid", type="string", length=11, nullable=false, unique=true)
     */
    private $dhisUid;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $parent
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     * })
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
     * @var Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $organisationunitStructure
     *
     * @ORM\OneToOne(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", inversedBy="organisationunit")
     */
    private $organisationunitStructure;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level1OrganisationunitStructure
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", mappedBy="level1Organisationunit",cascade={"ALL"})
     */
    private $level1OrganisationunitStructure;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level2OrganisationunitStructure
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", mappedBy="level2Organisationunit",cascade={"ALL"})
     */
    private $level2OrganisationunitStructure;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level3OrganisationunitStructure
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", mappedBy="level3Organisationunit",cascade={"ALL"})
     */
    private $level3OrganisationunitStructure;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level4OrganisationunitStructure
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", mappedBy="level4Organisationunit",cascade={"ALL"})
     */
    private $level4OrganisationunitStructure;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level5OrganisationunitStructure
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", mappedBy="level5Organisationunit",cascade={"ALL"})
     */
    private $level5OrganisationunitStructure;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level6OrganisationunitStructure
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", mappedBy="level6Organisationunit",cascade={"ALL"})
     */
    private $level6OrganisationunitStructure;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level7OrganisationunitStructure
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", mappedBy="level7Organisationunit",cascade={"ALL"})
     */
    private $level7OrganisationunitStructure;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level8OrganisationunitStructure
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", mappedBy="level8Organisationunit",cascade={"ALL"})
     */
    private $level8OrganisationunitStructure;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level9OrganisationunitStructure
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", mappedBy="level9Organisationunit",cascade={"ALL"})
     */
    private $level9OrganisationunitStructure;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level10OrganisationunitStructure
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", mappedBy="level10Organisationunit",cascade={"ALL"})
     */
    private $level10OrganisationunitStructure;


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

    /**
     * Add dashboardChart
     *
     * @param Hris\DashboardBundle\Entity\DashboardChart $dashboardChart
     * @return Organisationunit
     */
    public function addDashboardChart(\Hris\DashboardBundle\Entity\DashboardChart $dashboardChart)
    {
        $this->dashboardChart[] = $dashboardChart;
    
        return $this;
    }

    /**
     * Remove dashboardChart
     *
     * @param Hris\DashboardBundle\Entity\DashboardChart $dashboardChart
     */
    public function removeDashboardChart(\Hris\DashboardBundle\Entity\DashboardChart $dashboardChart)
    {
        $this->dashboardChart->removeElement($dashboardChart);
    }

    /**
     * Get dashboardChart
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getDashboardChart()
    {
        return $this->dashboardChart;
    }

    /**
     * Set organisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $organisationunitStructure
     * @return Organisationunit
     */
    public function setOrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $organisationunitStructure = null)
    {
        $this->organisationunitStructure = $organisationunitStructure;
    
        return $this;
    }

    /**
     * Get organisationunitStructure
     *
     * @return Hris\OrganisationunitBundle\Entity\OrganisationunitStructure 
     */
    public function getOrganisationunitStructure()
    {
        return $this->organisationunitStructure;
    }

    /**
     * Set dhisUid
     *
     * @param string $dhisUid
     * @return Organisationunit
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
     * Add userInfo
     *
     * @param Hris\UserBundle\Entity\UserInfo $userInfo
     * @return Organisationunit
     */
    public function addUserInfo(\Hris\UserBundle\Entity\UserInfo $userInfo)
    {
        $this->userInfo[] = $userInfo;
    
        return $this;
    }

    /**
     * Remove userInfo
     *
     * @param Hris\UserBundle\Entity\UserInfo $userInfo
     */
    public function removeUserInfo(\Hris\UserBundle\Entity\UserInfo $userInfo)
    {
        $this->userInfo->removeElement($userInfo);
    }

    /**
     * Get userInfo
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * Add level1OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level1OrganisationunitStructure
     * @return Organisationunit
     */
    public function addLevel1OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level1OrganisationunitStructure)
    {
        $this->level1OrganisationunitStructure[] = $level1OrganisationunitStructure;
    
        return $this;
    }

    /**
     * Remove level1OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level1OrganisationunitStructure
     */
    public function removeLevel1OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level1OrganisationunitStructure)
    {
        $this->level1OrganisationunitStructure->removeElement($level1OrganisationunitStructure);
    }

    /**
     * Get level1OrganisationunitStructure
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLevel1OrganisationunitStructure()
    {
        return $this->level1OrganisationunitStructure;
    }

    /**
     * Add level2OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level2OrganisationunitStructure
     * @return Organisationunit
     */
    public function addLevel2OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level2OrganisationunitStructure)
    {
        $this->level2OrganisationunitStructure[] = $level2OrganisationunitStructure;
    
        return $this;
    }

    /**
     * Remove level2OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level2OrganisationunitStructure
     */
    public function removeLevel2OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level2OrganisationunitStructure)
    {
        $this->level2OrganisationunitStructure->removeElement($level2OrganisationunitStructure);
    }

    /**
     * Get level2OrganisationunitStructure
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLevel2OrganisationunitStructure()
    {
        return $this->level2OrganisationunitStructure;
    }

    /**
     * Add level3OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level3OrganisationunitStructure
     * @return Organisationunit
     */
    public function addLevel3OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level3OrganisationunitStructure)
    {
        $this->level3OrganisationunitStructure[] = $level3OrganisationunitStructure;
    
        return $this;
    }

    /**
     * Remove level3OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level3OrganisationunitStructure
     */
    public function removeLevel3OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level3OrganisationunitStructure)
    {
        $this->level3OrganisationunitStructure->removeElement($level3OrganisationunitStructure);
    }

    /**
     * Get level3OrganisationunitStructure
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLevel3OrganisationunitStructure()
    {
        return $this->level3OrganisationunitStructure;
    }

    /**
     * Add level4OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level4OrganisationunitStructure
     * @return Organisationunit
     */
    public function addLevel4OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level4OrganisationunitStructure)
    {
        $this->level4OrganisationunitStructure[] = $level4OrganisationunitStructure;
    
        return $this;
    }

    /**
     * Remove level4OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level4OrganisationunitStructure
     */
    public function removeLevel4OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level4OrganisationunitStructure)
    {
        $this->level4OrganisationunitStructure->removeElement($level4OrganisationunitStructure);
    }

    /**
     * Get level4OrganisationunitStructure
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLevel4OrganisationunitStructure()
    {
        return $this->level4OrganisationunitStructure;
    }

    /**
     * Add level5OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level5OrganisationunitStructure
     * @return Organisationunit
     */
    public function addLevel5OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level5OrganisationunitStructure)
    {
        $this->level5OrganisationunitStructure[] = $level5OrganisationunitStructure;
    
        return $this;
    }

    /**
     * Remove level5OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level5OrganisationunitStructure
     */
    public function removeLevel5OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level5OrganisationunitStructure)
    {
        $this->level5OrganisationunitStructure->removeElement($level5OrganisationunitStructure);
    }

    /**
     * Get level5OrganisationunitStructure
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLevel5OrganisationunitStructure()
    {
        return $this->level5OrganisationunitStructure;
    }

    /**
     * Add level6OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level6OrganisationunitStructure
     * @return Organisationunit
     */
    public function addLevel6OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level6OrganisationunitStructure)
    {
        $this->level6OrganisationunitStructure[] = $level6OrganisationunitStructure;
    
        return $this;
    }

    /**
     * Remove level6OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level6OrganisationunitStructure
     */
    public function removeLevel6OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level6OrganisationunitStructure)
    {
        $this->level6OrganisationunitStructure->removeElement($level6OrganisationunitStructure);
    }

    /**
     * Get level6OrganisationunitStructure
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLevel6OrganisationunitStructure()
    {
        return $this->level6OrganisationunitStructure;
    }

    /**
     * Add level7OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level7OrganisationunitStructure
     * @return Organisationunit
     */
    public function addLevel7OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level7OrganisationunitStructure)
    {
        $this->level7OrganisationunitStructure[] = $level7OrganisationunitStructure;
    
        return $this;
    }

    /**
     * Remove level7OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level7OrganisationunitStructure
     */
    public function removeLevel7OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level7OrganisationunitStructure)
    {
        $this->level7OrganisationunitStructure->removeElement($level7OrganisationunitStructure);
    }

    /**
     * Get level7OrganisationunitStructure
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLevel7OrganisationunitStructure()
    {
        return $this->level7OrganisationunitStructure;
    }

    /**
     * Add level8OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level8OrganisationunitStructure
     * @return Organisationunit
     */
    public function addLevel8OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level8OrganisationunitStructure)
    {
        $this->level8OrganisationunitStructure[] = $level8OrganisationunitStructure;
    
        return $this;
    }

    /**
     * Remove level8OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level8OrganisationunitStructure
     */
    public function removeLevel8OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level8OrganisationunitStructure)
    {
        $this->level8OrganisationunitStructure->removeElement($level8OrganisationunitStructure);
    }

    /**
     * Get level8OrganisationunitStructure
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLevel8OrganisationunitStructure()
    {
        return $this->level8OrganisationunitStructure;
    }

    /**
     * Add level9OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level9OrganisationunitStructure
     * @return Organisationunit
     */
    public function addLevel9OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level9OrganisationunitStructure)
    {
        $this->level9OrganisationunitStructure[] = $level9OrganisationunitStructure;
    
        return $this;
    }

    /**
     * Remove level9OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level9OrganisationunitStructure
     */
    public function removeLevel9OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level9OrganisationunitStructure)
    {
        $this->level9OrganisationunitStructure->removeElement($level9OrganisationunitStructure);
    }

    /**
     * Get level9OrganisationunitStructure
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLevel9OrganisationunitStructure()
    {
        return $this->level9OrganisationunitStructure;
    }

    /**
     * Add level10OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level10OrganisationunitStructure
     * @return Organisationunit
     */
    public function addLevel10OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level10OrganisationunitStructure)
    {
        $this->level10OrganisationunitStructure[] = $level10OrganisationunitStructure;
    
        return $this;
    }

    /**
     * Remove level10OrganisationunitStructure
     *
     * @param Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level10OrganisationunitStructure
     */
    public function removeLevel10OrganisationunitStructure(\Hris\OrganisationunitBundle\Entity\OrganisationunitStructure $level10OrganisationunitStructure)
    {
        $this->level10OrganisationunitStructure->removeElement($level10OrganisationunitStructure);
    }

    /**
     * Get level10OrganisationunitStructure
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLevel10OrganisationunitStructure()
    {
        return $this->level10OrganisationunitStructure;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userInfo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->organisationunitGroup = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dashboardChart = new \Doctrine\Common\Collections\ArrayCollection();
        $this->active = FALSE;
        $this->level1OrganisationunitStructure = new \Doctrine\Common\Collections\ArrayCollection();
        $this->level2OrganisationunitStructure = new \Doctrine\Common\Collections\ArrayCollection();
        $this->level3OrganisationunitStructure = new \Doctrine\Common\Collections\ArrayCollection();
        $this->level4OrganisationunitStructure = new \Doctrine\Common\Collections\ArrayCollection();
        $this->level5OrganisationunitStructure = new \Doctrine\Common\Collections\ArrayCollection();
        $this->level6OrganisationunitStructure = new \Doctrine\Common\Collections\ArrayCollection();
        $this->level7OrganisationunitStructure = new \Doctrine\Common\Collections\ArrayCollection();
        $this->level8OrganisationunitStructure = new \Doctrine\Common\Collections\ArrayCollection();
        $this->level9OrganisationunitStructure = new \Doctrine\Common\Collections\ArrayCollection();
        $this->level10OrganisationunitStructure = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
}