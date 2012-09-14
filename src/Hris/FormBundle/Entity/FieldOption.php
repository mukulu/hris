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

use Hris\FormBundle\Entity\FieldOptionGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * Hris\FormBundle\Entity\FieldOption
 *
 * @ORM\Table(name="hris_fieldoption",uniqueConstraints={@ORM\UniqueConstraint(name="unique_fieldoption_idx",columns={"value", "field_id"})})
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\FieldOptionRepository")
 */
class FieldOption
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
     * @ORM\Column(name="uid", type="string", length=11, nullable=false, unique=true)
     */
    private $uid;

    /**
     * @var string $value
     *
     * @ORM\Column(name="value", type="string", length=64, nullable=false)
     */
    private $value;
    
    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

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
     * @var Hris\FormBundle\Entity\FieldOption $parentFieldOption
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FieldOption", mappedBy="fieldOption")
     */
    private $parentFieldOption;
    
    /**
     * @var Hris\FormBundle\Entity\FieldOption $childFieldOption
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FieldOption", inversedBy="fieldOption")
     * @ORM\JoinTable(name="hris_fieldoption_children",
     *   joinColumns={
     *     @ORM\JoinColumn(name="parent_fieldoption", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="child_fieldoption", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $childFieldOption;
    
    /**
     * @var Hris\FormBundle\Entity\FieldOptionGroup $fieldOptionGroup
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FieldOptionGroup", mappedBy="fieldOption")
     */
    private $fieldOptionGroup;
    
    /**
     * @var Hris\FormBundle\Entity\Field $field
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\Field", inversedBy="fieldOption")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="field_id", referencedColumnName="id")
     * })
     */
    private $field;


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
     * Set value
     *
     * @param string $value
     * @return FieldOption
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return FieldOption
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
     * Constructor
     */
    public function __construct()
    {
        $this->parentFieldOption = new \Doctrine\Common\Collections\ArrayCollection();
        $this->childFieldOption = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fieldOptionGroup = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set uid
     *
     * @param string $uid
     * @return FieldOption
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
     * @return FieldOption
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
     * Add parentFieldOption
     *
     * @param Hris\FormBundle\Entity\FieldOption $parentFieldOption
     * @return FieldOption
     */
    public function addParentFieldOption(\Hris\FormBundle\Entity\FieldOption $parentFieldOption)
    {
        $this->parentFieldOption[] = $parentFieldOption;
    
        return $this;
    }

    /**
     * Remove parentFieldOption
     *
     * @param Hris\FormBundle\Entity\FieldOption $parentFieldOption
     */
    public function removeParentFieldOption(\Hris\FormBundle\Entity\FieldOption $parentFieldOption)
    {
        $this->parentFieldOption->removeElement($parentFieldOption);
    }

    /**
     * Get parentFieldOption
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getParentFieldOption()
    {
        return $this->parentFieldOption;
    }

    /**
     * Add childFieldOption
     *
     * @param Hris\FormBundle\Entity\FieldOption $childFieldOption
     * @return FieldOption
     */
    public function addChildFieldOption(\Hris\FormBundle\Entity\FieldOption $childFieldOption)
    {
        $this->childFieldOption[] = $childFieldOption;
    
        return $this;
    }

    /**
     * Remove childFieldOption
     *
     * @param Hris\FormBundle\Entity\FieldOption $childFieldOption
     */
    public function removeChildFieldOption(\Hris\FormBundle\Entity\FieldOption $childFieldOption)
    {
        $this->childFieldOption->removeElement($childFieldOption);
    }

    /**
     * Get childFieldOption
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getChildFieldOption()
    {
        return $this->childFieldOption;
    }

    /**
     * Add fieldOptionGroup
     *
     * @param Hris\FormBundle\Entity\FieldOptionGroup $fieldOptionGroup
     * @return FieldOption
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

    /**
     * Set field
     *
     * @param Hris\FormBundle\Entity\Field $field
     * @return FieldOption
     */
    public function setField(\Hris\FormBundle\Entity\Field $field = null)
    {
        $this->field = $field;
    
        return $this;
    }

    /**
     * Get field
     *
     * @return Hris\FormBundle\Entity\Field 
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set lastupdated
     *
     * @param \DateTime $lastupdated
     * @return FieldOption
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
}