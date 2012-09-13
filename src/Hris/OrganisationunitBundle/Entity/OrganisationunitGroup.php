<?php

namespace Hris\OrganisationunitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hris\OrganisationunitBundle\Entity\OrganisationunitGroup
 *
 * @ORM\Table(name="hris_organiationunitgroup")
 * @ORM\Entity(repositoryClass="Hris\OrganisationunitBundle\Entity\OrganisationunitGroupRepository")
 */
class OrganisationunitGroup
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
     * @var string $uid
     *
     * @ORM\Column(name="uid", type="string", length=11)
     */
    private $uid;

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=50)
     */
    private $code;

    /**
     * @var \DateTime $lastupdated
     *
     * @ORM\Column(name="lastupdated", type="datetime")
     */
    private $lastupdated;

    /**
     * @var \DateTime $datecreated
     *
     * @ORM\Column(name="datecreated", type="datetime")
     */
    private $datecreated;


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
     * @return OrganisationunitGroup
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
     * Set uid
     *
     * @param string $uid
     * @return OrganisationunitGroup
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
     * Set code
     *
     * @param string $code
     * @return OrganisationunitGroup
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
     * Set lastupdated
     *
     * @param \DateTime $lastupdated
     * @return OrganisationunitGroup
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
     * @return OrganisationunitGroup
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
}