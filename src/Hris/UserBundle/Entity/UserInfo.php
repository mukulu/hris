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
namespace Hris\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Hris\DashboardBundle\Entity\DashboardChart;
use Hris\UserBundle\Entity\User;

/**
 * Hris\UserBundle\Entity\UserInfo
 *
 * @ORM\Table(name="hris_userinfo")
 * @ORM\Entity(repositoryClass="Hris\UserBundle\Entity\UserInfoRepository")
 */
class UserInfo
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
     * @ORM\Column(name="uid", type="string", length=13, nullable=false, unique=true)
     */
    private $uid;
    
    /**
     * @var \Hris\UserBundle\Entity\User $user
     *
     * @ORM\OneToOne(targetEntity="Hris\UserBundle\Entity\User", mappedBy="userInfo")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false,unique=true)
     */
    private $user;

    /**
     * @var string $phonenumber
     *
     * @ORM\Column(name="phonenumber", type="string", length=64)
     */
    private $phonenumber;

    /**
     * @var string $jobTitle
     *
     * @ORM\Column(name="jobTitle", type="string", length=64)
     */
    private $jobTitle;

    /**
     * @var string $firstName
     *
     * @ORM\Column(name="firstName", type="string", length=64)
     */
    private $firstName;

    /**
     * @var string $middleName
     *
     * @ORM\Column(name="middleName", type="string", length=64)
     */
    private $middleName;

    /**
     * @var string $surname
     *
     * @ORM\Column(name="surname", type="string", length=64)
     */
    private $surname;
    
    /**
     * @var \Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     *
     * @ORM\ManyToMany(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit", inversedBy="userInfo")
     * @ORM\JoinTable(name="hris_userinfo_organisationunits",
     *   joinColumns={
     *     @ORM\JoinColumn(name="userinfo_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="organisationunit_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     * @ORM\OrderBy({"longname" = "ASC"})
     */
    private $organisationunit;
    
    /**
     * @var \Hris\DashboardBundle\Entity\DashboardChart $dashboardChart
     *
     * @ORM\OneToMany(targetEntity="Hris\DashboardBundle\Entity\DashboardChart", mappedBy="userInfo",cascade={"ALL"})
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $dashboardChart;


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
     * Set phonenumber
     *
     * @param string $phonenumber
     * @return UserInfo
     */
    public function setPhonenumber($phonenumber)
    {
        $this->phonenumber = $phonenumber;
    
        return $this;
    }

    /**
     * Get phonenumber
     *
     * @return string 
     */
    public function getPhonenumber()
    {
        return $this->phonenumber;
    }

    /**
     * Set jobTitle
     *
     * @param string $jobTitle
     * @return UserInfo
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;
    
        return $this;
    }

    /**
     * Get jobTitle
     *
     * @return string 
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return UserInfo
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     * @return UserInfo
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
    
        return $this;
    }

    /**
     * Get middleName
     *
     * @return string 
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return UserInfo
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    
        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->organisationunit = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dashboardChart = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set uid
     *
     * @param string $uid
     * @return UserInfo
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
     * Set user
     *
     * @param \Hris\UserBundle\Entity\User $user
     * @return UserInfo
     */
    public function setUser(\Hris\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Hris\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add organisationunit
     *
     * @param \Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     * @return UserInfo
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
     * Add dashboardChart
     *
     * @param \Hris\DashboardBundle\Entity\DashboardChart $dashboardChart
     * @return UserInfo
     */
    public function addDashboardChart(\Hris\DashboardBundle\Entity\DashboardChart $dashboardChart)
    {
        $this->dashboardChart[] = $dashboardChart;
    
        return $this;
    }

    /**
     * Remove dashboardChart
     *
     * @param \Hris\DashboardBundle\Entity\DashboardChart $dashboardChart
     */
    public function removeDashboardChart(\Hris\DashboardBundle\Entity\DashboardChart $dashboardChart)
    {
        $this->dashboardChart->removeElement($dashboardChart);
    }

    /**
     * Get dashboardChart
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDashboardChart()
    {
        return $this->dashboardChart;
    }
}