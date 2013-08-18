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
namespace Hris\RecordsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Hris\FormBundle\Entity\Form;
use Hris\FormBundle\Entity\Field;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\RecordsBundle\Entity\RecordStats
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="hris_record_stats")
 * @ORM\Entity(repositoryClass="Hris\RecordsBundle\Entity\RecordStatsRepository")
 */
class RecordStats
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
     * @var Organisationunit $organisationunit
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organisationunit_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * })
     */
    private $organisationunit;
    
    /**
     * @var Form $form
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\Form",inversedBy="record")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="form_id", referencedColumnName="id",nullable=false, onDelete="CASCADE")
     * })
     */
    private $form;

    /**
     * @var boolean $complete
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="complete", type="boolean")
     */
    private $complete;
    
    /**
     * @var boolean $correct
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="correct", type="boolean")
     */
    private $correct;
    
    /**
     * @var Field $field
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\Field", inversedBy="recordStats")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="field_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $field;
    
    /**
     * @var string $value
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;
    
    /**
     * @var integer $count
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @var \DateTime $datecreated
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="datecreated", type="datetime", nullable=false)
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
     * Constructor
     */
    public function __construct()
    {
    	$this->complete = FALSE;
    	$this->correct = TRUE;
    	$this->value = 0;
    	$this->uid = uniqid();
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
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return Record
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
     * @return Record
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
     * Set complete
     *
     * @param boolean $complete
     * @return Record
     */
    public function setComplete($complete)
    {
        $this->complete = $complete;
    
        return $this;
    }

    /**
     * Get complete
     *
     * @return boolean 
     */
    public function getComplete()
    {
        return $this->complete;
    }

    /**
     * Set correct
     *
     * @param boolean $correct
     * @return Record
     */
    public function setCorrect($correct)
    {
        $this->correct = $correct;
    
        return $this;
    }

    /**
     * Get correct
     *
     * @return boolean 
     */
    public function getCorrect()
    {
        return $this->correct;
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return Record
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
     * Set organisationunit
     *
     * @param Organisationunit $organisationunit
     * @return Record
     */
    public function setOrganisationunit(Organisationunit $organisationunit = null)
    {
        $this->organisationunit = $organisationunit;
    
        return $this;
    }

    /**
     * Get organisationunit
     *
     * @return Organisationunit
     */
    public function getOrganisationunit()
    {
        return $this->organisationunit;
    }

    /**
     * Set form
     *
     * @param Form $form
     * @return Record
     */
    public function setForm(Form $form)
    {
        $this->form = $form;
    
        return $this;
    }

    /**
     * Get form
     *
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return RecordStats
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return RecordStats
     */
    public function setCount($count)
    {
        $this->count = $count;
    
        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }
    
    /**
     * Increment count
     *
     * @param integer $count
     * @return RecordStats
     */
    public function incrementCount($count=1)
    {
    	$this->count += $count;
    
    	return $this;
    }
    
    /**
     * Decrement count
     *
     * @param integer $count
     * @return RecordStats
     */
    public function decrementCount($count=1)
    {
    	$this->count -= $count;
    
    	return $this;
    }

    /**
     * Set field
     *
     * @param Field $field
     * @return RecordStats
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
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        $recordDetail = 'Organisationunit:'.$this->getOrganisationunit()->__toString().' Form:'.$this->getForm()->__toString().' Field:'.$this->getField()->__toString().' Value:'.$this->getValue().' Count:'.$this->getCount();
        return $recordDetail;
    }
}