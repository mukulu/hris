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

use Hris\FormBundle\Entity\Field;
use Hris\FormBundle\Entity\FormSection;

/**
 * Hris\FormBundle\Entity\FormSectionFieldMember
 *
 * @ORM\Table(name="hris_formsection_fieldmembers")
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\FormSectionFieldMemberRepository")
 */
class FormSectionFieldMember
{
    /**
     * @var \Hris\FormBundle\Entity\FormSection $formSection
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\FormSection",inversedBy="formSectionFieldMember")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="formsection_id", referencedColumnName="id",nullable=false, onDelete="CASCADE")
     * })
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $formSection;
    
    /**
     * @var \Hris\FormBundle\Entity\Field $field
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\Field",inversedBy="formSectionFieldMember")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="field_id", referencedColumnName="id",nullable=false, onDelete="CASCADE")
     * })
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $field;

    /**
     * @var integer $sort
     *
     * @ORM\Column(name="sort", type="integer")
     */
    private $sort;
    
    public function __construct($formSection=NULL,$field=NULL,$sort=NULL)
    {
    	if(!empty($formSection)) $this->formSection = $formSection;
    	if(!empty($field)) $this->field = $field;
    	if(!empty($sort)) $this->sort = $sort;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     * @return FormSectionFieldMember
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    
        return $this;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set formSection
     *
     * @param \Hris\FormBundle\Entity\FormSection $formSection
     * @return FormSectionFieldMember
     */
    public function setFormSection(\Hris\FormBundle\Entity\FormSection $formSection)
    {
        $this->formSection = $formSection;
    
        return $this;
    }

    /**
     * Get formSection
     *
     * @return \Hris\FormBundle\Entity\FormSection
     */
    public function getFormSection()
    {
        return $this->formSection;
    }

    /**
     * Set field
     *
     * @param \Hris\FormBundle\Entity\Field $field
     * @return FormSectionFieldMember
     */
    public function setField(\Hris\FormBundle\Entity\Field $field)
    {
        $this->field = $field;
    
        return $this;
    }

    /**
     * Get field
     *
     * @return \Hris\FormBundle\Entity\Field
     */
    public function getField()
    {
        return $this->field;
    }
}