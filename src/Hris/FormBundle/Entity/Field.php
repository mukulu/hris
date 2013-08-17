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
namespace Hris\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Hris\FormBundle\Entity\ResourceTableFieldMember;
use Hris\FormBundle\Entity\FormSectionFieldMember;
use Hris\FormBundle\Entity\Form;
use Hris\FormBundle\Entity\FormFieldMember;
use Hris\FormBundle\Entity\FieldOptionMerge;
use Hris\FormBundle\Entity\FormVisibleFields;
use Hris\FormBundle\Entity\FieldGroup;
use Hris\FormBundle\Entity\DataType;
use Hris\FormBundle\Entity\InputType;
use Hris\RecordsBundle\Entity\RecordValue;
use Hris\RecordsBundle\Entity\RecordStats;
use \DateTime;

/**
 * Hris\FormBundle\Entity\Field
 *
 * @Gedmo\Loggable
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
     * @ORM\Column(name="uid", type="string", length=13, unique=true)
     */
    private $uid;

    /**
     * @var string $name
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="name", type="string", length=64, unique=true)
     */
    private $name;

    /**
     * @var string $caption
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="caption", type="string", length=64)
     */
    private $caption;

    /**
     * @var boolean $compulsory
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="compulsory", type="boolean")
     */
    private $compulsory;

    /**
     * @var boolean isUnique
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="isUnique", type="boolean")
     */
    private $isUnique;

    /**
     * @var string $description
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var boolean $hashistory
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="hashistory", type="boolean")
     */
    private $hashistory;

    /**
     * @var boolean $fieldrelation
     *
     * @ORM\Column(name="fieldrelation", type="boolean", nullable=true)
     */
    private $fieldrelation;
    
    /**
     * @var \Hris\FormBundle\Entity\FieldGroup $fieldGroup
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FieldGroup", mappedBy="field")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $fieldGroup;
    
    /**
     * @var \Hris\FormBundle\Entity\Field $parentField
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\Field", mappedBy="childField")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $parentField;
    
    /**
     * @var \Hris\FormBundle\Entity\Field $childField
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\Field", inversedBy="parentField")
     * @ORM\JoinTable(name="hris_field_relation",
     *   joinColumns={
     *     @ORM\JoinColumn(name="parent_field", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="child_field", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $childField;
    
    /**
     * @var \Hris\FormBundle\Entity\DataType $dataType
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\DataType",inversedBy="field")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="datatype_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $dataType;
    
    /**
     * @var \Hris\FormBundle\Entity\InputType $inputType
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\InputType",inversedBy="field")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="inputtype_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $inputType;
    
    /**
     *	@var \Hris\FormBundle\Entity\FieldOption $fieldOption
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\FieldOption", mappedBy="field",cascade={"ALL"})
     * @ORM\OrderBy({"value" = "ASC"})
     */
    private $fieldOption;
    
    /**
     *	@var \Hris\RecordsBundle\Entity\RecordValue $recordValue
     *
     * @ORM\OneToMany(targetEntity="Hris\RecordsBundle\Entity\RecordValue", mappedBy="field",cascade={"ALL"})
     * @ORM\OrderBy({"value" = "ASC"})
     */
    private $recordValue;
    
    /**
     *	@var \Hris\RecordsBundle\Entity\RecordStats $recordStats
     *
     * @ORM\OneToMany(targetEntity="Hris\RecordsBundle\Entity\RecordStats", mappedBy="field",cascade={"ALL"})
     * @ORM\OrderBy({"count" = "ASC"})
     */
    private $recordStats;
    
    /**
     * @var \Hris\FormBundle\Entity\FormVisibleFields $formVisibleFields
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\FormVisibleFields", mappedBy="field",cascade={"ALL"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $formVisibleFields;
    
    /**
     * @var \Hris\FormBundle\Entity\FieldOptionMerge $fieldOptionMerge
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\FieldOptionMerge", mappedBy="removedOptionField",cascade={"ALL"})
     * @ORM\OrderBy({"removedoptionvalue" = "ASC"})
     */
    private $fieldOptionMerge;
    
    /**
     * @var \Hris\FormBundle\Entity\FormFieldMember $formFieldMember
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\FormFieldMember", mappedBy="field", cascade={"ALL"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $formFieldMember;
    
    /**
     * @var \Hris\FormBundle\Entity\FormSectionFieldMember $formSectionFieldMember
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\FormSectionFieldMember", mappedBy="field",cascade={"ALL"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $formSectionFieldMember;
    
    /**
     * @var \Hris\FormBundle\Entity\Form $uniqueRecordForms
     * Forms in which this field together(or not) with others makes a record unique in the form
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\Form", mappedBy="uniqueRecordFields")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $uniqueRecordForms;
    
    /**
     * @var \Hris\FormBundle\Entity\ResourceTableFieldMember $resourceTableFieldMember
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\ResourceTableFieldMember", mappedBy="field",cascade={"ALL"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $resourceTableFieldMember;
    
    /**
     * @var \DateTime $datecreated
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="datecreated", type="datetime")
     */
    private $datecreated;
    
    /**
     * @var \DateTime $lastupdated
     *
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
     * Set isUnique
     *
     * @param boolean $isUnique
     * @return Field
     */
    public function setIsUnique($isUnique)
    {
        $this->$isUnique = $isUnique;
    
        return $this;
    }

    /**
     * Get isUnique
     *
     * @return boolean 
     */
    public function getIsUnique()
    {
        return $this->isUnique;
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
     * Add fieldGroup
     *
     * @param \Hris\FormBundle\Entity\FieldGroup $fieldGroup
     * @return Field
     */
    public function addFieldGroup(\Hris\FormBundle\Entity\FieldGroup $fieldGroup)
    {
        $this->fieldGroup[$fieldGroup->getId()] = $fieldGroup;
    
        return $this;
    }

    /**
     * Remove fieldGroup
     *
     * @param \Hris\FormBundle\Entity\FieldGroup $fieldGroup
     */
    public function removeFieldGroup(\Hris\FormBundle\Entity\FieldGroup $fieldGroup)
    {
        $this->fieldGroup->removeElement($fieldGroup);
    }

    /**
     * Get fieldGroup
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFieldGroup()
    {
        return $this->fieldGroup;
    }

    /**
     * Add parentField
     *
     * @param \Hris\FormBundle\Entity\Field $parentField
     * @return Field
     */
    public function addParentField(\Hris\FormBundle\Entity\Field $parentField)
    {
        $this->parentField[$parentField->getId()] = $parentField;
    
        return $this;
    }

    /**
     * Remove parentField
     *
     * @param \Hris\FormBundle\Entity\Field $parentField
     */
    public function removeParentField(\Hris\FormBundle\Entity\Field $parentField)
    {
        $this->parentField->removeElement($parentField);
    }

    /**
     * Get parentField
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParentField()
    {
        return $this->parentField;
    }

    /**
     * Add childField
     *
     * @param \Hris\FormBundle\Entity\Field $childField
     * @return Field
     */
    public function addChildField(\Hris\FormBundle\Entity\Field $childField)
    {
        $this->childField[$childField->getId()] = $childField;
    
        return $this;
    }

    /**
     * Remove childField
     *
     * @param \Hris\FormBundle\Entity\Field $childField
     */
    public function removeChildField(\Hris\FormBundle\Entity\Field $childField)
    {
        $this->childField->removeElement($childField);
    }

    /**
     * Get childField
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildField()
    {
        return $this->childField;
    }

    /**
     * Set dataType
     *
     * @param \Hris\FormBundle\Entity\DataType $dataType
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
     * @return \Hris\FormBundle\Entity\DataType
     */
    public function getDataType()
    {
        return $this->dataType;
    }

    /**
     * Set inputType
     *
     * @param \Hris\FormBundle\Entity\InputType $inputType
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
     * @return \Hris\FormBundle\Entity\InputType
     */
    public function getInputType()
    {
        return $this->inputType;
    }

    /**
     * Add fieldOption
     *
     * @param \Hris\FormBundle\Entity\FieldOption $fieldOption
     * @return Field
     */
    public function addFieldOption(\Hris\FormBundle\Entity\FieldOption $fieldOption)
    {
        $this->fieldOption[$fieldOption->getId()] = $fieldOption;
    
        return $this;
    }

    /**
     * Remove fieldOption
     *
     * @param \Hris\FormBundle\Entity\FieldOption $fieldOption
     */
    public function removeFieldOption(\Hris\FormBundle\Entity\FieldOption $fieldOption)
    {
        $this->fieldOption->removeElement($fieldOption);
    }

    /**
     * Get fieldOption
     *
     * @return \Doctrine\Common\Collections\Collection
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

    /**
     * Add fieldOptionMerge
     *
     * @param \Hris\FormBundle\Entity\FieldOptionMerge $fieldOptionMerge
     * @return Field
     */
    public function addFieldOptionMerge(\Hris\FormBundle\Entity\FieldOptionMerge $fieldOptionMerge)
    {
        $this->fieldOptionMerge[$fieldOptionMerge->getId()] = $fieldOptionMerge;
    
        return $this;
    }

    /**
     * Remove fieldOptionMerge
     *
     * @param \Hris\FormBundle\Entity\FieldOptionMerge $fieldOptionMerge
     */
    public function removeFieldOptionMerge(\Hris\FormBundle\Entity\FieldOptionMerge $fieldOptionMerge)
    {
        $this->fieldOptionMerge->removeElement($fieldOptionMerge);
    }

    /**
     * Get fieldOptionMerge
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFieldOptionMerge()
    {
        return $this->fieldOptionMerge;
    }

    /**
     * Add formFieldMember
     *
     * @param \Hris\FormBundle\Entity\FormFieldMember $formFieldMember
     * @return Field
     */
    public function addFormFieldMember(\Hris\FormBundle\Entity\FormFieldMember $formFieldMember)
    {
        $this->formFieldMember[] = $formFieldMember;
    
        return $this;
    }

    /**
     * Remove formFieldMember
     *
     * @param \Hris\FormBundle\Entity\FormFieldMember $formFieldMember
     */
    public function removeFormFieldMember(\Hris\FormBundle\Entity\FormFieldMember $formFieldMember)
    {
        $this->formFieldMember->removeElement($formFieldMember);
    }

    /**
     * Get formFieldMember
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormFieldMember()
    {
        return $this->formFieldMember;
    }
    

    /**
     * Add formSectionFieldMember
     *
     * @param \Hris\FormBundle\Entity\FormSectionFieldMember $formSectionFieldMember
     * @return Field
     */
    public function addFormSectionFieldMember(\Hris\FormBundle\Entity\FormSectionFieldMember $formSectionFieldMember)
    {
        $this->formSectionFieldMember[] = $formSectionFieldMember;
    
        return $this;
    }

    /**
     * Remove formSectionFieldMember
     *
     * @param \Hris\FormBundle\Entity\FormSectionFieldMember $formSectionFieldMember
     */
    public function removeFormSectionFieldMember(\Hris\FormBundle\Entity\FormSectionFieldMember $formSectionFieldMember)
    {
        $this->formSectionFieldMember->removeElement($formSectionFieldMember);
    }

    /**
     * Get formSectionFieldMember
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormSectionFieldMember()
    {
        return $this->formSectionFieldMember;
    }

    /**
     * Add resourceTableFieldMember
     *
     * @param \Hris\FormBundle\Entity\ResourceTableFieldMember $resourceTableFieldMember
     * @return Field
     */
    public function addResourceTableFieldMember(\Hris\FormBundle\Entity\ResourceTableFieldMember $resourceTableFieldMember)
    {
        $this->resourceTableFieldMember[] = $resourceTableFieldMember;
    
        return $this;
    }

    /**
     * Remove resourceTableFieldMember
     *
     * @param \Hris\FormBundle\Entity\ResourceTableFieldMember $resourceTableFieldMember
     */
    public function removeResourceTableFieldMember(\Hris\FormBundle\Entity\ResourceTableFieldMember $resourceTableFieldMember)
    {
        $this->resourceTableFieldMember->removeElement($resourceTableFieldMember);
    }

    /**
     * Get resourceTableFieldMember
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResourceTableFieldMember()
    {
        return $this->resourceTableFieldMember;
    }
    
    /**
     * Add uniqueRecordForms
     *
     * @param \Hris\FormBundle\Entity\Form $uniqueRecordForms
     * @return Field
     */
    public function addUniqueRecordForm(\Hris\FormBundle\Entity\Form $uniqueRecordForms)
    {
        $this->uniqueRecordForms[$uniqueRecordForms->getId()] = $uniqueRecordForms;
    
        return $this;
    }

    /**
     * Remove uniqueRecordForms
     *
     * @param \Hris\FormBundle\Entity\Form $uniqueRecordForms
     */
    public function removeUniqueRecordForm(\Hris\FormBundle\Entity\Form $uniqueRecordForms)
    {
        $this->uniqueRecordForms->removeElement($uniqueRecordForms);
    }

    /**
     * Get uniqueRecordForms
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUniqueRecordForms()
    {
        return $this->uniqueRecordForms;
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
        $this->formVisibleFields = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fieldOptionMerge = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formFieldMember = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formSectionFieldMember = new \Doctrine\Common\Collections\ArrayCollection();
        $this->uniqueRecordForms = new \Doctrine\Common\Collections\ArrayCollection();
        $this->resourceTableFieldMember = new \Doctrine\Common\Collections\ArrayCollection();
        $this->uid = uniqid();
        $this->datecreated = new \DateTime('now');
        $this->hashistory = false;
        $this->isUnique = false;
        $this->compulsory = True;
    }
    
    /**
     * Add formVisibleFields
     *
     * @param \Hris\FormBundle\Entity\FormVisibleFields $formVisibleFields
     * @return Field
     */
    public function addFormVisibleField(\Hris\FormBundle\Entity\FormVisibleFields $formVisibleFields)
    {
        $this->formVisibleFields[] = $formVisibleFields;
    
        return $this;
    }

    /**
     * Remove formVisibleFields
     *
     * @param \Hris\FormBundle\Entity\FormVisibleFields $formVisibleFields
     */
    public function removeFormVisibleField(\Hris\FormBundle\Entity\FormVisibleFields $formVisibleFields)
    {
        $this->formVisibleFields->removeElement($formVisibleFields);
    }

    /**
     * Get formVisibleFields
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormVisibleFields()
    {
        return $this->formVisibleFields;
    }

    /**
     * Add recordValue
     *
     * @param \Hris\RecordsBundle\Entity\RecordValue $recordValue
     * @return Field
     */
    public function addRecordValue(\Hris\RecordsBundle\Entity\RecordValue $recordValue)
    {
        $this->recordValue[$recordValue->getId()] = $recordValue;
    
        return $this;
    }

    /**
     * Remove recordValue
     *
     * @param \Hris\RecordsBundle\Entity\RecordValue $recordValue
     */
    public function removeRecordValue(\Hris\RecordsBundle\Entity\RecordValue $recordValue)
    {
        $this->recordValue->removeElement($recordValue);
    }

    /**
     * Get recordValue
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecordValue()
    {
        return $this->recordValue;
    }

    /**
     * Add recordStats
     *
     * @param \Hris\RecordsBundle\Entity\RecordStats $recordStats
     * @return Field
     */
    public function addRecordStat(\Hris\RecordsBundle\Entity\RecordStats $recordStats)
    {
        $this->recordStats[$recordStats->getId()] = $recordStats;
    
        return $this;
    }

    /**
     * Remove recordStats
     *
     * @param \Hris\RecordsBundle\Entity\RecordStats $recordStats
     */
    public function removeRecordStat(\Hris\RecordsBundle\Entity\RecordStats $recordStats)
    {
        $this->recordStats->removeElement($recordStats);
    }

    /**
     * Get recordStats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecordStats()
    {
        return $this->recordStats;
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
}
