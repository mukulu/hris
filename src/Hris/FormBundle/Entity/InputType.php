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
use \DateTime;

/**
 * Hris\FormBundle\Entity\InputType
 *
 * @ORM\Table(name="hris_field_inputtype")
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\InputTypeRepository")
 */
class InputType
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
     * @ORM\Column(name="name", type="string", length=64, unique=true )
     */
    private $name;
    
    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string $htmltag
     *
     * @ORM\Column(name="htmltag", type="string", length=255, nullable=true, unique=true )
     */
    private $htmltag;
    
    /**
     * @var \Hris\FormBundle\Entity\Field $field
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\Field", mappedBy="inputType",cascade={"ALL"})
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $field;
    
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
     * @return InputType
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
     * Set htmltag
     *
     * @param string $htmltag
     * @return InputType
     */
    public function setHtmltag($htmltag)
    {
        $this->htmltag = $htmltag;
    
        return $this;
    }

    /**
     * Get htmltag
     *
     * @return string 
     */
    public function getHtmltag()
    {
        return $this->htmltag;
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return InputType
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
     * @return InputType
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
     * @return InputType
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
     * @return InputType
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
     * Constructor
     */
    public function __construct()
    {
        $this->field = new \Doctrine\Common\Collections\ArrayCollection();
        $this->uid = uniqid();
        $this->datecreated = new \DateTime('now');
    }
    
    /**
     * Add field
     *
     * @param \Hris\FormBundle\Entity\Field $field
     * @return InputType
     */
    public function addField(\Hris\FormBundle\Entity\Field $field)
    {
        $this->field[$field->getId()] = $field;
    
        return $this;
    }

    /**
     * Remove field
     *
     * @param \Hris\FormBundle\Entity\Field $field
     */
    public function removeField(\Hris\FormBundle\Entity\Field $field)
    {
        $this->field->removeElement($field);
    }

    /**
     * Get field
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getField()
    {
        return $this->field;
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
