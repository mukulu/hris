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

use Gedmo\Mapping\Annotation as Gedmo;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Hris\UserBundle\Entity\UserInfo;

/**
 * Hris\UserBundle\Entity\User
 *
 * @ORM\Table(name="hris_user")
 * @ORM\Entity(repositoryClass="Hris\UserBundle\Entity\UserRepository")
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class User extends BaseUser
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var \Hris\UserBundle\Entity\UserInfo $userInfo
     *
     * @ORM\OneToOne(targetEntity="Hris\UserBundle\Entity\UserInfo", inversedBy="user")
     */
    private $userInfo;

    /**
     * @var \Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     *
     * @ORM\ManyToMany(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit", inversedBy="user")
     * @ORM\JoinTable(name="hris_user_organisationunits",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="organisationunit_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     * @ORM\OrderBy({"longname" = "ASC"})
     */
    private $organisationunit;

    /**
     * @var \DateTime $datecreated
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="datecreated", type="datetime")
     */
    private $datecreated;
    
    /**
     * @var \DateTime $lastupdated
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="lastupdated", type="datetime", nullable=true)
     */
    private $lastupdated;
    
    /**
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        if(empty($this->datecreated))
        {
            $this->datecreated = new \DateTime('now');
        }
        $this->organisationunit = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add organisationunit
     *
     * @param \Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     * @return User
     */
    public function addOrganisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit)
    {
        $this->organisationunit[$organisationunit->getId()] = $organisationunit;

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
     * Set lastupdated
     *
     * @param \DateTime $lastupdated
     * @return User
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
     * @return User
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
     * Set userInfo
     *
     * @param \Hris\UserBundle\Entity\UserInfo $userInfo
     * @return User
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
    
    /**
     * Get deletedAt
     *
     * @return \DateTime $deletedAt
     */
    public function getDeletedAt()
    {
    	return $this->deletedAt;
    }
    
    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
    	$this->deletedAt = $deletedAt;
    }

    /**
     * Get expiresAt
     *
     * @return \DateTime $expiresAt
     */
    public function getExpiresAt()
    {
    	return $this->expiresAt;
    }

    /**
     * Get credentialsExpireAt
     *
     * @return \DateTime $credentialsExpireAt
     */
    public function getCredentialsExpireAt()
    {
    	return $this->credentialsExpireAt;
    }
}