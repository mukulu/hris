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
namespace Hris\DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Hris\FormBundle\Entity\Form;
use Hris\UserBundle\Entity\UserInfo;
use Hris\OrganisationunitBundle\Entity\Organisationunit;

/**
 * Hris\DashboardBundle\Entity\DashboardChart
 *
 * @ORM\Table(name="hris_dashboardchart", uniqueConstraints={@ORM\UniqueConstraint(name="userFieldOneTwoGraphTypeLowerLevel_idx",columns={"userinfo_id", "fieldOne","fieldTwo","graphType","lowerLevels"})})
 * @ORM\Entity(repositoryClass="Hris\DashboardBundle\Entity\DashboardChartRepository")
 */
class DashboardChart
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
     * @var string $fieldOne
     *
     * @ORM\Column(name="fieldOne", type="string", length=64)
     */
    private $fieldOne;

    /**
     * @var string $fieldTwo
     *
     * @ORM\Column(name="fieldTwo", type="string", length=64)
     */
    private $fieldTwo;

    /**
     * @var string $graphType
     *
     * @ORM\Column(name="graphType", type="string", length=64)
     */
    private $graphType;

    /**
     * @var boolean $lowerLevels
     *
     * @ORM\Column(name="lowerLevels", type="boolean")
     */
    private $lowerLevels;
    
    /**
     * @var boolean $systemWide
     *
     * @ORM\Column(name="systemWide", type="boolean")
     */
    private $systemWide;
    
    /**
     * @var \Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     *
     * @ORM\ManyToMany(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit", inversedBy="dashboardChart")
     * @ORM\JoinTable(name="hris_dashboardchart_organisationunitmembers",
     *   joinColumns={
     *     @ORM\JoinColumn(name="dashboardchart_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="organisationunit_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     * @ORM\OrderBy({"longname" = "ASC"})
     */
    private $organisationunit;
    
    /**
     * @var \Hris\UserBundle\Entity\UserInfo $userInfo
     *
     * @ORM\ManyToOne(targetEntity="Hris\UserBundle\Entity\UserInfo",inversedBy="dashboardChart")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userinfo_id", referencedColumnName="id")
     * })
     */
    private $userInfo;
    
    /**
     * @var \Hris\FormBundle\Entity\Form $form
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\Form", inversedBy="dashboardChart")
     * @ORM\JoinTable(name="hris_dashboardchart_formmembers",
     *   joinColumns={
     *     @ORM\JoinColumn(name="dashboardchart_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="form_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $form;
    
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
     * @return DashboardChart
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
     * @return DashboardChart
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
     * Set fieldOne
     *
     * @param string $fieldOne
     * @return DashboardChart
     */
    public function setFieldOne($fieldOne)
    {
        $this->fieldOne = $fieldOne;
    
        return $this;
    }

    /**
     * Get fieldOne
     *
     * @return string 
     */
    public function getFieldOne()
    {
        return $this->fieldOne;
    }

    /**
     * Set fieldTwo
     *
     * @param string $fieldTwo
     * @return DashboardChart
     */
    public function setFieldTwo($fieldTwo)
    {
        $this->fieldTwo = $fieldTwo;
    
        return $this;
    }

    /**
     * Get fieldTwo
     *
     * @return string 
     */
    public function getFieldTwo()
    {
        return $this->fieldTwo;
    }

    /**
     * Set graphType
     *
     * @param string $graphType
     * @return DashboardChart
     */
    public function setGraphType($graphType)
    {
        $this->graphType = $graphType;
    
        return $this;
    }

    /**
     * Get graphType
     *
     * @return string 
     */
    public function getGraphType()
    {
        return $this->graphType;
    }
    
    /**
     * Set uid
     *
     * @param string $uid
     * @return DashboardChart
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
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return DashboardChart
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
     * @return DashboardChart
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
     * Add form
     *
     * @param \Hris\FormBundle\Entity\Form $form
     * @return DashboardChart
     */
    public function addForm(\Hris\FormBundle\Entity\Form $form)
    {
        $this->form[] = $form;
    
        return $this;
    }

    /**
     * Remove form
     *
     * @param \Hris\FormBundle\Entity\Form $form
     */
    public function removeForm(\Hris\FormBundle\Entity\Form $form)
    {
        $this->form->removeElement($form);
    }

    /**
     * Get form
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set lowerLevels
     *
     * @param boolean $lowerLevels
     * @return DashboardChart
     */
    public function setLowerLevels($lowerLevels)
    {
        $this->lowerLevels = $lowerLevels;
    
        return $this;
    }

    /**
     * Get lowerLevels
     *
     * @return boolean 
     */
    public function getLowerLevels()
    {
        return $this->lowerLevels;
    }

    /**
     * Add organisationunit
     *
     * @param \Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     * @return DashboardChart
     */
    public function addOrganisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit)
    {
        $this->organisationunit[] = $organisationunit;
    
        return $this;
    }

    /**
     * Remove organisationunit
     *
     * @param \Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     */
    public function removeOrganisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit)
    {
        $this->organisationunit->removeElement($organisationunit);
    }

    /**
     * Get organisationunit
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganisationunit()
    {
        return $this->organisationunit;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->organisationunit = new \Doctrine\Common\Collections\ArrayCollection();
        $this->form = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lowerLevels = True;
        $this->uid = uniqid();
    }
    

    /**
     * Set systemWide
     *
     * @param boolean $systemWide
     * @return DashboardChart
     */
    public function setSystemWide($systemWide)
    {
        $this->systemWide = $systemWide;
    
        return $this;
    }

    /**
     * Get systemWide
     *
     * @return boolean 
     */
    public function getSystemWide()
    {
        return $this->systemWide;
    }

    /**
     * Set userInfo
     *
     * @param \Hris\UserBundle\Entity\UserInfo $userInfo
     * @return DashboardChart
     */
    public function setUserInfo(\Hris\UserBundle\Entity\UserInfo $userInfo = null)
    {
        $this->userInfo = $userInfo;
    
        return $this;
    }

    /**
     * Get userInfo
     *
     * @return \Hris\UserBundle\Entity\UserInfo
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }
}