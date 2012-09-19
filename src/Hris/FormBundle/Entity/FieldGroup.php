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
use Hris\FormBundle\Entity\FieldGroupset;

/**
 * Hris\FormBundle\Entity\FieldGroup
 *
 * @ORM\Table(name="hris_fieldgroup")
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\FieldGroupRepository")
 */
class FieldGroup
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
     * @ORM\Column(name="uid", type="string", length=13, unique=true)
     */
    private $uid;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=64, unique=true)
     */
    private $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    
    /**
     * @var Hris\FormBundle\Entity\Field $field
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\Field", inversedBy="fieldGroup")
     * @ORM\JoinTable(name="hris_fieldgroup_members",
     *   joinColumns={
     *     @ORM\JoinColumn(name="fieldgroup_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="field_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $field;
    
    /**
     * @var Hris\FormBundle\Entity\FieldGroupset $fieldGroupset
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FieldGroupset", mappedBy="fieldGroup")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $fieldGroupset;
    
    /**
     * @var \DateTime $datecreated
     *
     * @ORM\Column(name="datecreated", type="datetime")
     */
    private $datecreated;
    
    /**
     * @var \DateTime $lastupdated
     *
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
     * @return FieldGroup
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
     * Set uid
     *
     * @param string $uid
     * @return FieldGroup
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
     * Set description
     *
     * @param string $description
     * @return FieldGroup
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
     * @return FieldGroup
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
     * @return FieldGroup
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
     * @param Hris\FormBundle\Entity\Field $field
     * @return FieldGroup
     */
    public function addField(\Hris\FormBundle\Entity\Field $field)
    {
        $this->field[] = $field;
    
        return $this;
    }

    /**
     * Remove field
     *
     * @param Hris\FormBundle\Entity\Field $field
     */
    public function removeField(\Hris\FormBundle\Entity\Field $field)
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
     * Constructor
     */
    public function __construct()
    {
        $this->field = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fieldGroupset = new \Doctrine\Common\Collections\ArrayCollection();
        $this->uid = uniqid();
    }
    
    /**
     * Add fieldGroupset
     *
     * @param Hris\FormBundle\Entity\FieldGroupset $fieldGroupset
     * @return FieldGroup
     */
    public function addFieldGroupset(\Hris\FormBundle\Entity\FieldGroupset $fieldGroupset)
    {
        $this->fieldGroupset[] = $fieldGroupset;
    
        return $this;
    }

    /**
     * Remove fieldGroupset
     *
     * @param Hris\FormBundle\Entity\FieldGroupset $fieldGroupset
     */
    public function removeFieldGroupset(\Hris\FormBundle\Entity\FieldGroupset $fieldGroupset)
    {
        $this->fieldGroupset->removeElement($fieldGroupset);
    }

    /**
     * Get fieldGroupset
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFieldGroupset()
    {
        return $this->fieldGroupset;
    }
}