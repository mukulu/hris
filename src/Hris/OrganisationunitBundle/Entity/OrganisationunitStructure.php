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

use Doctrine\ORM\Mapping as ORM;

use Hris\OrganisationunitBundle\Entity\OrganisationunitLevel;
use Hris\OrganisationunitBundle\Entity\Organisationunit;

/**
 * Hris\OrganisationunitBundle\Entity\OrganisationunitStructure
 *
 * @ORM\Table(name="hris_organisationunitstructure")
 * @ORM\Entity(repositoryClass="Hris\OrganisationunitBundle\Entity\OrganisationunitStructureRepository")
 */
class OrganisationunitStructure
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
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     *
     * @ORM\OneToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit", mappedBy="organisationunitStructure")
     * @ORM\JoinColumn(name="organisationunit_id", referencedColumnName="id", nullable=false,unique=true)
     */
    private $organisationunit;

    /**
     * @var Hris\OrganisationunitBundle\Entity\OrganisationunitLevel $level
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitLevel",inversedBy="organisationunitStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level", referencedColumnName="id", nullable=true)
     * })
     */
    private $level;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level1Organisationunit
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="level1OrganisationunitStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level1_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level1Organisationunit;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level2Organisationunit
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="level2OrganisationunitStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level2_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level2Organisationunit;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level3Organisationunit
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="level3OrganisationunitStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level3_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level3Organisationunit;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level4Organisationunit
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="level4OrganisationunitStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level4_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level4Organisationunit;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level5Organisationunit
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="level5OrganisationunitStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level5_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level5Organisationunit;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level6Organisationunit
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="level6OrganisationunitStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level6_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level6Organisationunit;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level7Organisationunit
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="level7OrganisationunitStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level7_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level7Organisationunit;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level8Organisationunit
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="level8OrganisationunitStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level8_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level8Organisationunit;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level9Organisationunit
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="level9OrganisationunitStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level9_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level9Organisationunit;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level10Organisationunit
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="level10OrganisationunitStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level10_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level10Organisationunit;
    
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
     * @return OrganisationunitStructure
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
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return OrganisationunitStructure
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
     * @return OrganisationunitStructure
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
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     * @return OrganisationunitStructure
     */
    public function setOrganisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit)
    {
        $this->organisationunit = $organisationunit;
    
        return $this;
    }

    /**
     * Get organisationunit
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getOrganisationunit()
    {
        return $this->organisationunit;
    }

    /**
     * Set level1Organisationunit
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level1Organisationunit
     * @return OrganisationunitStructure
     */
    public function setLevel1Organisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $level1Organisationunit = null)
    {
        $this->level1Organisationunit = $level1Organisationunit;
    
        return $this;
    }

    /**
     * Get level1Organisationunit
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel1Organisationunit()
    {
        return $this->level1Organisationunit;
    }

    /**
     * Set level2Organisationunit
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level2Organisationunit
     * @return OrganisationunitStructure
     */
    public function setLevel2Organisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $level2Organisationunit = null)
    {
        $this->level2Organisationunit = $level2Organisationunit;
    
        return $this;
    }

    /**
     * Get level2Organisationunit
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel2Organisationunit()
    {
        return $this->level2Organisationunit;
    }

    /**
     * Set level3Organisationunit
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level3Organisationunit
     * @return OrganisationunitStructure
     */
    public function setLevel3Organisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $level3Organisationunit = null)
    {
        $this->level3Organisationunit = $level3Organisationunit;
    
        return $this;
    }

    /**
     * Get level3Organisationunit
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel3Organisationunit()
    {
        return $this->level3Organisationunit;
    }

    /**
     * Set level4Organisationunit
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level4Organisationunit
     * @return OrganisationunitStructure
     */
    public function setLevel4Organisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $level4Organisationunit = null)
    {
        $this->level4Organisationunit = $level4Organisationunit;
    
        return $this;
    }

    /**
     * Get level4Organisationunit
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel4Organisationunit()
    {
        return $this->level4Organisationunit;
    }

    /**
     * Set level5Organisationunit
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level5Organisationunit
     * @return OrganisationunitStructure
     */
    public function setLevel5Organisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $level5Organisationunit = null)
    {
        $this->level5Organisationunit = $level5Organisationunit;
    
        return $this;
    }

    /**
     * Get level5Organisationunit
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel5Organisationunit()
    {
        return $this->level5Organisationunit;
    }

    /**
     * Set level6Organisationunit
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level6Organisationunit
     * @return OrganisationunitStructure
     */
    public function setLevel6Organisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $level6Organisationunit = null)
    {
        $this->level6Organisationunit = $level6Organisationunit;
    
        return $this;
    }

    /**
     * Get level6Organisationunit
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel6Organisationunit()
    {
        return $this->level6Organisationunit;
    }

    /**
     * Set level7Organisationunit
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level7Organisationunit
     * @return OrganisationunitStructure
     */
    public function setLevel7Organisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $level7Organisationunit = null)
    {
        $this->level7Organisationunit = $level7Organisationunit;
    
        return $this;
    }

    /**
     * Get level7Organisationunit
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel7Organisationunit()
    {
        return $this->level7Organisationunit;
    }

    /**
     * Set level8Organisationunit
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level8Organisationunit
     * @return OrganisationunitStructure
     */
    public function setLevel8Organisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $level8Organisationunit = null)
    {
        $this->level8Organisationunit = $level8Organisationunit;
    
        return $this;
    }

    /**
     * Get level8Organisationunit
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel8Organisationunit()
    {
        return $this->level8Organisationunit;
    }

    /**
     * Set level9Organisationunit
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level9Organisationunit
     * @return OrganisationunitStructure
     */
    public function setLevel9Organisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $level9Organisationunit = null)
    {
        $this->level9Organisationunit = $level9Organisationunit;
    
        return $this;
    }

    /**
     * Get level9Organisationunit
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel9Organisationunit()
    {
        return $this->level9Organisationunit;
    }

    /**
     * Set level10Organisationunit
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level10Organisationunit
     * @return OrganisationunitStructure
     */
    public function setLevel10Organisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $level10Organisationunit = null)
    {
        $this->level10Organisationunit = $level10Organisationunit;
    
        return $this;
    }

    /**
     * Get level10Organisationunit
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel10Organisationunit()
    {
        return $this->level10Organisationunit;
    }
}