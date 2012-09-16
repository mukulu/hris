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

use Hris\FormBundle\Entity\FieldGroup;
use Hris\FormBundle\Entity\DataType;
use Hris\FormBundle\Entity\InputType;
use Doctrine\ORM\Mapping as ORM;

/**
 * Hris\FormBundle\Entity\Field
 *
 * @ORM\Table(name="hris_field")
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\FieldRepository")
 */
class Field
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=64, unique=true)
     */
    private $name;

    /**
     * @var string $caption
     *
     * @ORM\Column(name="caption", type="string", length=64)
     */
    private $caption;

    /**
     * @var boolean $compulsory
     *
     * @ORM\Column(name="compulsory", type="boolean")
     */
    private $compulsory;

    /**
     * @var boolean $uniqueid
     *
     * @ORM\Column(name="uniqueid", type="boolean")
     */
    private $uniqueid;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var boolean $hashistory
     *
     * @ORM\Column(name="hashistory", type="boolean")
     */
    private $hashistory;

    /**
     * @var boolean $fieldrelation
     *
     * @ORM\Column(name="fieldrelation", type="boolean")
     */
    private $fieldrelation;
    
    /**
     * @var \DateTime $datecreated
     *
     * @ORM\Column(name="datecreated", type="datetime", nullable=false)
     */
    private $datecreated;
    
    /**
     * @var \DateTime $lastupdated
     *
     * @ORM\Column(name="lastupdated", type="datetime", nullable=true)
     */
    private $lastupdated;
    
    /**
     * @var Hris\FormBundle\Entity\FieldGroup $fieldGroup
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FieldGroup", mappedBy="field")
     */
    private $fieldGroup;
    
    /**
     * @var Hris\FormBundle\Entity\Field $parentField
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\Field", mappedBy="field")
     */
    private $parentField;
    
    /**
     * @var Hris\FormBundle\Entity\Field $childField
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\Field", inversedBy="Field")
     * @ORM\JoinTable(name="hris_field_relation",
     *   joinColumns={
     *     @ORM\JoinColumn(name="parent_field", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="child_field", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $childField;
    
    /**
     * @var Hris\FormBundle\Entity\DataType $dataType
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\DataType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="datatype_id", referencedColumnName="id")
     * })
     */
    private $dataType;
    
    /**
     * @var Hris\FormBundle\Entity\InputType $inputType
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\InputType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="inputtype_id", referencedColumnName="id")
     * })
     */
    private $inputType;
    
    /**
     *	@var Hris\FormBundle\Entity\FieldOption $fieldOption
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\FieldOption", mappedBy="field")
     * @ORM\OrderBy({"value" = "ASC"})
     */
    private $fieldOption;


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
     * @return Field
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
     * Set caption
     *
     * @param string $caption
     * @return Field
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    
        return $this;
    }

    /**
     * Get caption
     *
     * @return string 
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set compulsory
     *
     * @param boolean $compulsory
     * @return Field
     */
    public function setCompulsory($compulsory)
    {
        $this->compulsory = $compulsory;
    
        return $this;
    }

    /**
     * Get compulsory
     *
     * @return boolean 
     */
    public function getCompulsory()
    {
        return $this->compulsory;
    }

    /**
     * Set uniqueid
     *
     * @param boolean $uniqueid
     * @return Field
     */
    public function setUniqueid($uniqueid)
    {
        $this->uniqueid = $uniqueid;
    
        return $this;
    }

    /**
     * Get uniqueid
     *
     * @return boolean 
     */
    public function getUniqueid()
    {
        return $this->uniqueid;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Field
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
     * Set hashistory
     *
     * @param boolean $hashistory
     * @return Field
     */
    public function setHashistory($hashistory)
    {
        $this->hashistory = $hashistory;
    
        return $this;
    }

    /**
     * Get hashistory
     *
     * @return boolean 
     */
    public function getHashistory()
    {
        return $this->hashistory;
    }

    /**
     * Set fieldrelation
     *
     * @param boolean $fieldrelation
     * @return Field
     */
    public function setFieldrelation($fieldrelation)
    {
        $this->fieldrelation = $fieldrelation;
    
        return $this;
    }

    /**
     * Get fieldrelation
     *
     * @return boolean 
     */
    public function getFieldrelation()
    {
        return $this->fieldrelation;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fieldGroup = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parentField = new \Doctrine\Common\Collections\ArrayCollection();
        $this->childField = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fieldOption = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add fieldGroup
     *
     * @param Hris\FormBundle\Entity\FieldGroup $fieldGroup
     * @return Field
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

    /**
     * Add parentField
     *
     * @param Hris\FormBundle\Entity\Field $parentField
     * @return Field
     */
    public function addParentField(\Hris\FormBundle\Entity\Field $parentField)
    {
        $this->parentField[] = $parentField;
    
        return $this;
    }

    /**
     * Remove parentField
     *
     * @param Hris\FormBundle\Entity\Field $parentField
     */
    public function removeParentField(\Hris\FormBundle\Entity\Field $parentField)
    {
        $this->parentField->removeElement($parentField);
    }

    /**
     * Get parentField
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getParentField()
    {
        return $this->parentField;
    }

    /**
     * Add childField
     *
     * @param Hris\FormBundle\Entity\Field $childField
     * @return Field
     */
    public function addChildField(\Hris\FormBundle\Entity\Field $childField)
    {
        $this->childField[] = $childField;
    
        return $this;
    }

    /**
     * Remove childField
     *
     * @param Hris\FormBundle\Entity\Field $childField
     */
    public function removeChildField(\Hris\FormBundle\Entity\Field $childField)
    {
        $this->childField->removeElement($childField);
    }

    /**
     * Get childField
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getChildField()
    {
        return $this->childField;
    }

    /**
     * Set dataType
     *
     * @param Hris\FormBundle\Entity\DataType $dataType
     * @return Field
     */
    public function setDataType(\Hris\FormBundle\Entity\DataType $dataType = null)
    {
        $this->dataType = $dataType;
    
        return $this;
    }

    /**
     * Get dataType
     *
     * @return Hris\FormBundle\Entity\DataType 
     */
    public function getDataType()
    {
        return $this->dataType;
    }

    /**
     * Set inputType
     *
     * @param Hris\FormBundle\Entity\InputType $inputType
     * @return Field
     */
    public function setInputType(\Hris\FormBundle\Entity\InputType $inputType = null)
    {
        $this->inputType = $inputType;
    
        return $this;
    }

    /**
     * Get inputType
     *
     * @return Hris\FormBundle\Entity\InputType 
     */
    public function getInputType()
    {
        return $this->inputType;
    }

    /**
     * Add fieldOption
     *
     * @param Hris\FormBundle\Entity\FieldOption $fieldOption
     * @return Field
     */
    public function addFieldOption(\Hris\FormBundle\Entity\FieldOption $fieldOption)
    {
        $this->fieldOption[] = $fieldOption;
    
        return $this;
    }

    /**
     * Remove fieldOption
     *
     * @param Hris\FormBundle\Entity\FieldOption $fieldOption
     */
    public function removeFieldOption(\Hris\FormBundle\Entity\FieldOption $fieldOption)
    {
        $this->fieldOption->removeElement($fieldOption);
    }

    /**
     * Get fieldOption
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFieldOption()
    {
        return $this->fieldOption;
    }

    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return Field
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
     * @return Field
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
     * Set uid
     *
     * @param string $uid
     * @return Field
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
}