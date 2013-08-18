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
namespace Hris\IndicatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Hris\FormBundle\Entity\FieldOptionGroup;
use Hris\OrganisationunitBundle\Entity\OrganisationunitGroup;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Indicator
 *
 * @ORM\Table(name="hris_indicator")
 * @Gedmo\Loggable
 * @ORM\Entity(repositoryClass="Hris\IndicatorBundle\Entity\IndicatorRepository")
 */
class Indicator
{
    /**
     * @var integer
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
     * @var string
     *
     * @Assert\NotBlank()
     * @Gedmo\Versioned
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @var float
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="value", type="float")
     */
    private $value;

    /**
     * @var integer
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="year", type="integer")
     */
    private $year;

    /**
     * @var FieldOptionGroup $fieldOptionGroup
     *
     * @ORM\ManyToOne(targetEntity="\Hris\FormBundle\Entity\FieldOptionGroup",inversedBy="indicator")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fieldoptiongroup_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $fieldOptionGroup;

    /**
     * @var OrganisationunitGroup $organisationunitGroup
     *
     * @ORM\ManyToOne(targetEntity="\Hris\OrganisationunitBundle\Entity\OrganisationunitGroup",inversedBy="indicator")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organisationunit_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $organisationunitGroup;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return Indicator
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
     * Set name
     *
     * @param string $name
     * @return Indicator
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
     * Set value
     *
     * @param float $value
     * @return Indicator
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return float 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set year
     *
     * @param integer $year
     * @return Indicator
     */
    public function setYear($year)
    {
        $this->year = $year;
    
        return $this;
    }

    /**
     * Get year
     *
     * @return integer 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set fieldOptionGroup
     *
     * @param FieldOptionGroup $fieldOptionGroup
     * @return Indicator
     */
    public function setFieldOptionGroup(FieldOptionGroup $fieldOptionGroup = null)
    {
        $this->fieldOptionGroup = $fieldOptionGroup;

        return $this;
    }

    /**
     * Get fieldOptionGroup
     *
     * @return FieldOptionGroup
     */
    public function getFieldOptionGroup()
    {
        return $this->fieldOptionGroup;
    }




    /**
     * Set organisationunitGroup
     *
     * @param OrganisationunitGroup $organisationunitGroup
     * @return Indicator
     */
    public function setOrganisationunitGroup(OrganisationunitGroup $organisationunitGroup = null)
    {
        $this->organisationunitGroup = $organisationunitGroup;

        return $this;
    }

    /**
     * Get organisationunitGroup
     *
     * @return OrganisationunitGroup
     */
    public function getOrganisationunitGroup()
    {
        return $this->organisationunitGroup;
    }

    /**
     * Set lastupdated
     *
     * @param \DateTime $lastupdated
     * @return Indicator
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
     * @return Indicator
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
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->uid = uniqid();
    }
}
