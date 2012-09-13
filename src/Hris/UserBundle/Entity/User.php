<?php

namespace Hris\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Hris\UserBundle\Entity\User
 *
 * @ORM\Table(name="hris_user")
 * @ORM\Entity(repositoryClass="Hris\UserBundle\Entity\UserRepository")
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
     * @var Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     *
     * @ORM\ManyToMany(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit", inversedBy="user")
     * @ORM\JoinTable(name="hris_user_orgunanisationunits",
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /*
     * @todo
     * 	- Organisation unit (many)
     * 	- Phone numbers
     * 	- Job title
     * 	- date added
     *  - last updated
     */
    
    public function __construct()
    {
    	parent::__construct();
    	$this->organisationunit = new ArrayCollection();
    }

    /**
     * Add organisationunit
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     * @return User
     */
    public function addOrganisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit)
    {
        $this->organisationunit[] = $organisationunit;
    
        return $this;
    }

    /**
     * Remove organisationunit
     *
     * @param Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     */
    public function removeOrganisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit)
    {
        $this->organisationunit->removeElement($organisationunit);
    }

    /**
     * Get organisationunit
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getOrganisationunit()
    {
        return $this->organisationunit;
    }
}