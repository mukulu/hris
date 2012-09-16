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

use Hris\FormBundle\Entity\FormFieldMember;
use Hris\FormBundle\Entity\VisibleFormFields;
use Hris\FormBundle\Entity\Field;
use Doctrine\ORM\Mapping as ORM;

/**
 * Hris\FormBundle\Entity\Form
 *
 * @ORM\Table(name="hris_form")
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\FormRepository")
 */
class Form
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
     * @ORM\Column(name="uid", type="string", length=11, nullable=false, unique=true)
     */
    private $uid;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=false, unique=true)
     */
    private $name;

    /**
     * @var string $hypertext
     *
     * @ORM\Column(name="hypertext", type="text", nullable=true)
     */
    private $hypertext;

    /**
     * @var string $formtitle
     *
     * @ORM\Column(name="formtitle", type="string", length=64, nullable=true)
     */
    private $formtitle;

    /**
     * @var \DateTime $datecreated
     *
     * @ORM\Column(name="datecreated", type="datetime", nullable=false)
     */
    private $datecreated;
    
    /**
     * @var \DateTime $lastupdated
     *
     * @ORM\Column(name="lastupdated", type="datetime", nullable=true)
     */
    private $lastupdated;
    
    /**
     * @var Hris\FormBundle\Entity\Field $uniqueRecordFields
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\Field", inversedBy="form")
     * @ORM\JoinTable(name="hris_formuniquerecordfields",
     *   joinColumns={
     *     @ORM\JoinColumn(name="form_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="field_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $uniqueRecordFields;
    
    /**
     * @var Hris\FormBundle\Entity\FormFieldMember $field
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\FormFieldMember", mappedBy="form",cascade={"ALL"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $field;
    
    /**
     * @var Hris\FormBundle\Entity\VisibleFormFields $visibleFormFields
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\VisibleFormFields", mappedBy="form",cascade={"ALL"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $visibleFormFields;
    
    /**
     * Field position in the form counter
     */
    private $sort;


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
     * @return Form
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
     * Set hypertext
     *
     * @param string $hypertext
     * @return Form
     */
    public function setHypertext($hypertext)
    {
        $this->hypertext = $hypertext;
    
        return $this;
    }

    /**
     * Get hypertext
     *
     * @return string 
     */
    public function getHypertext()
    {
        return $this->hypertext;
    }

    /**
     * Set formtitle
     *
     * @param string $formtitle
     * @return Form
     */
    public function setFormtitle($formtitle)
    {
        $this->formtitle = $formtitle;
    
        return $this;
    }

    /**
     * Get formtitle
     *
     * @return string 
     */
    public function getFormtitle()
    {
        return $this->formtitle;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->uniqueRecordFields = new \Doctrine\Common\Collections\ArrayCollection();
        $this->field = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sort = 0;
    }
    
    /**
     * Add uniqueRecordFields
     *
     * @param Hris\FormBundle\Entity\Field $uniqueRecordFields
     * @return Form
     */
    public function addUniqueRecordField(\Hris\FormBundle\Entity\Field $uniqueRecordFields)
    {
        $this->uniqueRecordFields[] = $uniqueRecordFields;
    
        return $this;
    }
    
    /**
     * Remove uniqueRecordFields
     *
     * @param Hris\FormBundle\Entity\Field $uniqueRecordFields
     */
    public function removeUniqueRecordField(\Hris\FormBundle\Entity\Field $uniqueRecordFields)
    {
        $this->uniqueRecordFields->removeElement($uniqueRecordFields);
    }

    /**
     * Get uniqueRecordFields
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUniqueRecordFields()
    {
        return $this->uniqueRecordFields;
    }

    

    /**
     * Remove field
     *
     * @param Hris\FormBundle\Entity\VisibleFormFields $field
     */
    public function removeField(\Hris\FormBundle\Entity\VisibleFormFields $field)
    {
        $this->field->removeElement($field);
    }
    
    

    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return Form
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
     * @return Form
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
     * Add field
     *
     * @param Hris\FormBundle\Entity\FormFieldMember $field
     * @return Form
     */
    public function addField(\Hris\FormBundle\Entity\FormFieldMember $field)
    {
        $this->field[] = $field;
    
        return $this;
    }
    
    /**
     * Add field
     *
     * @param Hris\FormBundle\Entity\Field $field
     * @return Form
     */
    public function addSimpleField(Hris\FormBundle\Entity\Field $field)
    {
    	$this->sort += 1;
    	$this->field[] = new \Hris\FormBundle\Entity\FormFieldMember($this, $field, $this->sort);
    
    	return $this;
    }

    /**
     * Get field
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getField()
    {
        return $this->field;
    }
    
    /**
     * Get field
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getSimpleField()
    {
    	$simpleFields = new \Doctrine\Common\Collections\ArrayCollection();
    
    	if(!empty($this->field)) {
    		foreach( $this->field as $key => $formFieldMember ) {
    			$simpleFields->add($formFieldMember->getField());
    		}
    	}
    	return $simpleFields;
    }

    /**
     * Add visibleFormFields
     *
     * @param Hris\FormBundle\Entity\VisibleFormFields $visibleFormFields
     * @return Form
     */
    public function addVisibleFormField(\Hris\FormBundle\Entity\VisibleFormFields $visibleFormFields)
    {
        $this->visibleFormFields[] = $visibleFormFields;
    
        return $this;
    }
    
    /**
     * Add field
     *
     * @param Hris\FormBundle\Entity\Field $field
     * @return Form
     */
    public function addSimpleVisibleFormField(Hris\FormBundle\Entity\Field $field)
    {
    	$this->sort += 1;
    	$this->visibleFormFields[] = new \Hris\FormBundle\Entity\VisibleFormFields($this, $field, $this->sort);
    
    	return $this;
    }

    /**
     * Remove visibleFormFields
     *
     * @param Hris\FormBundle\Entity\VisibleFormFields $visibleFormFields
     */
    public function removeVisibleFormField(\Hris\FormBundle\Entity\VisibleFormFields $visibleFormFields)
    {
        $this->visibleFormFields->removeElement($visibleFormFields);
    }

    /**
     * Get visibleFormFields
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getVisibleFormFields()
    {
        return $this->visibleFormFields;
    }
    
    /**
     * Get field
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getSimpleVisibleFormFields()
    {
    	$simpleVisibleFields = new \Doctrine\Common\Collections\ArrayCollection();
    
    	if(!empty($this->visibleFormFields)) {
    		foreach( $this->visibleFormFields as $key => $visibleFormFields ) {
    			$simpleVisibleFields->add($visibleFormFields->getField());
    		}
    	}
    	return $simpleVisibleFields;
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return Form
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
}