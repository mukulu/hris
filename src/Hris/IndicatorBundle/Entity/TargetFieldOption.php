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
namespace Hris\IndicatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Hris\FormBundle\Entity\FieldOption;
use Hris\IndicatorBundle\Entity\Target;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\TargetBundle\Entity\TargetFieldOption
 *
 * @ORM\Table(name="hris_indicator_targetfieldoption")
 * @ORM\Entity(repositoryClass="Hris\TargetBundle\Entity\TargetFieldOptionRepository")
 */
class TargetFieldOption
{
    /**
     * @var Target $target
     *
     * @ORM\ManyToOne(targetEntity="Hris\IndicatorBundle\Entity\Target",inversedBy="targetFieldOption")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="target_id", referencedColumnName="id",nullable=false, onDelete="CASCADE")
     * })
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $target;
    
    /**
     * @var FieldOption $fieldOption
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\FieldOption",inversedBy="targetFieldOption")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fieldoption_id", referencedColumnName="id",nullable=false, onDelete="CASCADE")
     * })
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $fieldOption;

    /**
     * @var integer $value
     *
     * @ORM\Column(name="value", type="integer")
     */
    private $value;
    
    public function __construct($target=NULL,$fieldOption=NULL,$value=NULL)
    {
    	if(!empty($target)) $this->target = $target;
    	if(!empty($fieldOption)) $this->fieldOption = $fieldOption;
    	if(!empty($value)) $this->value = $value;
    }

    /**
     * Set value
     *
     * @param integer $value
     * @return TargetFieldOption
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set target
     *
     * @param Target $target
     * @return TargetFieldOption
     */
    public function setTarget(Target $target)
    {
        $this->target = $target;
    
        return $this;
    }

    /**
     * Get target
     *
     * @return Target
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set fieldOption
     *
     * @param FieldOption $fieldOption
     * @return TargetFieldOption
     */
    public function setFieldOption(FieldOption $fieldOption)
    {
        $this->fieldOption = $fieldOption;
    
        return $this;
    }

    /**
     * Get fieldOption
     *
     * @return FieldOption
     */
    public function getFieldOption()
    {
        return $this->fieldOption;
    }

    /**
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        $targetFieldOption = $this->getTarget()->__toString().':'.$this->getFieldOption()->__toString();
        return $targetFieldOption;
    }
}