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

use Doctrine\ORM\Mapping as ORM;

use Hris\FormBundle\Entity\Field;
use Hris\FormBundle\Entity\Form;

/**
 * Hris\FormBundle\Entity\FormVisibleFields
 *
 * @ORM\Table(name="hris_form_visiblefields")
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\VisibleFormFieldsRepository")
 */
class FormVisibleFields
{
    
    /**
     * @var Hris\FormBundle\Entity\Form $form
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\Form",inversedBy="formVisibleFields")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="form_id", referencedColumnName="id",nullable=false)
     * })
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $form;
    
    /**
     * @var Hris\FormBundle\Entity\Field $field
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\Field",inversedBy="formVisibleFields")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="field_id", referencedColumnName="id",nullable=false)
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
    
    public function __construct($form=NULL,$field=NULL,$sort=NULL)
    {
    	if(!empty($form)) $this->form = $form;
    	if(!empty($field)) $this->field = $field;
    	if(!empty($sort)) $this->sort = $sort;
    }

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
     * Set sort
     *
     * @param integer $sort
     * @return VisibleFormFields
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
     * Set form
     *
     * @param Hris\FormBundle\Entity\Form $form
     * @return VisibleFormFields
     */
    public function setForm(\Hris\FormBundle\Entity\Form $form)
    {
        $this->form = $form;
    
        return $this;
    }

    /**
     * Get form
     *
     * @return Hris\FormBundle\Entity\Form 
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set field
     *
     * @param Hris\FormBundle\Entity\Field $field
     * @return VisibleFormFields
     */
    public function setField(\Hris\FormBundle\Entity\Field $field)
    {
        $this->field = $field;
    
        return $this;
    }

    /**
     * Get field
     *
     * @return Hris\FormBundle\Entity\Field 
     */
    public function getField()
    {
        return $this->field;
    }
}