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

use Hris\OrganisationunitBundle\Entity\OrganisationunitLevel;
use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Doctrine\ORM\Mapping as ORM;

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
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $rganisationunit
     *
     * @ORM\OneToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit", mappedBy="organisationunitStructure")
     * @ORM\JoinColumn(name="organisationunit_id", referencedColumnName="id", nullable=false,unique=true)
     */
    private $rganisationunit;

    /**
     * @var Hris\OrganisationunitBundle\Entity\OrganisationunitLevel $level
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitLevel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level", referencedColumnName="id", nullable=true)
     * })
     */
    private $level;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level1Id
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level1_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level1Id;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level2Id
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level2_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level2Id;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level3Id
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level3_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level3Id;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level4Id
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level4_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level4Id;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level5Id
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level5_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level5Id;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level6Id
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level6_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level6Id;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level7Id
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level7_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level7Id;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level8Id
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level8_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level8Id;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level9Id
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level9_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level9Id;
    
    /**
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $level10Id
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="level10_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $level10Id;
    
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
     * Set rganisationunit
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $rganisationunit
     * @return OrganisationunitStructure
     */
    public function setRganisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $rganisationunit)
    {
        $this->rganisationunit = $rganisationunit;
    
        return $this;
    }

    /**
     * Get rganisationunit
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getRganisationunit()
    {
        return $this->rganisationunit;
    }

    /**
     * Set level1Id
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level1Id
     * @return OrganisationunitStructure
     */
    public function setLevel1Id(\Hris\OrganisationunitBundle\Entity\Organisationunit $level1Id = null)
    {
        $this->level1Id = $level1Id;
    
        return $this;
    }

    /**
     * Get level1Id
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel1Id()
    {
        return $this->level1Id;
    }

    /**
     * Set level2Id
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level2Id
     * @return OrganisationunitStructure
     */
    public function setLevel2Id(\Hris\OrganisationunitBundle\Entity\Organisationunit $level2Id = null)
    {
        $this->level2Id = $level2Id;
    
        return $this;
    }

    /**
     * Get level2Id
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel2Id()
    {
        return $this->level2Id;
    }

    /**
     * Set level3Id
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level3Id
     * @return OrganisationunitStructure
     */
    public function setLevel3Id(\Hris\OrganisationunitBundle\Entity\Organisationunit $level3Id = null)
    {
        $this->level3Id = $level3Id;
    
        return $this;
    }

    /**
     * Get level3Id
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel3Id()
    {
        return $this->level3Id;
    }

    /**
     * Set level4Id
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level4Id
     * @return OrganisationunitStructure
     */
    public function setLevel4Id(\Hris\OrganisationunitBundle\Entity\Organisationunit $level4Id = null)
    {
        $this->level4Id = $level4Id;
    
        return $this;
    }

    /**
     * Get level4Id
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel4Id()
    {
        return $this->level4Id;
    }

    /**
     * Set level5Id
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level5Id
     * @return OrganisationunitStructure
     */
    public function setLevel5Id(\Hris\OrganisationunitBundle\Entity\Organisationunit $level5Id = null)
    {
        $this->level5Id = $level5Id;
    
        return $this;
    }

    /**
     * Get level5Id
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel5Id()
    {
        return $this->level5Id;
    }

    /**
     * Set level6Id
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level6Id
     * @return OrganisationunitStructure
     */
    public function setLevel6Id(\Hris\OrganisationunitBundle\Entity\Organisationunit $level6Id = null)
    {
        $this->level6Id = $level6Id;
    
        return $this;
    }

    /**
     * Get level6Id
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel6Id()
    {
        return $this->level6Id;
    }

    /**
     * Set level7Id
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level7Id
     * @return OrganisationunitStructure
     */
    public function setLevel7Id(\Hris\OrganisationunitBundle\Entity\Organisationunit $level7Id = null)
    {
        $this->level7Id = $level7Id;
    
        return $this;
    }

    /**
     * Get level7Id
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel7Id()
    {
        return $this->level7Id;
    }

    /**
     * Set level8Id
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level8Id
     * @return OrganisationunitStructure
     */
    public function setLevel8Id(\Hris\OrganisationunitBundle\Entity\Organisationunit $level8Id = null)
    {
        $this->level8Id = $level8Id;
    
        return $this;
    }

    /**
     * Get level8Id
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel8Id()
    {
        return $this->level8Id;
    }

    /**
     * Set level9Id
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level9Id
     * @return OrganisationunitStructure
     */
    public function setLevel9Id(\Hris\OrganisationunitBundle\Entity\Organisationunit $level9Id = null)
    {
        $this->level9Id = $level9Id;
    
        return $this;
    }

    /**
     * Get level9Id
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel9Id()
    {
        return $this->level9Id;
    }

    /**
     * Set level10Id
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $level10Id
     * @return OrganisationunitStructure
     */
    public function setLevel10Id(\Hris\OrganisationunitBundle\Entity\Organisationunit $level10Id = null)
    {
        $this->level10Id = $level10Id;
    
        return $this;
    }

    /**
     * Get level10Id
     *
     * @return Hris\OrganisationunitBundle\Entity\Organisationunit 
     */
    public function getLevel10Id()
    {
        return $this->level10Id;
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
}