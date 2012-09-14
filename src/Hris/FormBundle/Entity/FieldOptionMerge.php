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

use Hris\FormBundle\Entity\FieldOption;
use Hris\FormBundle\Entity\Field;
use Doctrine\ORM\Mapping as ORM;

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
     * @var Hris\FormBundle\Entity\FieldOption $mergedFieldOption
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\FieldOption", inversedBy="fieldOptionMerge")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mergedfieldoption_id", referencedColumnName="id")
     * })
     */
    private $mergedFieldOption;

    /**
     * @var Hris\FormBundle\Entity\Field $removedOptionField
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\Field", inversedBy="fieldOptionMerge")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="removedoptionfield_id", referencedColumnName="id")
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
     * @var string $uid
     *
     * @ORM\Column(name="uid", type="string", length=11)
     */
    private $uid;


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
     * Set mergedoption
     *
     * @param integer $mergedoption
     * @return FieldOptionMerge
     */
    public function setMergedoption($mergedoption)
    {
        $this->mergedoption = $mergedoption;
    
        return $this;
    }

    /**
     * Get mergedoption
     *
     * @return integer 
     */
    public function getMergedoption()
    {
        return $this->mergedoption;
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
     * @param Hris\FormBundle\Entity\FieldOption $mergedFieldOption
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
     * @return Hris\FormBundle\Entity\FieldOption 
     */
    public function getMergedFieldOption()
    {
        return $this->mergedFieldOption;
    }

    /**
     * Set removedOptionField
     *
     * @param Hris\FormBundle\Entity\Field $removedOptionField
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
     * @return Hris\FormBundle\Entity\Field 
     */
    public function getRemovedOptionField()
    {
        return $this->removedOptionField;
    }
}