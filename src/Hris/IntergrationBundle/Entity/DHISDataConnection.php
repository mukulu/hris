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
use Hris\IntergrationBundle\Entity\DataelementFieldOptionRelation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\IntergrationBundle\Entity\DHISDataConnection
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="hris_intergration_dhis_data_connection",uniqueConstraints={@ORM\UniqueConstraint(name="unique_serverdatasetname_idx", columns={"host_url","dataset_name"}),@ORM\UniqueConstraint(name="unique_serverdatasetuid_idx", columns={"host_url", "dataset_uid"})})
 * @ORM\Entity(repositoryClass="Hris\IntergrationBundle\Entity\DHISDataConnectionRepository")
 */
class DHISDataConnection
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
     * @var string $name
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="name", type="string", length=64, nullable=false)
     */
    private $name;

    /**
     * @var string $datasetName
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="dataset_name", type="string", length=64, nullable=false)
     */
    private $datasetName;

    /**
     * @var string $datasetUid
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="dataset_uid", type="string", length=64, nullable=false)
     */
    private $datasetUid;

    /**
     * @var string $hostUrl
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="host_url", type="string", length=255, nullable=false)
     */
    private $hostUrl;

    /**
     * @var string $username
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="username", type="string", length=64, nullable=false)
     */
    private $username;

    /**
     * @var string $password
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="password", type="string", length=64, nullable=false)
     */
    private $password;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FieldOptionGroupset", inversedBy="dhisDataConnection")
     * @ORM\JoinTable(name="hris_intergration_fieldoptiongroupset_member",
     *   joinColumns={
     *     @ORM\JoinColumn(name="dhis_data_connection_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="field_option_groupset_id", referencedColumnName="id")
     *   }
     * )
     */
    private $fieldOptionGroupset;

    /**
     * @var Organisationunit $organisationunit
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="dhisDataConnection")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_organisationunit_id", referencedColumnName="id")
     * })
     */
    private $parentOrganisationunit;

    /**
     * @var DataelementFieldOptionRelation $dataelementFieldOptionRelation
     *
     * @ORM\OneToMany(targetEntity="Hris\IntergrationBundle\Entity\DataelementFieldOptionRelation", mappedBy="dhisDataConnection",cascade={"ALL"})
     * @ORM\OrderBy({"dataelementname" = "ASC"})
     */
    private $dataelementFieldOptionRelation;

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
     * Constructor
     */
    public function __construct()
    {
        $this->uid = uniqid();
        $this->fieldOptionGroupset = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return DHISDataConnection
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
     * Set datasetName
     *
     * @param string $datasetName
     * @return DHISDataConnection
     */
    public function setDatasetName($datasetName)
    {
        $this->datasetName = $datasetName;
    
        return $this;
    }

    /**
     * Get datasetName
     *
     * @return string 
     */
    public function getDatasetName()
    {
        return $this->datasetName;
    }

    /**
     * Set datasetUid
     *
     * @param string $datasetUid
     * @return DHISDataConnection
     */
    public function setDatasetUid($datasetUid)
    {
        $this->datasetUid = $datasetUid;
    
        return $this;
    }

    /**
     * Get datasetUid
     *
     * @return string 
     */
    public function getDatasetUid()
    {
        return $this->datasetUid;
    }

    /**
     * Set hostUrl
     *
     * @param string $hostUrl
     * @return DHISDataConnection
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
     * Set username
     *
     * @param string $username
     * @return DHISDataConnection
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
     * Set password
     *
     * @param string $password
     * @return DHISDataConnection
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
     * Add fieldOptionGroupset
     *
     * @param \Hris\FormBundle\Entity\FieldOptionGroupset $fieldOptionGroupset
     * @return DHISDataConnection
     */
    public function addFieldOptionGroupset(\Hris\FormBundle\Entity\FieldOptionGroupset $fieldOptionGroupset)
    {
        $this->fieldOptionGroupset[] = $fieldOptionGroupset;
    
        return $this;
    }

    /**
     * Remove fieldOptionGroupset
     *
     * @param \Hris\FormBundle\Entity\FieldOptionGroupset $fieldOptionGroupset
     */
    public function removeFieldOptionGroupset(\Hris\FormBundle\Entity\FieldOptionGroupset $fieldOptionGroupset)
    {
        $this->fieldOptionGroupset->removeElement($fieldOptionGroupset);
    }

    /**
     * Get fieldOptionGroupset
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFieldOptionGroupset()
    {
        return $this->fieldOptionGroupset;
    }

    /**
     * Set parentOrganisationunit
     *
     * @param \Hris\OrganisationunitBundle\Entity\Organisationunit $parentOrganisationunit
     * @return DHISDataConnection
     */
    public function setParentOrganisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $parentOrganisationunit = null)
    {
        $this->parentOrganisationunit = $parentOrganisationunit;
    
        return $this;
    }

    /**
     * Get parentOrganisationunit
     *
     * @return \Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getParentOrganisationunit()
    {
        return $this->parentOrganisationunit;
    }

    /**
     * Add dataelementFieldOptionRelation
     *
     * @param \Hris\IntergrationBundle\Entity\DataelementFieldOptionRelation $dataelementFieldOptionRelation
     * @return DHISDataConnection
     */
    public function addDataelementFieldOptionRelation(\Hris\IntergrationBundle\Entity\DataelementFieldOptionRelation $dataelementFieldOptionRelation)
    {
        $this->dataelementFieldOptionRelation[] = $dataelementFieldOptionRelation;
    
        return $this;
    }

    /**
     * Remove dataelementFieldOptionRelation
     *
     * @param \Hris\IntergrationBundle\Entity\DataelementFieldOptionRelation $dataelementFieldOptionRelation
     */
    public function removeDataelementFieldOptionRelation(\Hris\IntergrationBundle\Entity\DataelementFieldOptionRelation $dataelementFieldOptionRelation)
    {
        $this->dataelementFieldOptionRelation->removeElement($dataelementFieldOptionRelation);
    }

    /**
     * Get dataelementFieldOptionRelation
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDataelementFieldOptionRelation()
    {
        return $this->dataelementFieldOptionRelation;
    }

    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return DHISDataConnection
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
     * @return DHISDataConnection
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
     * Set uid
     *
     * @param string $uid
     * @return DHISDataConnection
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
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}