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

use Doctrine\ORM\Mapping as ORM;

use Hris\FormBundle\Entity\RelationalFilter;
use Hris\FormBundle\Entity\ArithmeticFilter;
use Hris\FormBundle\Entity\FriendlyReportCategory;
use Hris\FormBundle\Entity\FieldOptionGroup;

/**
 * Hris\FormBundle\Entity\FriendlyReport
 *
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
     * @ORM\Column(name="uid", type="string", length=13, unique=true)
     */
    private $uid;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=64, unique=true)
     */
    private $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer $sort
     *
     * @ORM\Column(name="sort", type="integer")
     */
    private $sort;
    
    /**
     * @var \Hris\FormBundle\Entity\FieldOptionGroup $serie
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\FieldOptionGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="series_id", referencedColumnName="id")
     * })
     */
    private $serie;
    
    /**
     * @var \Hris\FormBundle\Entity\FriendlyReportCategory $friendlyReportCategory
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\FriendlyReportCategory", mappedBy="friendlyReport",cascade={"ALL"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $friendlyReportCategory;
    
    /**
     * @var \Hris\FormBundle\Entity\ArithmeticFilter $arithmeticFilter
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
     * @var \Hris\FormBundle\Entity\RelationalFilter $relationalFilter
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
     * @ORM\Column(name="datecreated", type="datetime")
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
        $this->friendlyReportCategory = new \Doctrine\Common\Collections\ArrayCollection();
        $this->arithmeticFilter = new \Doctrine\Common\Collections\ArrayCollection();
        $this->relationalFilter = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sort = 0;
        $this->uid = uniqid();
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
     * @param \Hris\FormBundle\Entity\FriendlyReportCategory $friendlyReportCategory
     * @return FriendlyReport
     */
    public function addFriendlyReportCategory(\Hris\FormBundle\Entity\FriendlyReportCategory $friendlyReportCategory)
    {
        $this->friendlyReportCategory[] = $friendlyReportCategory;
    
        return $this;
    }

    /**
     * Remove friendlyReportCategory
     *
     * @param \Hris\FormBundle\Entity\FriendlyReportCategory $friendlyReportCategory
     */
    public function removeFriendlyReportCategory(\Hris\FormBundle\Entity\FriendlyReportCategory $friendlyReportCategory)
    {
        $this->friendlyReportCategory->removeElement($friendlyReportCategory);
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
     * @param \Hris\FormBundle\Entity\ArithmeticFilter $arithmeticFilter
     * @return FriendlyReport
     */
    public function addArithmeticFilter(\Hris\FormBundle\Entity\ArithmeticFilter $arithmeticFilter)
    {
        $this->arithmeticFilter[] = $arithmeticFilter;
    
        return $this;
    }

    /**
     * Remove arithmeticFilter
     *
     * @param \Hris\FormBundle\Entity\ArithmeticFilter $arithmeticFilter
     */
    public function removeArithmeticFilter(\Hris\FormBundle\Entity\ArithmeticFilter $arithmeticFilter)
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
     * @param \Hris\FormBundle\Entity\RelationalFilter $relationalFilter
     * @return FriendlyReport
     */
    public function addRelationalFilter(\Hris\FormBundle\Entity\RelationalFilter $relationalFilter)
    {
        $this->relationalFilter[] = $relationalFilter;
    
        return $this;
    }

    /**
     * Remove relationalFilter
     *
     * @param \Hris\FormBundle\Entity\RelationalFilter $relationalFilter
     */
    public function removeRelationalFilter(\Hris\FormBundle\Entity\RelationalFilter $relationalFilter)
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
     * @param \Hris\FormBundle\Entity\FieldOptionGroup  $serie
     * @return FriendlyReport
     */
    public function setSerie(\Hris\FormBundle\Entity\FieldOptionGroup  $serie = null)
    {
        $this->serie = $serie;
    
        return $this;
    }

    /**
     * Get serie
     *
     * @return \Hris\FormBundle\Entity\FieldOptionGroup
     */
    public function getSerie()
    {
        return $this->serie;
    }
}