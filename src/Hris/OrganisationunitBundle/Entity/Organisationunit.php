<?php

namespace Hris\OrganisationunitBundle\Entity;

use Hris\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Hris\OrganisationunitBundle\Entity\Organisationunit
 *
 * @ORM\Table(name="hris_organiationunit")
 * @ORM\Entity(repositoryClass="Hris\OrganisationunitBundle\Entity\OrganisationunitRepository")
 */
class Organisationunit
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
     * @var Hris\UserBundle\Entity\User $user
     * 
     * @ORM\ManyToMany(targetEntity="Hris\UserBundle\Entity\User", mappedBy="organisationunit")
     */
    private $user;

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=25)
     */
    private $code;

    /**
     * @var string $uid
     *
     * @ORM\Column(name="uid", type="string", length=11)
     */
    private $uid;

    /**
     * @var string $shortname
     *
     * @ORM\Column(name="shortname", type="string", length=20)
     */
    private $shortname;

    /**
     * @var string $longname
     *
     * @ORM\Column(name="longname", type="string", length=64)
     */
    private $longname;

    /**
     * @var boolean $active
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var \DateTime $openingdate
     *
     * @ORM\Column(name="openingdate", type="date")
     */
    private $openingdate;

    /**
     * @var \DateTime $closingdate
     *
     * @ORM\Column(name="closingdate", type="date")
     */
    private $closingdate;

    /**
     * @var string $geocode
     *
     * @ORM\Column(name="geocode", type="string", length=255)
     */
    private $geocode;

    /**
     * @var string $coordinates
     *
     * @ORM\Column(name="coordinates", type="text")
     */
    private $coordinates;

    /**
     * @var string $featuretype
     *
     * @ORM\Column(name="featuretype", type="string", length=20)
     */
    private $featuretype;

    /**
     * @var \DateTime $lastupdated
     *
     * @ORM\Column(name="lastupdated", type="datetime")
     */
    private $lastupdated;

    /**
     * @var string $address
     *
     * @ORM\Column(name="address", type="text")
     */
    private $address;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=150)
     */
    private $email;

    /**
     * @var string $phonenumber
     *
     * @ORM\Column(name="phonenumber", type="string", length=150)
     */
    private $phonenumber;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;


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
     * Set code
     *
     * @param string $code
     * @return Organisationunit
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
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
     * Set shortname
     *
     * @param string $shortname
     * @return Organisationunit
     */
    public function setShortname($shortname)
    {
        $this->shortname = $shortname;
    
        return $this;
    }

    /**
     * Get shortname
     *
     * @return string 
     */
    public function getShortname()
    {
        return $this->shortname;
    }

    /**
     * Set longname
     *
     * @param string $longname
     * @return Organisationunit
     */
    public function setLongname($longname)
    {
        $this->longname = $longname;
    
        return $this;
    }

    /**
     * Get longname
     *
     * @return string 
     */
    public function getLongname()
    {
        return $this->longname;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Organisationunit
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set openingdate
     *
     * @param \DateTime $openingdate
     * @return Organisationunit
     */
    public function setOpeningdate($openingdate)
    {
        $this->openingdate = $openingdate;
    
        return $this;
    }

    /**
     * Get openingdate
     *
     * @return \DateTime 
     */
    public function getOpeningdate()
    {
        return $this->openingdate;
    }

    /**
     * Set closingdate
     *
     * @param \DateTime $closingdate
     * @return Organisationunit
     */
    public function setClosingdate($closingdate)
    {
        $this->closingdate = $closingdate;
    
        return $this;
    }

    /**
     * Get closingdate
     *
     * @return \DateTime 
     */
    public function getClosingdate()
    {
        return $this->closingdate;
    }

    /**
     * Set geocode
     *
     * @param string $geocode
     * @return Organisationunit
     */
    public function setGeocode($geocode)
    {
        $this->geocode = $geocode;
    
        return $this;
    }

    /**
     * Get geocode
     *
     * @return string 
     */
    public function getGeocode()
    {
        return $this->geocode;
    }

    /**
     * Set coordinates
     *
     * @param string $coordinates
     * @return Organisationunit
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;
    
        return $this;
    }

    /**
     * Get coordinates
     *
     * @return string 
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * Set featuretype
     *
     * @param string $featuretype
     * @return Organisationunit
     */
    public function setFeaturetype($featuretype)
    {
        $this->featuretype = $featuretype;
    
        return $this;
    }

    /**
     * Get featuretype
     *
     * @return string 
     */
    public function getFeaturetype()
    {
        return $this->featuretype;
    }

    /**
     * Set lastupdated
     *
     * @param \DateTime $lastupdated
     * @return Organisationunit
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
     * Set address
     *
     * @param string $address
     * @return Organisationunit
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Organisationunit
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phonenumber
     *
     * @param string $phonenumber
     * @return Organisationunit
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
     * Set description
     *
     * @param string $description
     * @return Organisationunit
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
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add user
     *
     * @param Hris\UserBundle\Entity\User $user
     * @return Organisationunit
     */
    public function addUser(\Hris\UserBundle\Entity\User $user)
    {
        $this->user[] = $user;
    
        return $this;
    }

    /**
     * Remove user
     *
     * @param Hris\UserBundle\Entity\User $user
     */
    public function removeUser(\Hris\UserBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUser()
    {
        return $this->user;
    }
}