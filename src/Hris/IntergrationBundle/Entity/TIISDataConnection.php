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
namespace Hris\IntergrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Hris\IntergrationBundle\Entity\TIISEmployeeFieldRelation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TIISDataConnection
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="hris_intergration_tiis_data_connection")
 * @ORM\Entity(repositoryClass="Hris\IntergrationBundle\Entity\TIISDataConnectionRepository")
 */
class TIISDataConnection
{
    /**
     * @var integer
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
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="recordstablename", type="string", length=64)
     */
    private $recordstablename;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="organisationunittablename", type="string", length=64)
     */
    private $organisationunitTableName;

    /**
     * @var string $organisationunitLongnameColumNname
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="organisationunitlongnamecolumnname", type="string", length=64)
     */
    private $organisationunitLongnameColumNname;

    /**
     * @var string $organisationunitCodeColumnName
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="organisationunitcodecolumnname", type="string", length=64)
     */
    private $organisationunitCodeColumnName;

    /**
     * @var string $organisationunitOwnershipColumnName
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="organisationunitownershipcolumnname", type="string", length=64)
     */
    private $organisationunitOwnershipColumnName;

    /**
     * @var string $recordsOrganisationunitColumnName
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="recordsorganisationunitcolumnname", type="string", length=64)
     */
    private $recordsOrganisationunitColumnName;

    /**
     * @var string $recordsInstanceColumnName
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="recordsinstancecolumnname", type="string", length=64)
     */
    private $recordsInstanceColumnName;

    /**
     * @var string $tiisParentOrganisationunitCode
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="tiisparentorganisationunitcode", type="string", length=64)
     */
    private $tiisParentOrganisationunitCode;

    /**
     * Top most of of organisationunit hierarchy(who's children will be remapped)
     * @var string $hrisTopMostOrganisationunitShrotname
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="hristopmostorganisationunitshrotname", type="string", length=64)
     */
    private $hrisTopMostOrganisationunitShrotname;

    /**
     * @var string $hrisInstituionGroupName
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="hrisinstituiongroupname", type="string", length=64)
     */
    private $hrisInstituionGroupName;

    /**
     * @var string $hostUrl
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="host_url", type="string", length=255, nullable=false)
     */
    private $hostUrl;

    /**
     * @var string $password
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="password", type="string", length=64)
     */
    private $password;

    /**
     * @var string $username
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="username", type="string", length=64)
     */
    private $username;

    /**
     * @var string $database
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="database", type="string", length=64)
     */
    private $database;

    /**
     * @var string $employeeFormName
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="employeeformname", type="string", length=64)
     */
    private $employeeFormName;

    /**
     * @var string $defaultNationality
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="defaultnationality", type="string", length=64)
     */
    private $defaultNationality;

    /**
     * @var string $defaultHrNationality
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="defaulthrnationality", type="string", length=64)
     */
    private $defaultHrNationality;

    /**
     * @var Organisationunit $organisationunit
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="tiisDataConnection")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organisationunit_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $organisationunit;

    /**
     * @var TIISEmployeeFieldRelation $tiisEmployeeFieldRelation
     *
     * @ORM\OneToMany(targetEntity="Hris\IntergrationBundle\Entity\TIISEmployeeFieldRelation", mappedBy="dhisDataConnection",cascade={"ALL"})
     * @ORM\OrderBy({"dataelementname" = "ASC"})
     */
    private $tiisEmployeeFieldRelation;

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

    public function __construct()
    {
        $this->uid = uniqid();
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
     * Set name
     *
     * @param string $name
     * @return TIISDataConnection
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
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return TIISDataConnection
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
     * Set recordstablename
     *
     * @param string $recordstablename
     * @return TIISDataConnection
     */
    public function setRecordstablename($recordstablename)
    {
        $this->recordstablename = $recordstablename;
    
        return $this;
    }

    /**
     * Get recordstablename
     *
     * @return string 
     */
    public function getRecordstablename()
    {
        return $this->recordstablename;
    }

    /**
     * Set organisationunitTableName
     *
     * @param string $organisationunitTableName
     * @return TIISDataConnection
     */
    public function setOrganisationunitTableName($organisationunitTableName)
    {
        $this->organisationunitTableName = $organisationunitTableName;
    
        return $this;
    }

    /**
     * Get organisationunitTableName
     *
     * @return string 
     */
    public function getOrganisationunitTableName()
    {
        return $this->organisationunitTableName;
    }

    /**
     * Set organisationunitLongnameColumNname
     *
     * @param string $organisationunitLongnameColumNname
     * @return TIISDataConnection
     */
    public function setOrganisationunitLongnameColumNname($organisationunitLongnameColumNname)
    {
        $this->organisationunitLongnameColumNname = $organisationunitLongnameColumNname;
    
        return $this;
    }

    /**
     * Get organisationunitLongnameColumNname
     *
     * @return string 
     */
    public function getOrganisationunitLongnameColumNname()
    {
        return $this->organisationunitLongnameColumNname;
    }

    /**
     * Set organisationunitCodeColumnName
     *
     * @param string $organisationunitCodeColumnName
     * @return TIISDataConnection
     */
    public function setOrganisationunitCodeColumnName($organisationunitCodeColumnName)
    {
        $this->organisationunitCodeColumnName = $organisationunitCodeColumnName;
    
        return $this;
    }

    /**
     * Get organisationunitCodeColumnName
     *
     * @return string 
     */
    public function getOrganisationunitCodeColumnName()
    {
        return $this->organisationunitCodeColumnName;
    }

    /**
     * Set organisationunitOwnershipColumnName
     *
     * @param string $organisationunitOwnershipColumnName
     * @return TIISDataConnection
     */
    public function setOrganisationunitOwnershipColumnName($organisationunitOwnershipColumnName)
    {
        $this->organisationunitOwnershipColumnName = $organisationunitOwnershipColumnName;
    
        return $this;
    }

    /**
     * Get organisationunitOwnershipColumnName
     *
     * @return string 
     */
    public function getOrganisationunitOwnershipColumnName()
    {
        return $this->organisationunitOwnershipColumnName;
    }

    /**
     * Set recordsOrganisationunitColumnName
     *
     * @param string $recordsOrganisationunitColumnName
     * @return TIISDataConnection
     */
    public function setRecordsOrganisationunitColumnName($recordsOrganisationunitColumnName)
    {
        $this->recordsOrganisationunitColumnName = $recordsOrganisationunitColumnName;
    
        return $this;
    }

    /**
     * Get recordsOrganisationunitColumnName
     *
     * @return string 
     */
    public function getRecordsOrganisationunitColumnName()
    {
        return $this->recordsOrganisationunitColumnName;
    }

    /**
     * Set recordsInstanceColumnName
     *
     * @param string $recordsInstanceColumnName
     * @return TIISDataConnection
     */
    public function setRecordsInstanceColumnName($recordsInstanceColumnName)
    {
        $this->recordsInstanceColumnName = $recordsInstanceColumnName;
    
        return $this;
    }

    /**
     * Get recordsInstanceColumnName
     *
     * @return string 
     */
    public function getRecordsInstanceColumnName()
    {
        return $this->recordsInstanceColumnName;
    }

    /**
     * Set tiisParentOrganisationunitCode
     *
     * @param string $tiisParentOrganisationunitCode
     * @return TIISDataConnection
     */
    public function setTiisParentOrganisationunitCode($tiisParentOrganisationunitCode)
    {
        $this->tiisParentOrganisationunitCode = $tiisParentOrganisationunitCode;
    
        return $this;
    }

    /**
     * Get tiisParentOrganisationunitCode
     *
     * @return string 
     */
    public function getTiisParentOrganisationunitCode()
    {
        return $this->tiisParentOrganisationunitCode;
    }

    /**
     * Set hrisTopMostOrganisationunitShrotname
     *
     * @param string $hrisTopMostOrganisationunitShrotname
     * @return TIISDataConnection
     */
    public function setHrisTopMostOrganisationunitShrotname($hrisTopMostOrganisationunitShrotname)
    {
        $this->hrisTopMostOrganisationunitShrotname = $hrisTopMostOrganisationunitShrotname;
    
        return $this;
    }

    /**
     * Get hrisTopMostOrganisationunitShrotname
     *
     * @return string 
     */
    public function getHrisTopMostOrganisationunitShrotname()
    {
        return $this->hrisTopMostOrganisationunitShrotname;
    }

    /**
     * Set hrisInstituionGroupName
     *
     * @param string $hrisInstituionGroupName
     * @return TIISDataConnection
     */
    public function setHrisInstituionGroupName($hrisInstituionGroupName)
    {
        $this->hrisInstituionGroupName = $hrisInstituionGroupName;
    
        return $this;
    }

    /**
     * Get hrisInstituionGroupName
     *
     * @return string 
     */
    public function getHrisInstituionGroupName()
    {
        return $this->hrisInstituionGroupName;
    }

    /**
     * Set hostUrl
     *
     * @param string $hostUrl
     * @return TIISDataConnection
     */
    public function setHostUrl($hostUrl)
    {
        $this->hostUrl = $hostUrl;
    
        return $this;
    }

    /**
     * Get hostUrl
     *
     * @return string 
     */
    public function getHostUrl()
    {
        return $this->hostUrl;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return TIISDataConnection
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return TIISDataConnection
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
     * Set database
     *
     * @param string $database
     * @return TIISDataConnection
     */
    public function setDatabase($database)
    {
        $this->database = $database;
    
        return $this;
    }

    /**
     * Get database
     *
     * @return string 
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Set employeeFormName
     *
     * @param string $employeeFormName
     * @return TIISDataConnection
     */
    public function setEmployeeFormName($employeeFormName)
    {
        $this->employeeFormName = $employeeFormName;
    
        return $this;
    }

    /**
     * Get employeeFormName
     *
     * @return string 
     */
    public function getEmployeeFormName()
    {
        return $this->employeeFormName;
    }

    /**
     * Set defaultNationality
     *
     * @param string $defaultNationality
     * @return TIISDataConnection
     */
    public function setDefaultNationality($defaultNationality)
    {
        $this->defaultNationality = $defaultNationality;
    
        return $this;
    }

    /**
     * Get defaultNationality
     *
     * @return string 
     */
    public function getDefaultNationality()
    {
        return $this->defaultNationality;
    }

    /**
     * Set defaultHrNationality
     *
     * @param string $defaultHrNationality
     * @return TIISDataConnection
     */
    public function setDefaultHrNationality($defaultHrNationality)
    {
        $this->defaultHrNationality = $defaultHrNationality;
    
        return $this;
    }

    /**
     * Get defaultHrNationality
     *
     * @return string 
     */
    public function getDefaultHrNationality()
    {
        return $this->defaultHrNationality;
    }

    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return TIISDataConnection
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
     * @return TIISDataConnection
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
     * Set organisationunit
     *
     * @param \Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     * @return TIISDataConnection
     */
    public function setOrganisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit = null)
    {
        $this->organisationunit = $organisationunit;
    
        return $this;
    }

    /**
     * Get organisationunit
     *
     * @return \Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getOrganisationunit()
    {
        return $this->organisationunit;
    }

    /**
     * Add tiisEmployeeFieldRelation
     *
     * @param TIISEmployeeFieldRelation $tiisEmployeeFieldRelation
     * @return TIISDataConnection
     */
    public function addTiisEmployeeFieldRelation(TIISEmployeeFieldRelation $tiisEmployeeFieldRelation)
    {
        $this->tiisEmployeeFieldRelation[] = $tiisEmployeeFieldRelation;
    
        return $this;
    }

    /**
     * Remove tiisEmployeeFieldRelation
     *
     * @param TIISEmployeeFieldRelation $tiisEmployeeFieldRelation
     */
    public function removeTiisEmployeeFieldRelation(TIISEmployeeFieldRelation $tiisEmployeeFieldRelation)
    {
        $this->tiisEmployeeFieldRelation->removeElement($tiisEmployeeFieldRelation);
    }

    /**
     * Get tiisEmployeeFieldRelation
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTiisEmployeeFieldRelation()
    {
        return $this->tiisEmployeeFieldRelation;
    }
}