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
namespace Hris\DashboardBundle\Entity;

use Hris\FormBundle\Entity\Form;
use Hris\UserBundle\Entity\User;
use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Doctrine\ORM\Mapping as ORM;

/**
 * Hris\DashboardBundle\Entity\DashboardChart
 *
 * @ORM\Table(name="hris_dashboardchart")
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text")
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
     * @var string $lowerLevels
     *
     * @ORM\Column(name="lowerLevels", type="string", length=64)
     */
    private $lowerLevels;
    
    /**
     * @var string $uid
     *
     * @ORM\Column(name="uid", type="string", length=11, nullable=false, unique=true)
     */
    private $uid;
    
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
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     *
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organisationunit_id", referencedColumnName="id")
     * })
     */
    private $organisationunit;
    
    /**
     * @var Hris\UserBundle\Entity\User $user
     *
     * @ORM\ManyToOne(targetEntity="Hris\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;
    
    /**
     * @var Hris\FormBundle\Entity\Form $form
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\Form", inversedBy="dashboardChart")
     * @ORM\JoinTable(name="hris_dashboardchartform_members",
     *   joinColumns={
     *     @ORM\JoinColumn(name="dashboardchart_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="form_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $form;


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
     * Set lowerLevels
     *
     * @param string $lowerLevels
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
     * @return string 
     */
    public function getLowerLevels()
    {
        return $this->lowerLevels;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->form = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set organisationunit
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     * @return DashboardChart
     */
    public function setOrganisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit = null)
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
     * Set user
     *
     * @param Hris\UserBundle\Entity\User $user
     * @return DashboardChart
     */
    public function setUser(\Hris\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return Hris\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add form
     *
     * @param Hris\FormBundle\Entity\Form $form
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
     * @param Hris\FormBundle\Entity\Form $form
     */
    public function removeForm(\Hris\FormBundle\Entity\Form $form)
    {
        $this->form->removeElement($form);
    }

    /**
     * Get form
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getForm()
    {
        return $this->form;
    }
}