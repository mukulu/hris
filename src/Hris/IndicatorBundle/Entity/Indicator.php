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

use Hris\FormBundle\Entity\FieldOptionGroup;
use Hris\OrganisationunitBundle\Entity\OrganisationunitGroup;

/**
 * Indicator
 *
 * @ORM\Table(name="hris_indicator")
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float")
     */
    private $value;

    /**
     * @var integer
     *
     * @ORM\Column(name="year", type="integer")
     */
    private $year;

    /**
     * @var \Hris\FormBundle\Entity\FieldOptionGroup $fieldOptionGroup
     *
     * @ORM\ManyToOne(targetEntity="\Hris\FormBundle\Entity\FieldOptionGroup",inversedBy="indicator")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fieldoptiongroup_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $fieldOptionGroup;

    /**
     * @var \Hris\OrganisationunitBundle\Entity\OrganisationunitGroup $organisationunitGroup
     *
     * @ORM\ManyToMany(targetEntity="Hris\OrganisationunitBundle\Entity\OrganisationunitGroup", mappedBy="organisationunit")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $organisationunitGroup;

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
     * @param \Hris\FormBundle\Entity\FieldOptionGroup $fieldOptionGroup
     * @return Indicator
     */
    public function setFieldOptionGroup(\Hris\FormBundle\Entity\FieldOptionGroup $fieldOptionGroup = null)
    {
        $this->fieldOptionGroup = $fieldOptionGroup;

        return $this;
    }

    /**
     * Get fieldOptionGroup
     *
     * @return \Hris\FormBundle\Entity\FieldOptionGroup
     */
    public function getFieldOptionGroup()
    {
        return $this->fieldOptionGroup;
    }

    /**
     * Add organisationunitGroup
     *
     * @param \Hris\OrganisationunitBundle\Entity\OrganisationunitGroup $organisationunitGroup
     * @return Indicator
     */
    public function addOrganisationunitGroup(\Hris\OrganisationunitBundle\Entity\OrganisationunitGroup $organisationunitGroup)
    {
        $this->organisationunitGroup[$organisationunitGroup->getId()] = $organisationunitGroup;

        return $this;
    }

    /**
     * Remove organisationunitGroup
     *
     * @param \Hris\OrganisationunitBundle\Entity\OrganisationunitGroup $organisationunitGroup
     */
    public function removeOrganisationunitGroup(\Hris\OrganisationunitBundle\Entity\OrganisationunitGroup $organisationunitGroup)
    {
        $this->organisationunitGroup->removeElement($organisationunitGroup);
    }

    /**
     * Get organisationunitGroup
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganisationunitGroup()
    {
        return $this->organisationunitGroup;
    }
}
