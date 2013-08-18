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

use Doctrine\ORM\Mapping as ORM;

use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\OrganisationunitBundle\Entity\OrganisationunitCompleteness
 *
 * @ORM\Table(name="hris_organisationunitcompleteness")
 * @ORM\Entity(repositoryClass="Hris\OrganisationunitBundle\Entity\OrganisationunitCompletenessRepository")
 */
class OrganisationunitCompleteness
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
     * @ORM\Column(name="uid", type="string", length=13, unique=true)
     */
    private $uid;
    
    /**
     * @var \Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organisationunit_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * })
     */
    private $organisationunit;

    /**
     * @var integer $expectation
     *
     * @ORM\Column(name="expectation", type="integer", nullable=true)
     */
    private $expectation;
    
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
     * Constructor
     */
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
     * Set expectation
     *
     * @param integer $expectation
     * @return OrganisationunitCompleteness
     */
    public function setExpectation($expectation)
    {
        $this->expectation = $expectation;
    
        return $this;
    }

    /**
     * Get expectation
     *
     * @return integer 
     */
    public function getExpectation()
    {
        return $this->expectation;
    }

    /**
     * Set organisationunit
     *
     * @param \Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     * @return OrganisationunitCompleteness
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
     * Set uid
     *
     * @param string $uid
     * @return OrganisationunitCompleteness
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
     * Set lastupdated
     *
     * @param \DateTime $lastupdated
     * @return OrganisationunitCompleteness
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
     * @return OrganisationunitCompleteness
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
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        $completeness = 'Organisationunit:'.$this->getOrganisationunit().' Expectation:'.$this->getExpectation();
        return $completeness;
    }
}