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

use Hris\FormBundle\Entity\FriendlyReport;
use Hris\FormBundle\Entity\FieldOption;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\FormBundle\Entity\RelationalFilter
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="hris_relationalfilter")
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\RelationalFilterRepository")
 */
class RelationalFilter
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
     * @ORM\Column(name="name", type="string", length=64, unique=true)
     */
    private $name;

    /**
     * @var boolean $excludeFieldOptions
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="excludeFieldOptions", type="boolean")
     */
    private $excludeFieldOptions;
    
    /**
     * @var FieldOption $fieldOption
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FieldOption", inversedBy="relationalFilter")
     * @ORM\JoinTable(name="hris_relationalfilter_member",
     *   joinColumns={
     *     @ORM\JoinColumn(name="relationalfilter_id", referencedColumnName="id", onDelete="CASCADE")
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
     * @var FriendlyReport $friendlyReport
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FriendlyReport", mappedBy="relationalFilter")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $friendlyReport;

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
     * @return RelationalFilter
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
     * Set excludeFieldOptions
     *
     * @param boolean $excludeFieldOptions
     * @return RelationalFilter
     */
    public function setExcludeFieldOptions($excludeFieldOptions)
    {
        $this->excludeFieldOptions = $excludeFieldOptions;
    
        return $this;
    }

    /**
     * Get excludeFieldOptions
     *
     * @return boolean 
     */
    public function getExcludeFieldOptions()
    {
        return $this->excludeFieldOptions;
    }
    
    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return RelationalFilter
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
     * @return RelationalFilter
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
     * @return RelationalFilter
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
     * Add fieldOption
     *
     * @param FieldOption $fieldOption
     * @return RelationalFilter
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
     * Set field
     *
     * @param Field $field
     * @return RelationalFilter
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
     * Add friendlyReport
     *
     * @param FriendlyReport $friendlyReport
     * @return RelationalFilter
     */
    public function addFriendlyReport(FriendlyReport $friendlyReport)
    {
        $this->friendlyReport[$friendlyReport->getId()] = $friendlyReport;
    
        return $this;
    }

    /**
     * Remove friendlyReport
     *
     * @param FriendlyReport $friendlyReport
     */
    public function removeFriendlyReport(FriendlyReport $friendlyReport)
    {
        $this->friendlyReport->removeElement($friendlyReport);
    }

    /**
     * Get friendlyReport
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFriendlyReport()
    {
        return $this->friendlyReport;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fieldOption = new ArrayCollection();
        $this->friendlyReport = new ArrayCollection();
        $this->uid = uniqid();
        $this->excludeFieldOptions = FALSE;
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