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
use Gedmo\Mapping\Annotation as Gedmo;
use Hris\OrganisationunitBundle\Entity\OrganisationunitLevel;
use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\OrganisationunitBundle\Entity\OrganisationunitStructure
 *
 * @Gedmo\Loggable
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
     * @var string $uid
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="uid", type="string", length=13, unique=true)
     */
    private $uid;
    
    /**
     * @Gedmo\Versioned
     * @var Organisationunit $organisationunit
     *
     * @ORM\OneToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit", mappedBy="organisationunitStructure")
     * @ORM\JoinColumn(name="organisationunit_id", referencedColumnName="id", nullable=false,unique=true)
     */
    private $organisationunit;

    /**
     * @Gedmo\Versioned
     * @var \Hris\OrganisationunitBundle\Entity\OrganisationunitLevel $level
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitLevel",inversedBy="organisationunitStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * })
     */
    private $level;
    
    /**
     * @var Organisationunit $level1Organisationunit
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="level1OrganisationunitStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level1_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * })
     */
    private $level1Organisationunit;
    
    /**
     * @var Organisationunit $level2Organisationunit
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="level2OrganisationunitStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level2_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * })
     */
    private $level2Organisationunit;
    
    /**
     * @var Organisationunit $level3Organisationunit
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="level3OrganisationunitStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level3_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * })
     */
    private $level3Organisationunit;
    
    /**
     * @var Organisationunit $level4Organisationunit
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="level4OrganisationunitStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level4_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * })
     */
    private $level4Organisationunit;
    
    /**
     * @var Organisationunit $level5Organisationunit
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="level5OrganisationunitStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level5_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * })
     */
    private $level5Organisationunit;
    
    /**
     * @var Organisationunit $level6Organisationunit
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="level6OrganisationunitStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level6_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * })
     */
    private $level6Organisationunit;
    
    /**
     * @var \DateTime $datecreated
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="datecreated", type="datetime", nullable=false)
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
     * @param Organisationunit $organisationunit
     * @return OrganisationunitStructure
     */
    public function setOrganisationunit(Organisationunit $organisationunit)
    {
        $this->organisationunit = $organisationunit;
    
        return $this;
    }

    /**
     * Get organisationunit
     *
     * @return Organisationunit
     */
    public function getOrganisationunit()
    {
        return $this->organisationunit;
    }

    /**
     * Set level1Organisationunit
     *
     * @param Organisationunit $level1Organisationunit
     * @return OrganisationunitStructure
     */
    public function setLevel1Organisationunit(Organisationunit $level1Organisationunit = null)
    {
        $this->level1Organisationunit = $level1Organisationunit;
    
        return $this;
    }

    /**
     * Get level1Organisationunit
     *
     * @return Organisationunit
     */
    public function getLevel1Organisationunit()
    {
        return $this->level1Organisationunit;
    }

    /**
     * Set level2Organisationunit
     *
     * @param Organisationunit $level2Organisationunit
     * @return OrganisationunitStructure
     */
    public function setLevel2Organisationunit(Organisationunit $level2Organisationunit = null)
    {
        $this->level2Organisationunit = $level2Organisationunit;
    
        return $this;
    }

    /**
     * Get level2Organisationunit
     *
     * @return Organisationunit
     */
    public function getLevel2Organisationunit()
    {
        return $this->level2Organisationunit;
    }

    /**
     * Set level3Organisationunit
     *
     * @param Organisationunit $level3Organisationunit
     * @return OrganisationunitStructure
     */
    public function setLevel3Organisationunit(Organisationunit $level3Organisationunit = null)
    {
        $this->level3Organisationunit = $level3Organisationunit;
    
        return $this;
    }

    /**
     * Get level3Organisationunit
     *
     * @return Organisationunit
     */
    public function getLevel3Organisationunit()
    {
        return $this->level3Organisationunit;
    }

    /**
     * Set level4Organisationunit
     *
     * @param Organisationunit $level4Organisationunit
     * @return OrganisationunitStructure
     */
    public function setLevel4Organisationunit(Organisationunit $level4Organisationunit = null)
    {
        $this->level4Organisationunit = $level4Organisationunit;
    
        return $this;
    }

    /**
     * Get level4Organisationunit
     *
     * @return Organisationunit
     */
    public function getLevel4Organisationunit()
    {
        return $this->level4Organisationunit;
    }

    /**
     * Set level5Organisationunit
     *
     * @param Organisationunit $level5Organisationunit
     * @return OrganisationunitStructure
     */
    public function setLevel5Organisationunit(Organisationunit $level5Organisationunit = null)
    {
        $this->level5Organisationunit = $level5Organisationunit;
    
        return $this;
    }

    /**
     * Get level5Organisationunit
     *
     * @return Organisationunit
     */
    public function getLevel5Organisationunit()
    {
        return $this->level5Organisationunit;
    }

    /**
     * Set level6Organisationunit
     *
     * @param Organisationunit $level6Organisationunit
     * @return OrganisationunitStructure
     */
    public function setLevel6Organisationunit(Organisationunit $level6Organisationunit = null)
    {
        $this->level6Organisationunit = $level6Organisationunit;
    
        return $this;
    }

    /**
     * Get level6Organisationunit
     *
     * @return Organisationunit
     */
    public function getLevel6Organisationunit()
    {
        return $this->level6Organisationunit;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->uid = uniqid();
    }

    /**
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        $organisationunitStructure = 'Organisationunit:'.$this->getOrganisationunit().' Level:'.$this->getLevel();
        return $organisationunitStructure;
    }
}