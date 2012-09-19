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
namespace Hris\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Hris\FormBundle\Entity\FieldOptionGroup;

/**
 * Hris\FormBundle\Entity\FieldOptionGroupset
 *
 * @ORM\Table(name="hris_fieldoptiongroupset")
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\FieldOptionGroupsetRepository")
 */
class FieldOptionGroupset
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
     * @var Hris\FormBundle\Entity\FieldOptionGroup $fieldOptionGroup 
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FieldOptionGroup", inversedBy="fieldOptionGroupset")
     * @ORM\JoinTable(name="hris_fieldoptiongroupset_members",
     *   joinColumns={
     *     @ORM\JoinColumn(name="fieldoptiongroupset_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="fieldoptiongroup_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $fieldOptionGroup;
    
    /**
     * @var \DateTime $datecreated
     *
     * @ORM\Column(name="datecreated", type="datetime")
     */
    private $datecreated;
    
    /**
     * @var \DateTime $lastmodified
     *
     * @ORM\Column(name="lastmodified", type="datetime", nullable=true)
     */
    private $lastmodified;

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
     * @return FieldOptionGroupset
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
     * @return FieldOptionGroupset
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
     * Constructor
     */
    public function __construct()
    {
        $this->fieldOptionGroup = new \Doctrine\Common\Collections\ArrayCollection();
        $this->uid = uniqid();
    }
    
    /**
     * Set description
     *
     * @param string $description
     * @return FieldOptionGroupset
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
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return FieldOptionGroupset
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
     * Set lastmodified
     *
     * @param \DateTime $lastmodified
     * @return FieldOptionGroupset
     */
    public function setLastmodified($lastmodified)
    {
        $this->lastmodified = $lastmodified;
    
        return $this;
    }

    /**
     * Get lastmodified
     *
     * @return \DateTime 
     */
    public function getLastmodified()
    {
        return $this->lastmodified;
    }

    /**
     * Add fieldOptionGroup
     *
     * @param Hris\FormBundle\Entity\FieldOptionGroup $fieldOptionGroup
     * @return FieldOptionGroupset
     */
    public function addFieldOptionGroup(\Hris\FormBundle\Entity\FieldOptionGroup $fieldOptionGroup)
    {
        $this->fieldOptionGroup[] = $fieldOptionGroup;
    
        return $this;
    }

    /**
     * Remove fieldOptionGroup
     *
     * @param Hris\FormBundle\Entity\FieldOptionGroup $fieldOptionGroup
     */
    public function removeFieldOptionGroup(\Hris\FormBundle\Entity\FieldOptionGroup $fieldOptionGroup)
    {
        $this->fieldOptionGroup->removeElement($fieldOptionGroup);
    }

    /**
     * Get fieldOptionGroup
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFieldOptionGroup()
    {
        return $this->fieldOptionGroup;
    }
}