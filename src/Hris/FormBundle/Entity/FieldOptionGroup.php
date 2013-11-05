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

use Hris\FormBundle\Entity\FriendlyReportCategory;
use Hris\FormBundle\Entity\FieldOptionGroupset;
use Hris\FormBundle\Entity\FieldOption;
use Hris\FormBundle\Entity\Field;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\FormBundle\Entity\FieldOptionGroup
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="hris_fieldoptiongroup")
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\FieldOptionGroupRepository")
 */
class FieldOptionGroup
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
     * @ORM\Column(name="name", type="string", length=64,unique=true)
     */
    private $name;
    
    /**
     * @var string $description
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    
    /**
     * @var FieldOption $fieldOption
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FieldOption", inversedBy="fieldOptionGroup")
     * @ORM\JoinTable(name="hris_fieldoptiongroup_members",
     *   joinColumns={
     *     @ORM\JoinColumn(name="fieldoptiongroup_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="fieldoption_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     * @ORM\OrderBy({"value" = "ASC"})
     */
    
    private $fieldOption;
    
    /**
     * @var Field $field
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\Field")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="field_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $field;
    
    /**
     * @var FieldOptionGroupset $fieldOptionGroupset
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FieldOptionGroupset", mappedBy="fieldOptionGroup")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $fieldOptionGroupset;
    
    /**
     * @var FriendlyReportCategory $friendlyReportCategory
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\FriendlyReportCategory", mappedBy="fieldOptionGroup")
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $friendlyReportCategory;

    /**
     * @var DataelementFieldOptionRelation $dataelementFieldOptionRelationColumn
     *
     * @ORM\OneToMany(targetEntity="Hris\IntergrationBundle\Entity\DataelementFieldOptionRelation", mappedBy="columnFieldOptionGroup",cascade={"ALL"})
     * @ORM\OrderBy({"dataelementname" = "ASC"})
     */
    private $dataelementFieldOptionRelationColumn;

    /**
     * @var DataelementFieldOptionRelation $dataelementFieldOptionRelationRow
     *
     * @ORM\OneToMany(targetEntity="Hris\IntergrationBundle\Entity\DataelementFieldOptionRelation", mappedBy="rowFieldOptionGroup",cascade={"ALL"})
     * @ORM\OrderBy({"dataelementname" = "ASC"})
     */
    private $dataelementFieldOptionRelationRow;

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
     * @todo Support filter to further constraint criteria for items fitting in the field group
     * e.g. Medical attendants can be mortuary, labolartory, etc, which is determined by department
     * 
     */


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
     * @return FieldOptionGroup
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     * Returns "name - field" or simply "name"
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
     * @return FieldOptionGroup
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
        $this->fieldOption = new ArrayCollection();
        $this->uid = uniqid();
    }
    
    /**
     * Set description
     *
     * @param string $description
     * @return FieldOptionGroup
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
     * Add fieldOption
     *
     * @param FieldOption $fieldOption
     * @return FieldOptionGroup
     */
    public function addFieldOption(FieldOption $fieldOption)
    {
        $this->fieldOption[$fieldOption->getId()] = $fieldOption;
        $fieldOption->addFieldOptionGroup($this);
    
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
     * Set field
     *
     * @param Field $field
     * @return FieldOptionGroup
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
     * Add fieldOptionGroupset
     *
     * @param FieldOptionGroupset $fieldOptionGroupset
     * @return FieldOptionGroup
     */
    public function addFieldOptionGroupset(FieldOptionGroupset $fieldOptionGroupset)
    {
        $this->fieldOptionGroupset[$fieldOptionGroupset->getId()] = $fieldOptionGroupset;
    
        return $this;
    }

    /**
     * Remove fieldOptionGroupset
     *
     * @param FieldOptionGroupset $fieldOptionGroupset
     */
    public function removeFieldOptionGroupset(FieldOptionGroupset $fieldOptionGroupset)
    {
        $this->fieldOptionGroupset->removeElement($fieldOptionGroupset);
    }

    /**
     * Get fieldOptionGroupset
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFieldOptionGroupset()
    {
        return $this->fieldOptionGroupset;
    }

    /**
     * Add friendlyReportCategory
     *
     * @param FriendlyReportCategory $friendlyReportCategory
     * @return FieldOptionGroup
     */
    public function addFriendlyReportCategory(FriendlyReportCategory $friendlyReportCategory)
    {
        $this->friendlyReportCategory[] = $friendlyReportCategory;
    
        return $this;
    }

    /**
     * Remove friendlyReportCategory
     *
     * @param FriendlyReportCategory $friendlyReportCategory
     */
    public function removeFriendlyReportCategory(FriendlyReportCategory $friendlyReportCategory)
    {
        $this->friendlyReportCategory->removeElement($friendlyReportCategory);
    }

    /**
     * Get friendlyReportCategory
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFriendlyReportCategory()
    {
        return $this->friendlyReportCategory;
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