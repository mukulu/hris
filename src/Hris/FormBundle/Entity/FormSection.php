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

use Hris\FormBundle\Entity\FormSectionFieldMember;
use Hris\FormBundle\Entity\Form;
use Doctrine\ORM\Mapping as ORM;

/**
 * Hris\FormBundle\Entity\FormSection
 *
 * @ORM\Table(name="hris_formsection")
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\FormSectionRepository")
 */
class FormSection
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string $uid
     *
     * @ORM\Column(name="uid", type="string", length=11)
     */
    private $uid;
    
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
     * @var Hris\FormBundle\Entity\Form $form
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\Form")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="form_id", referencedColumnName="id")
     * })
     */
    private $form;
    
    /**
     * @var Hris\FormBundle\Entity\FormSectionFieldMember $field
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\FormSectionFieldMember", mappedBy="form",cascade={"ALL"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $field;


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
     * @return FormSection
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
     * Set description
     *
     * @param string $description
     * @return FormSection
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
     * Set uid
     *
     * @param string $uid
     * @return FormSection
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
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return FormSection
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
     * @return FormSection
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
     * Set form
     *
     * @param Hris\FormBundle\Entity\Form $form
     * @return FormSection
     */
    public function setForm(\Hris\FormBundle\Entity\Form $form = null)
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
     * Constructor
     */
    public function __construct()
    {
        $this->field = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

    /**
     * Add field
     *
     * @param Hris\FormBundle\Entity\FormSectionFieldMember $field
     * @return FormSection
     */
    public function addField(\Hris\FormBundle\Entity\FormSectionFieldMember $field)
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
    	$this->field[] = new \Hris\FormBundle\Entity\FormSectionFieldMember($this, $field, $this->sort);
    
    	return $this;
    }

    /**
     * Remove field
     *
     * @param Hris\FormBundle\Entity\FormSectionFieldMember $field
     */
    public function removeField(\Hris\FormBundle\Entity\FormSectionFieldMember $field)
    {
        $this->field->removeElement($field);
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
    		foreach( $this->field as $key => $formSectionFieldMember ) {
    			$simpleFields->add($formSectionFieldMember->getField());
    		}
    	}
    	return $simpleFields;
    }
}