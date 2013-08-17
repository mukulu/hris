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

use Hris\FormBundle\Entity\FieldOption;
use Hris\FormBundle\Entity\Field;

/**
 * Hris\FormBundle\Entity\FieldOptionMerge
 *
 * @ORM\Table(name="hris_fieldoptionmerge")
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
     * @ORM\Column(name="uid", type="string", length=13)
     */
    private $uid;

    /**
     * @var \Hris\FormBundle\Entity\FieldOption $mergedFieldOption
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\FieldOption", inversedBy="fieldOptionMerge")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mergedfieldoption_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $mergedFieldOption;

    /**
     * @var \Hris\FormBundle\Entity\Field $removedOptionField
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\Field", inversedBy="fieldOptionMerge")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="removedoptionfield_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $removedOptionField;

    /**
     * @var string $removedoptionvalue
     *
     * @ORM\Column(name="removedoptionvalue", type="string", length=64)
     */
    private $removedoptionvalue;
    
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
     * Set removedoptionvalue
     *
     * @param string $removedoptionvalue
     * @return FieldOptionMerge
     */
    public function setRemovedoptionvalue($removedoptionvalue)
    {
        $this->removedoptionvalue = $removedoptionvalue;
    
        return $this;
    }

    /**
     * Get removedoptionvalue
     *
     * @return string 
     */
    public function getRemovedoptionvalue()
    {
        return $this->removedoptionvalue;
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
     * @param \Hris\FormBundle\Entity\FieldOption $mergedFieldOption
     * @return FieldOptionMerge
     */
    public function setMergedFieldOption(\Hris\FormBundle\Entity\FieldOption $mergedFieldOption = null)
    {
        $this->mergedFieldOption = $mergedFieldOption;
    
        return $this;
    }

    /**
     * Get mergedFieldOption
     *
     * @return \Hris\FormBundle\Entity\FieldOption
     */
    public function getMergedFieldOption()
    {
        return $this->mergedFieldOption;
    }

    /**
     * Set removedOptionField
     *
     * @param \Hris\FormBundle\Entity\Field $removedOptionField
     * @return FieldOptionMerge
     */
    public function setRemovedOptionField(\Hris\FormBundle\Entity\Field $removedOptionField = null)
    {
        $this->removedOptionField = $removedOptionField;
    
        return $this;
    }

    /**
     * Get removedOptionField
     *
     * @return \Hris\FormBundle\Entity\Field
     */
    public function getRemovedOptionField()
    {
        return $this->removedOptionField;
    }

    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return FieldOptionMerge
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
     * @return FieldOptionMerge
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
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        $fieldOptionMergeName = 'Merged Option:'.$this->getMergedFieldOption()->__toString().' Removed Option:'.$this->getRemovedOptionField()->__toString();
        return $fieldOptionMergeName;
    }
}