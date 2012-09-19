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

use Hris\FormBundle\Entity\FieldGroup;

/**
 * Hris\FormBundle\Entity\FieldGroupset
 *
 * @ORM\Table(name="hris_fieldgroupset")
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\FieldGroupsetRepository")
 */
class FieldGroupset
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
     * @var Hris\FormBundle\Entity\FieldGroup $fieldGroup
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FieldGroup", inversedBy="fieldGroupset")
     * @ORM\JoinTable(name="hris_fieldgroupset_members",
     *   joinColumns={
     *     @ORM\JoinColumn(name="fieldgroupset_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="fieldgroup_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $fieldGroup;

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
     * @return FieldGroupset
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
     * @return FieldGroupset
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
     * Set description
     *
     * @param string $description
     * @return FieldGroupset
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
     * @return FieldGroupset
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
     * @return FieldGroupset
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
     * Constructor
     */
    public function __construct()
    {
        $this->fieldGroup = new \Doctrine\Common\Collections\ArrayCollection();
        $this->uid = uniqid();
    }
    
    /**
     * Add fieldGroup
     *
     * @param Hris\FormBundle\Entity\FieldGroup $fieldGroup
     * @return FieldGroupset
     */
    public function addFieldGroup(\Hris\FormBundle\Entity\FieldGroup $fieldGroup)
    {
        $this->fieldGroup[] = $fieldGroup;
    
        return $this;
    }

    /**
     * Remove fieldGroup
     *
     * @param Hris\FormBundle\Entity\FieldGroup $fieldGroup
     */
    public function removeFieldGroup(\Hris\FormBundle\Entity\FieldGroup $fieldGroup)
    {
        $this->fieldGroup->removeElement($fieldGroup);
    }

    /**
     * Get fieldGroup
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFieldGroup()
    {
        return $this->fieldGroup;
    }
}