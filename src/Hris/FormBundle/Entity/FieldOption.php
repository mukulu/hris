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

use Hris\FormBundle\Entity\FieldOptionMerge;
use Hris\FormBundle\Entity\RelationalFilter;
use Hris\FormBundle\Entity\FieldOptionGroup;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\FormBundle\Entity\FieldOption
 *
 * @Gedmo\Loggable
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
     * @ORM\Column(name="uid", type="string", length=13, unique=true)
     */
    private $uid;

    /**
     * @var string $value
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="value", type="string", length=64)
     */
    private $value;
    
    /**
     * @var string $description
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    
    /**
     * @var FieldOption $parentFieldOption
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FieldOption", mappedBy="childFieldOption")
     * @ORM\OrderBy({"value" = "ASC"})
     */
    private $parentFieldOption;
    
    /**
     * @var FieldOption $childFieldOption
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FieldOption", inversedBy="parentFieldOption")
     * @ORM\JoinTable(name="hris_fieldoption_children",
     *   joinColumns={
     *     @ORM\JoinColumn(name="parent_fieldoption", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="child_fieldoption", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     * @ORM\OrderBy({"value" = "ASC"})
     */
    private $childFieldOption;
    
    /**
     * @var FieldOptionGroup $fieldOptionGroup
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FieldOptionGroup", mappedBy="fieldOption")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $fieldOptionGroup;
    
    /**
     * @var Field $field
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\Field", inversedBy="fieldOption")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="field_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $field;
    
    /**
     * @var RelationalFilter $relationalFilter
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\RelationalFilter", mappedBy="fieldOption")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $relationalFilter;
    
    /**
     * @var FieldOptionMerge $fieldOptionMerge
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\FieldOptionMerge", mappedBy="mergedFieldOption")
     * @ORM\OrderBy({"removedoptionvalue" = "ASC"})
     */
    private $fieldOptionMerge;

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
     * @param FieldOption $parentFieldOption
     * @return FieldOption
     */
    public function addParentFieldOption(FieldOption $parentFieldOption)
    {
        $this->parentFieldOption[$parentFieldOption->getId()] = $parentFieldOption;
    
        return $this;
    }

    /**
     * Remove parentFieldOption
     *
     * @param FieldOption $parentFieldOption
     */
    public function removeParentFieldOption(FieldOption $parentFieldOption)
    {
        $this->parentFieldOption->removeElement($parentFieldOption);
    }

    /**
     * Get parentFieldOption
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParentFieldOption()
    {
        return $this->parentFieldOption;
    }

    /**
     * Add childFieldOption
     *
     * @param FieldOption $childFieldOption
     * @return FieldOption
     */
    public function addChildFieldOption(FieldOption $childFieldOption)
    {
        $this->childFieldOption[$childFieldOption->getId()] = $childFieldOption;
    
        return $this;
    }

    /**
     * Remove childFieldOption
     *
     * @param FieldOption $childFieldOption
     */
    public function removeChildFieldOption(FieldOption $childFieldOption)
    {
        $this->childFieldOption->removeElement($childFieldOption);
    }

    /**
     * Get childFieldOption
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildFieldOption()
    {
        return $this->childFieldOption;
    }

    /**
     * Add fieldOptionGroup
     *
     * @param FieldOptionGroup $fieldOptionGroup
     * @return FieldOption
     */
    public function addFieldOptionGroup(FieldOptionGroup $fieldOptionGroup)
    {
        $this->fieldOptionGroup[$fieldOptionGroup->getId()] = $fieldOptionGroup;
    
        return $this;
    }

    /**
     * Remove fieldOptionGroup
     *
     * @param FieldOptionGroup $fieldOptionGroup
     */
    public function removeFieldOptionGroup(FieldOptionGroup $fieldOptionGroup)
    {
        $this->fieldOptionGroup->removeElement($fieldOptionGroup);
    }

    /**
     * Get fieldOptionGroup
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFieldOptionGroup()
    {
        return $this->fieldOptionGroup;
    }

    /**
     * Set field
     *
     * @param Field $field
     * @return FieldOption
     */
    public function setField(Field $field = null)
    {
        $this->field = $field;
    
        return $this;
    }

    /**
     * Get field
     *
     * @return Field
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

    /**
     * Add relationalFilter
     *
     * @param RelationalFilter $relationalFilter
     * @return FieldOption
     */
    public function addRelationalFilter(RelationalFilter $relationalFilter)
    {
        $this->relationalFilter[$relationalFilter->getId()] = $relationalFilter;
    
        return $this;
    }

    /**
     * Remove relationalFilter
     *
     * @param RelationalFilter $relationalFilter
     */
    public function removeRelationalFilter(RelationalFilter $relationalFilter)
    {
        $this->relationalFilter->removeElement($relationalFilter);
    }

    /**
     * Get relationalFilter
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRelationalFilter()
    {
        return $this->relationalFilter;
    }

    /**
     * Add fieldOptionMerge
     *
     * @param FieldOptionMerge $fieldOptionMerge
     * @return FieldOption
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
     * Constructor
     */
    public function __construct()
    {
        $this->parentFieldOption = new ArrayCollection();
        $this->childFieldOption = new ArrayCollection();
        $this->fieldOptionGroup = new ArrayCollection();
        $this->relationalFilter = new ArrayCollection();
        $this->fieldOptionMerge = new ArrayCollection();
        $this->uid = uniqid();
    }

    /**
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
    
}