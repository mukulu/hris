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
use Hris\OrganisationunitBundle\Entity\OrganisationunitGroup;
use Hris\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\OrganisationunitBundle\Entity\Organisationunit
 *
 * @ORM\Table(name="hris_organisationunit",uniqueConstraints={@ORM\UniqueConstraint(name="organisationunits_with_one_parent_idx",columns={"longname", "parent_id"})})
 * @ORM\Entity(repositoryClass="Hris\OrganisationunitBundle\Entity\OrganisationunitRepository")
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
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
     * @ORM\Column(name="dhisUid", type="string", length=11, unique=true, nullable=true)
     */
    private $dhisUid;

    /**
     * @var OrganisationunitGroup $organisationunitGroup
     *
     * @ORM\ManyToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitGroup", mappedBy="organisationunit")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $organisationunitGroup;

    /**
     * @var string $code
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="code", type="string", length=25, nullable=true, unique=true)
     */
    private $code;

    /**
     * @var \Hris\OrganisationunitBundle\Entity\Organisationunit $parent
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * })
     */
    private $parent;

    /**
     * @var string $shortname
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="shortname", type="string", length=20, unique=true)
     */
    private $shortname;

    /**
     * @var string $longname
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="longname", type="string", length=64)
     */
    private $longname;

    /**
     * @var boolean $active
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var \DateTime $openingdate
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="openingdate", type="date", nullable=true)
     */
    private $openingdate;

    /**
     * @var \DateTime $closingdate
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="closingdate", type="date", nullable=true)
     */
    private $closingdate;

    /**
     * @var string $geocode
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="geocode", type="string", length=255, nullable=true)
     */
    private $geocode;

    /**
     * @var string $coordinates
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="coordinates", type="text", nullable=true)
     */
    private $coordinates;

    /**
     * @var string $featuretype
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="featuretype", type="string", length=20, nullable=true)
     */
    private $featuretype;

    /**
     * @var string $address
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address;

    /**
     * @var string $email
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="email", type="string", length=150, nullable=true)
     */
    private $email;

    /**
     * @var string $phonenumber
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="phonenumber", type="string", length=150, nullable=true)
     */
    private $phonenumber;

    /**
     * @var string $contactperson
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="contactperson", type="string", length=150, nullable=true)
     */
    private $contactperson;

    /**
     * @var string $description
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var OrganisationunitCompleteness
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitCompleteness", mappedBy="organisationunit",cascade={"ALL"})
     * @ORM\OrderBy({"expectation" = "ASC"})
     */
    private $organisationunitCompleteness;

    /**
     * @var User $user
     *
     * @ORM\OneToMany(targetEntity="Hris\UserBundle\Entity\User", mappedBy="organisationunit",cascade={"ALL"})
     * @ORM\OrderBy({"longname" = "ASC"})
     */
    private $user;

    /**
     * @var OrganisationunitStructure $organisationunitStructure
     *
     * @ORM\OneToOne(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", mappedBy="organisationunit",cascade={"ALL"})
     */
    private $organisationunitStructure;

    /**
     * @var OrganisationunitStructure $level1OrganisationunitStructure
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", mappedBy="level1Organisationunit",cascade={"ALL"})
     */
    private $level1OrganisationunitStructure;

    /**
     * @var OrganisationunitStructure $level2OrganisationunitStructure
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", mappedBy="level2Organisationunit",cascade={"ALL"})
     */
    private $level2OrganisationunitStructure;

    /**
     * @var OrganisationunitStructure $level3OrganisationunitStructure
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", mappedBy="level3Organisationunit",cascade={"ALL"})
     */
    private $level3OrganisationunitStructure;

    /**
     * @var OrganisationunitStructure $level4OrganisationunitStructure
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", mappedBy="level4Organisationunit",cascade={"ALL"})
     */
    private $level4OrganisationunitStructure;

    /**
     * @var OrganisationunitStructure $level5OrganisationunitStructure
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", mappedBy="level5Organisationunit",cascade={"ALL"})
     */
    private $level5OrganisationunitStructure;

    /**
     * @var OrganisationunitStructure $level6OrganisationunitStructure
     *
     * @ORM\OneToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitStructure", mappedBy="level6Organisationunit",cascade={"ALL"})
     */
    private $level6OrganisationunitStructure;

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
     * Set contactperson
     *
     * @param string $contactperson
     * @return Organisationunit
     */
    public function setContactperson($contactperson)
    {
        $this->contactperson = $contactperson;

        return $this;
    }

    /**
     * Get contactperson
     *
     * @return string
     */
    public function getContactperson()
    {
        return $this->contactperson;
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
     * @param OrganisationunitGroup $organisationunitGroup
     * @return Organisationunit
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
     * Remove all organisationunitGroups
     *
     * @return Organisationunit
     */
    public function removeAllOrganisationunitGroups()
    {
        foreach($this->organisationunitGroup as $organisationunitGroupKey=>$organisationunitGroup) {
            $this->organisationunitGroup->removeElement($organisationunitGroup);
        }

        return $this;
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
     * Set organisationunitStructure
     *
     * @param OrganisationunitStructure $organisationunitStructure
     */
    public function setOrganisationunitStructure(OrganisationunitStructure $organisationunitStructure) {
        $this->organisationunitStructure = $organisationunitStructure;
    }

    /**
     * Get organisationunitStructure
     *
     * @return OrganisationunitStructure
     */
    public function getOrganisationunitStructure() {
        return $this->organisationunitStructure;
    }

    /**
     * Add level1OrganisationunitStructure
     *
     * @param OrganisationunitStructure $level1OrganisationunitStructure
     * @return Organisationunit
     */
    public function addLevel1OrganisationunitStructure(OrganisationunitStructure $level1OrganisationunitStructure)
    {
        $this->level1OrganisationunitStructure[$level1OrganisationunitStructure->getId()] = $level1OrganisationunitStructure;

        return $this;
    }

    /**
     * Remove level1OrganisationunitStructure
     *
     * @param OrganisationunitStructure $level1OrganisationunitStructure
     */
    public function removeLevel1OrganisationunitStructure(OrganisationunitStructure $level1OrganisationunitStructure)
    {
        $this->level1OrganisationunitStructure->removeElement($level1OrganisationunitStructure);
    }

    /**
     * Get level1OrganisationunitStructure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLevel1OrganisationunitStructure()
    {
        return $this->level1OrganisationunitStructure;
    }

    /**
     * Add level2OrganisationunitStructure
     *
     * @param OrganisationunitStructure $level2OrganisationunitStructure
     * @return Organisationunit
     */
    public function addLevel2OrganisationunitStructure(OrganisationunitStructure $level2OrganisationunitStructure)
    {
        $this->level2OrganisationunitStructure[$level2OrganisationunitStructure->getId()] = $level2OrganisationunitStructure;

        return $this;
    }

    /**
     * Remove level2OrganisationunitStructure
     *
     * @param OrganisationunitStructure $level2OrganisationunitStructure
     */
    public function removeLevel2OrganisationunitStructure(OrganisationunitStructure $level2OrganisationunitStructure)
    {
        $this->level2OrganisationunitStructure->removeElement($level2OrganisationunitStructure);
    }

    /**
     * Get level2OrganisationunitStructure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLevel2OrganisationunitStructure()
    {
        return $this->level2OrganisationunitStructure;
    }

    /**
     * Add level3OrganisationunitStructure
     *
     * @param OrganisationunitStructure $level3OrganisationunitStructure
     * @return Organisationunit
     */
    public function addLevel3OrganisationunitStructure(OrganisationunitStructure $level3OrganisationunitStructure)
    {
        $this->level3OrganisationunitStructure[$level3OrganisationunitStructure->getId()] = $level3OrganisationunitStructure;

        return $this;
    }

    /**
     * Remove level3OrganisationunitStructure
     *
     * @param OrganisationunitStructure $level3OrganisationunitStructure
     */
    public function removeLevel3OrganisationunitStructure(OrganisationunitStructure $level3OrganisationunitStructure)
    {
        $this->level3OrganisationunitStructure->removeElement($level3OrganisationunitStructure);
    }

    /**
     * Get level3OrganisationunitStructure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLevel3OrganisationunitStructure()
    {
        return $this->level3OrganisationunitStructure;
    }

    /**
     * Add level4OrganisationunitStructure
     *
     * @param OrganisationunitStructure $level4OrganisationunitStructure
     * @return Organisationunit
     */
    public function addLevel4OrganisationunitStructure(OrganisationunitStructure $level4OrganisationunitStructure)
    {
        $this->level4OrganisationunitStructure[$level4OrganisationunitStructure->getId()] = $level4OrganisationunitStructure;

        return $this;
    }

    /**
     * Remove level4OrganisationunitStructure
     *
     * @param OrganisationunitStructure $level4OrganisationunitStructure
     */
    public function removeLevel4OrganisationunitStructure(OrganisationunitStructure $level4OrganisationunitStructure)
    {
        $this->level4OrganisationunitStructure->removeElement($level4OrganisationunitStructure);
    }

    /**
     * Get level4OrganisationunitStructure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLevel4OrganisationunitStructure()
    {
        return $this->level4OrganisationunitStructure;
    }

    /**
     * Add level5OrganisationunitStructure
     *
     * @param OrganisationunitStructure $level5OrganisationunitStructure
     * @return Organisationunit
     */
    public function addLevel5OrganisationunitStructure(OrganisationunitStructure $level5OrganisationunitStructure)
    {
        $this->level5OrganisationunitStructure[$level5OrganisationunitStructure->getId()] = $level5OrganisationunitStructure;

        return $this;
    }

    /**
     * Remove level5OrganisationunitStructure
     *
     * @param OrganisationunitStructure $level5OrganisationunitStructure
     */
    public function removeLevel5OrganisationunitStructure(OrganisationunitStructure $level5OrganisationunitStructure)
    {
        $this->level5OrganisationunitStructure->removeElement($level5OrganisationunitStructure);
    }

    /**
     * Get level5OrganisationunitStructure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLevel5OrganisationunitStructure()
    {
        return $this->level5OrganisationunitStructure;
    }

    /**
     * Add level6OrganisationunitStructure
     *
     * @param OrganisationunitStructure $level6OrganisationunitStructure
     * @return Organisationunit
     */
    public function addLevel6OrganisationunitStructure(OrganisationunitStructure $level6OrganisationunitStructure)
    {
        $this->level6OrganisationunitStructure[$level6OrganisationunitStructure->getId()] = $level6OrganisationunitStructure;

        return $this;
    }

    /**
     * Remove level6OrganisationunitStructure
     *
     * @param OrganisationunitStructure $level6OrganisationunitStructure
     */
    public function removeLevel6OrganisationunitStructure(OrganisationunitStructure $level6OrganisationunitStructure)
    {
        $this->level6OrganisationunitStructure->removeElement($level6OrganisationunitStructure);
    }

    /**
     * Get level6OrganisationunitStructure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLevel6OrganisationunitStructure()
    {
        return $this->level6OrganisationunitStructure;
    }

    /**
     * Set parent
     *
     * @param \Hris\OrganisationunitBundle\Entity\Organisationunit $parent
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
     * @return \Hris\OrganisationunitBundle\Entity\Organisationunit
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
     * Constructor
     */
    public function __construct()
    {
        $this->organisationunitGroup = new \Doctrine\Common\Collections\ArrayCollection();
        $this->active = FALSE;
        $this->uid = uniqid();
    }

    /**
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->longname;
    }
    

    /**
     * Add organisationunitCompleteness
     *
     * @param OrganisationunitCompleteness $organisationunitCompleteness
     * @return Organisationunit
     */
    public function addOrganisationunitCompletenes(OrganisationunitCompleteness $organisationunitCompleteness)
    {
        $this->organisationunitCompleteness[] = $organisationunitCompleteness;
    
        return $this;
    }

    /**
     * Remove organisationunitCompleteness
     *
     * @param OrganisationunitCompleteness $organisationunitCompleteness
     */
    public function removeOrganisationunitCompletenes(OrganisationunitCompleteness $organisationunitCompleteness)
    {
        $this->organisationunitCompleteness->removeElement($organisationunitCompleteness);
    }

    /**
     * Get organisationunitCompleteness
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrganisationunitCompleteness()
    {
        return $this->organisationunitCompleteness;
    }

    /**
     * Add user
     *
     * @param \Hris\UserBundle\Entity\User $user
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
     * @param \Hris\UserBundle\Entity\User $user
     */
    public function removeUser(\Hris\UserBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUser()
    {
        return $this->user;
    }
}