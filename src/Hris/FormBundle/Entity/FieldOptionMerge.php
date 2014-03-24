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

use Hris\FormBundle\Entity\FieldOption;
use Hris\FormBundle\Entity\Field;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\FormBundle\Entity\FieldOptionMerge
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="hris_fieldoptionmerge",uniqueConstraints={ @ORM\UniqueConstraint(name="unique_fieldoptionmerge_idx",columns={"field_id", "mergedfieldoption_id","removedFieldOptionValue"}) })
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\FieldOptionMergeRepository")
 */
class FieldOptionMerge
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
     * @var Field $field
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\Field", inversedBy="fieldOptionMerge")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="field_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $field;

    /**
     * @var FieldOption $mergedFieldOption
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\FieldOption", inversedBy="fieldOptionMerge")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mergedfieldoption_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $mergedFieldOption;

    /**
     * @var string $removedFieldOptionValue
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="removedFieldOptionValue", type="string", length=64)
     */
    private $removedFieldOptionValue;

    /**
     * @var string $removedFieldOptionUid
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="removedFieldOptionUid", type="string", length=13, unique=true)
     */
    private $removedFieldOptionUid;

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
     * Set removedFieldOptionValue
     *
     * @param string $removedFieldOptionValue
     * @return FieldOptionMerge
     */
    public function setRemovedFieldOptionValue($removedFieldOptionValue)
    {
        $this->removedFieldOptionValue = $removedFieldOptionValue;
    
        return $this;
    }

    /**
     * Get removedFieldOptionValue
     *
     * @return string 
     */
    public function getRemovedFieldOptionValue()
    {
        return $this->removedFieldOptionValue;
    }

    /**
     * Set removedFieldOptionUid
     *
     * @param string $removedFieldOptionUid
     * @return FieldOptionMerge
     */
    public function setRemovedFieldOptionUid($removedFieldOptionUid)
    {
        $this->removedFieldOptionUid = $removedFieldOptionUid;

        return $this;
    }

    /**
     * Get removedFieldOptionUid
     *
     * @return string
     */
    public function getRemovedFieldOptionUid()
    {
        return $this->removedFieldOptionUid;
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return FieldOptionMerge
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
     * Set mergedFieldOption
     *
     * @param FieldOption $mergedFieldOption
     * @return FieldOptionMerge
     */
    public function setMergedFieldOption(FieldOption $mergedFieldOption = null)
    {
        $this->mergedFieldOption = $mergedFieldOption;
    
        return $this;
    }

    /**
     * Get mergedFieldOption
     *
     * @return FieldOption
     */
    public function getMergedFieldOption()
    {
        return $this->mergedFieldOption;
    }

    /**
     * Set field
     *
     * @param Field $field
     * @return FieldOptionMerge
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
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        $fieldOptionMergeName = $this->getRemovedFieldOptionValue();
        return $fieldOptionMergeName;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->uid = uniqid();
    }
}