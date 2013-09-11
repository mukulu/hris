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

use Doctrine\Common\Collections\ArrayCollection;
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
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Gedmo\Versioned
     * @ORM\Column(name="uid", type="string", length=13, unique=true)
     */
    private $uid;

    /**
     * @var string $name
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=64, unique=true)
     */
    private $name;

    /**
     * @var string $caption
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="caption", type="string", length=64)
     */
    private $caption;

    /**
     * @var boolean $compulsory
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="compulsory", type="boolean", nullable=True)
     */
    private $compulsory;

    /**
     * @var boolean isUnique
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="isUnique", type="boolean", nullable=True)
     */
    private $isUnique;

    /**
     * @var boolean isCalculated
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="isCalculated", type="boolean", nullable=True)
     */
    private $isCalculated;

    /**
     * @var string $description
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string $calculatedExpression
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="calculatedExpression", type="text", nullable=true)
     */
    private $calculatedExpression;

    /**
     * @var boolean $hashistory
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="hashistory", type="boolean", nullable=True)
     */
    private $hashistory;

    /**
     * @var boolean $fieldrelation
     *
     * @ORM\Column(name="fieldrelation", type="boolean", nullable=true)
     */
    private $fieldrelation;
    
    /**
     * @var FieldGroup $fieldGroup
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FieldGroup", mappedBy="field")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $fieldGroup;
    
    /**
     * @var Field $parentField
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\Field", mappedBy="childField")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $parentField;
    
    /**
     * @var Field $childField
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
     * @var DataType $dataType
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\DataType",inversedBy="field")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="datatype_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $dataType;
    
    /**
     * @var InputType $inputType
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\InputType",inversedBy="field")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="inputtype_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $inputType;
    
    /**
     * @var FormVisibleFields $formVisibleFields
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\FormVisibleFields", mappedBy="field",cascade={"ALL"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $formVisibleFields;
    
    /**
     * @var FieldOptionMerge $fieldOptionMerge
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\FieldOptionMerge", mappedBy="removedOptionField",cascade={"ALL"})
     * @ORM\OrderBy({"removedoptionvalue" = "ASC"})
     */
    private $fieldOptionMerge;
    
    /**
     * @var FormFieldMember $formFieldMember
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\FormFieldMember", mappedBy="field", cascade={"ALL"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $formFieldMember;
    
    /**
     * @var FormSectionFieldMember $formSectionFieldMember
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\FormSectionFieldMember", mappedBy="field",cascade={"ALL"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $formSectionFieldMember;
    
    /**
     * @var Form $uniqueRecordForms
     * Forms in which this field together(or not) with others makes a record unique in the form
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\Form", mappedBy="uniqueRecordFields")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $uniqueRecordForms;
    
    /**
     * @var ResourceTableFieldMember $resourceTableFieldMember
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
        $this->isUnique = $isUnique;
    
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
     * @param FieldGroup $fieldGroup
     * @return Field
     */
    public function addFieldGroup(FieldGroup $fieldGroup)
    {
        $this->fieldGroup[$fieldGroup->getId()] = $fieldGroup;
    
        return $this;
    }

    /**
     * Remove fieldGroup
     *
     * @param FieldGroup $fieldGroup
     */
    public function removeFieldGroup(FieldGroup $fieldGroup)
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
     * @param Field $parentField
     * @return Field
     */
    public function addParentField(Field $parentField)
    {
        $this->parentField[$parentField->getId()] = $parentField;
    
        return $this;
    }

    /**
     * Remove parentField
     *
     * @param Field $parentField
     */
    public function removeParentField(Field $parentField)
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
     * @param Field $childField
     * @return Field
     */
    public function addChildField(Field $childField)
    {
        $this->childField[$childField->getId()] = $childField;
    
        return $this;
    }

    /**
     * Remove childField
     *
     * @param Field $childField
     */
    public function removeChildField(Field $childField)
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
     * @param DataType $dataType
     * @return Field
     */
    public function setDataType(DataType $dataType = null)
    {
        $this->dataType = $dataType;
    
        return $this;
    }

    /**
     * Get dataType
     *
     * @return DataType
     */
    public function getDataType()
    {
        return $this->dataType;
    }

    /**
     * Set inputType
     *
     * @param InputType $inputType
     * @return Field
     */
    public function setInputType(InputType $inputType = null)
    {
        $this->inputType = $inputType;
    
        return $this;
    }

    /**
     * Get inputType
     *
     * @return InputType
     */
    public function getInputType()
    {
        return $this->inputType;
    }

    /**
     * Add fieldOption
     *
     * @param FieldOption $fieldOption
     * @return Field
     */
    public function addFieldOption(FieldOption $fieldOption)
    {
        $this->fieldOption[$fieldOption->getId()] = $fieldOption;
    
        return $this;
    }

    /**
     * Remove fieldOption
     *
     * @param FieldOption $fieldOption
     */
    public function removeFieldOption(FieldOption $fieldOption)
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
     * @param FieldOptionMerge $fieldOptionMerge
     * @return Field
     */
    public function addFieldOptionMerge(FieldOptionMerge $fieldOptionMerge)
    {
        $this->fieldOptionMerge[$fieldOptionMerge->getId()] = $fieldOptionMerge;
    
        return $this;
    }

    /**
     * Remove fieldOptionMerge
     *
     * @param FieldOptionMerge $fieldOptionMerge
     */
    public function removeFieldOptionMerge(FieldOptionMerge $fieldOptionMerge)
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
     * @param FormFieldMember $formFieldMember
     * @return Field
     */
    public function addFormFieldMember(FormFieldMember $formFieldMember)
    {
        $this->formFieldMember[] = $formFieldMember;
    
        return $this;
    }

    /**
     * Remove formFieldMember
     *
     * @param FormFieldMember $formFieldMember
     */
    public function removeFormFieldMember(FormFieldMember $formFieldMember)
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
     * @param FormSectionFieldMember $formSectionFieldMember
     * @return Field
     */
    public function addFormSectionFieldMember(FormSectionFieldMember $formSectionFieldMember)
    {
        $this->formSectionFieldMember[] = $formSectionFieldMember;
    
        return $this;
    }

    /**
     * Remove formSectionFieldMember
     *
     * @param FormSectionFieldMember $formSectionFieldMember
     */
    public function removeFormSectionFieldMember(FormSectionFieldMember $formSectionFieldMember)
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
     * @param ResourceTableFieldMember $resourceTableFieldMember
     * @return Field
     */
    public function addResourceTableFieldMember(ResourceTableFieldMember $resourceTableFieldMember)
    {
        $this->resourceTableFieldMember[] = $resourceTableFieldMember;
    
        return $this;
    }

    /**
     * Remove resourceTableFieldMember
     *
     * @param ResourceTableFieldMember $resourceTableFieldMember
     */
    public function removeResourceTableFieldMember(ResourceTableFieldMember $resourceTableFieldMember)
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
     * @param Form $uniqueRecordForms
     * @return Field
     */
    public function addUniqueRecordForm(Form $uniqueRecordForms)
    {
        $this->uniqueRecordForms[$uniqueRecordForms->getId()] = $uniqueRecordForms;
    
        return $this;
    }

    /**
     * Remove uniqueRecordForms
     *
     * @param Form $uniqueRecordForms
     */
    public function removeUniqueRecordForm(Form $uniqueRecordForms)
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
        $this->fieldGroup = new ArrayCollection();
        $this->parentField = new ArrayCollection();
        $this->childField = new ArrayCollection();
        $this->fieldOption = new ArrayCollection();
        $this->formVisibleFields = new ArrayCollection();
        $this->fieldOptionMerge = new ArrayCollection();
        $this->formFieldMember = new ArrayCollection();
        $this->formSectionFieldMember = new ArrayCollection();
        $this->uniqueRecordForms = new ArrayCollection();
        $this->resourceTableFieldMember = new ArrayCollection();
        $this->uid = uniqid();
        $this->datecreated = new \DateTime('now');
        $this->hashistory = false;
        $this->isUnique = false;
        $this->compulsory = True;
        $this->isCalculated=False;
    }
    
    /**
     * Add formVisibleFields
     *
     * @param FormVisibleFields $formVisibleFields
     * @return Field
     */
    public function addFormVisibleField(FormVisibleFields $formVisibleFields)
    {
        $this->formVisibleFields[] = $formVisibleFields;
    
        return $this;
    }

    /**
     * Remove formVisibleFields
     *
     * @param FormVisibleFields $formVisibleFields
     */
    public function removeFormVisibleField(FormVisibleFields $formVisibleFields)
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
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->caption;
    }

    /**
     * Set isCalculated
     *
     * @param boolean $isCalculated
     * @return Field
     */
    public function setIsCalculated($isCalculated)
    {
        $this->isCalculated = $isCalculated;
    
        return $this;
    }

    /**
     * Get isCalculated
     *
     * @return boolean 
     */
    public function getIsCalculated()
    {
        return $this->isCalculated;
    }

    /**
     * Set calculatedExpression
     *
     * @param string $calculatedExpression
     * @return Field
     */
    public function setCalculatedExpression($calculatedExpression)
    {
        $this->calculatedExpression = $calculatedExpression;
    
        return $this;
    }

    /**
     * Get calculatedExpression
     *
     * @return string 
     */
    public function getCalculatedExpression()
    {
        return $this->calculatedExpression;
    }
}