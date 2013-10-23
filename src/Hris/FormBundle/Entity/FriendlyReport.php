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
namespace Hris\FormBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Hris\FormBundle\Entity\RelationalFilter;
use Hris\FormBundle\Entity\ArithmeticFilter;
use Hris\FormBundle\Entity\FriendlyReportCategory;
use Hris\FormBundle\Entity\FieldOptionGroup;
use Hris\IndicatorBundle\Entity\Target;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\FormBundle\Entity\FriendlyReport
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="hris_friendlyreport")
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\FriendlyReportRepository")
 */
class FriendlyReport
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
     * @var string $name
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="name", type="string", length=64, unique=true)
     */
    private $name;

    /**
     * @var string $description
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer $sort
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="sort", type="integer")
     */
    private $sort;
    
    /**
     * @var FieldOptionGroup $serie
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\FieldOptionGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="series_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $serie;
    
    /**
     * @var FriendlyReportCategory $friendlyReportCategory
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\FriendlyReportCategory", mappedBy="friendlyReport",cascade={"ALL"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $friendlyReportCategory;

    /**
     * @var boolean $ignoreSkipInReport
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="ignoreSkipInReport", type="boolean", nullable=true)
     */
    private $ignoreSkipInReport;

    /**
     * @var boolean $useTargets
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="useTargets", type="boolean", nullable=true)
     */
    private $useTargets;

    /**
     * @var boolean $showDeficitSurplus
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="showDeficitSurplus", type="boolean", nullable=true)
     */
    private $showDeficitSurplus;
    
    /**
     * @var ArithmeticFilter $arithmeticFilter
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\ArithmeticFilter", inversedBy="friendlyReport")
     * @ORM\JoinTable(name="hris_friendlyreport_arithmeticfilter",
     *   joinColumns={
     *     @ORM\JoinColumn(name="friendlyreport_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="arithmeticfilter_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $arithmeticFilter;
    
    /**
     * @var RelationalFilter $relationalFilter
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\RelationalFilter", inversedBy="friendlyReport")
     * @ORM\JoinTable(name="hris_friendlyreport_relationalfilter",
     *   joinColumns={
     *     @ORM\JoinColumn(name="friendlyreport_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="relationalfilter_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $relationalFilter;

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
     * Set name
     *
     * @param string $name
     * @return FriendlyReport
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
     * Set description
     *
     * @param string $description
     * @return FriendlyReport
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
     * Set sort
     *
     * @param integer $sort
     * @return FriendlyReport
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer
     */
    public function getSort()
    {
        return $this->sort;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->friendlyReportCategory = new ArrayCollection();
        $this->arithmeticFilter = new ArrayCollection();
        $this->relationalFilter = new ArrayCollection();
        $this->sort = 0;
        $this->uid = uniqid();
        $this->showDeficitSurplus = False;
    }
    
    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return FriendlyReport
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
     * @return FriendlyReport
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
     * @return FriendlyReport
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
     * Add friendlyReportCategory
     *
     * @param FriendlyReportCategory $friendlyReportCategory
     * @return FriendlyReport
     */
    public function addFriendlyReportCategory(FriendlyReportCategory $friendlyReportCategory)
    {
        $this->friendlyReportCategory[] = $friendlyReportCategory;
    
        return $this;
    }

    /**
     * Remove friendlyReportCategory
     *
     * @param FriendlyReportCategory $friendlyReportCategory
     */
    public function removeFriendlyReportCategory(FriendlyReportCategory $friendlyReportCategory)
    {
        $this->friendlyReportCategory->removeElement($friendlyReportCategory);
    }

    /**
     * Remove All friendlyReportCategory
     *
     */
    public function removeAllFriendlyReportCategory()
    {
        foreach($this->friendlyReportCategory as $key=>$reportCategory) {
            $this->friendlyReportCategory->removeElement($reportCategory);
        }
    }

    /**
     * Get friendlyReportCategory
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFriendlyReportCategory()
    {
        return $this->friendlyReportCategory;
    }

    /**
     * Add arithmeticFilter
     *
     * @param ArithmeticFilter $arithmeticFilter
     * @return FriendlyReport
     */
    public function addArithmeticFilter(ArithmeticFilter $arithmeticFilter)
    {
        $this->arithmeticFilter[$arithmeticFilter->getId()] = $arithmeticFilter;
    
        return $this;
    }

    /**
     * Remove arithmeticFilter
     *
     * @param ArithmeticFilter $arithmeticFilter
     */
    public function removeArithmeticFilter(ArithmeticFilter $arithmeticFilter)
    {
        $this->arithmeticFilter->removeElement($arithmeticFilter);
    }

    /**
     * Get arithmeticFilter
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArithmeticFilter()
    {
        return $this->arithmeticFilter;
    }

    /**
     * Add relationalFilter
     *
     * @param RelationalFilter $relationalFilter
     * @return FriendlyReport
     */
    public function addRelationalFilter(RelationalFilter $relationalFilter)
    {
        $this->relationalFilter[$relationalFilter->getId()] = $relationalFilter;
    
        return $this;
    }

    /**
     * Remove relationalFilter
     *
     * @param RelationalFilter $relationalFilter
     */
    public function removeRelationalFilter(RelationalFilter $relationalFilter)
    {
        $this->relationalFilter->removeElement($relationalFilter);
    }

    /**
     * Get relationalFilter
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRelationalFilter()
    {
        return $this->relationalFilter;
    }

    /**
     * Set serie
     *
     * @param FieldOptionGroup $serie
     * @return FriendlyReport
     */
    public function setSerie(FieldOptionGroup $serie = null)
    {
        $this->serie = $serie;
    
        return $this;
    }

    /**
     * Get serie
     *
     * @return FieldOptionGroup
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Set useTargets
     *
     * @param boolean $useTargets
     * @return FriendlyReport
     */
    public function setUseTargets($useTargets)
    {
        $this->useTargets = $useTargets;

        return $this;
    }

    /**
     * Get useTargets
     *
     * @return boolean
     */
    public function getUseTargets()
    {
        return $this->useTargets;
    }

    /**
     * Set showDeficitSurplus
     *
     * @param boolean $showDeficitSurplus
     * @return FriendlyReport
     */
    public function setShowDeficitSurplus($showDeficitSurplus)
    {
        $this->showDeficitSurplus = $showDeficitSurplus;

        return $this;
    }

    /**
     * Get showDeficitSurplus
     *
     * @return boolean
     */
    public function getShowDeficitSurplus()
    {
        return $this->showDeficitSurplus;
    }
    
    /**
     * Set ignoreSkipInReport
     *
     * @param boolean $ignoreSkipInReport
     * @return FriendlyReport
     */
    public function setIgnoreSkipInReport($ignoreSkipInReport)
    {
        $this->ignoreSkipInReport = $ignoreSkipInReport;

        return $this;
    }

    /**
     * Get ignoreSkipInReport
     *
     * @return boolean
     */
    public function getIgnoreSkipInReport()
    {
        return $this->ignoreSkipInReport;
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