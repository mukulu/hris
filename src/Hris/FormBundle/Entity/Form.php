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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Hris\DashboardBundle\Entity\DashboardChart;
use Hris\FormBundle\Entity\FormFieldMember;
use Hris\FormBundle\Entity\FormVisibleFields;
use Hris\FormBundle\Entity\Field;
use Hris\RecordsBundle\Entity\Record;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\FormBundle\Entity\Form
 *
 * @Gedmo\Loggable
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
     * @Gedmo\Versioned
     * @ORM\Column(name="uid", type="string", length=13, unique=true)
     */
    private $uid;

    /**
     * @var string $name
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="name", type="string", length=64, unique=true)
     */
    private $name;

    /**
     * @var string $hypertext
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="hypertext", type="text", nullable=true)
     */
    private $hypertext;

    /**
     * @var string $title
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="title", type="string", length=64, nullable=true)
     */
    private $title;
    
    /**
     * @var Field $uniqueRecordFields
     * Fields that together makes a record unique in this form
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\Field", inversedBy="uniqueRecordForms")
     * @ORM\JoinTable(name="hris_form_uniquerecordfields",
     *   joinColumns={
     *     @ORM\JoinColumn(name="form_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="field_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $uniqueRecordFields;
    
    /**
     * @var FormFieldMember $formFieldMember
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\FormFieldMember", mappedBy="form",cascade={"ALL"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $formFieldMember;
    
    /**
     * @var Record $record
     *
     * @ORM\OneToMany(targetEntity="Hris\RecordsBundle\Entity\Record", mappedBy="form",cascade={"ALL"})
     * @ORM\OrderBy({"datecreated" = "ASC"})
     */
    private $record;
    
    /**
     * @var FormVisibleFields $formVisibleFields
     *
     * @ORM\OneToMany(targetEntity="Hris\FormBundle\Entity\FormVisibleFields", mappedBy="form",cascade={"ALL"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $formVisibleFields;
    
    /**
     * @var DashboardChart $dashboardChart
     *
     * @ORM\ManyToMany(targetEntity="Hris\DashboardBundle\Entity\DashboardChart", mappedBy="form",cascade={"ALL"})
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $dashboardChart;

    /**
     * @var \DateTime $datecreated
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="datecreated", type="datetime")
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
     * Add uniqueRecordFields
     *
     * @param Field $uniqueRecordFields
     * @return Form
     */
    public function addUniqueRecordField(Field $uniqueRecordFields)
    {
        $this->uniqueRecordFields[$uniqueRecordFields->getId()] = $uniqueRecordFields;

        $uniqueRecordFields->addUniqueRecordForm($this);
    
        return $this;
    }
    
    /**
     * Remove uniqueRecordFields
     *
     * @param Field $uniqueRecordFields
     */
    public function removeUniqueRecordField(Field $uniqueRecordFields)
    {
        $this->uniqueRecordFields->removeElement($uniqueRecordFields);
    }

    /**
     * Get uniqueRecordFields
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUniqueRecordFields()
    {
        return $this->uniqueRecordFields;
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
     * @param Field $field
     * @return Form
     */
    public function addSimpleField(Field $field)
    {
    	$this->sort += 1;
    	$this->formFieldMember[] = new FormFieldMember($this, $field, $this->sort);
    
    	return $this;
    }
    
    /**
     * Get field
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSimpleField()
    {
        $simpleFields = new ArrayCollection();
        $key = NULL;
    	if(!empty($this->formFieldMember)) {
    		foreach( $this->formFieldMember as $key => $formFieldMember ) {
    			$simpleFields->add($formFieldMember->getField());
    		}
    	}
    	return $simpleFields;
    }
    
    /**
     * Add field
     *
     * @param Field $field
     * @return Form
     */
    public function addSimpleFormVisibleField(Field $field)
    {
    	$this->sort += 1;
    	$this->formVisibleFields[] = new FormVisibleFields($this, $field, $this->sort);
    
    	return $this;
    }
    
    /**
     * Get field
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSimpleFormVisibleFields()
    {
        $simpleFormVisibleFields = new ArrayCollection();
    
    	if(!empty($this->formVisibleFields)) {
    		foreach( $this->formVisibleFields as $key => $formVisibleFields ) {
    			$simpleFormVisibleFields->add($formVisibleFields->getField());
    		}
    	}
    	return $simpleFormVisibleFields;
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

    /**
     * Add formFieldMember
     *
     * @param FormFieldMember $formFieldMember
     * @return Form
     */
    public function addFormFieldMember(FormFieldMember $formFieldMember)
    {
        $this->formFieldMember[] = $formFieldMember;
    
        return $this;
    }

    /**
     * Remove formFieldMember
     *
     * @param FormFieldMember $formFieldMember
     */
    public function removeFormFieldMember(FormFieldMember $formFieldMember)
    {
        $this->formFieldMember->removeElement($formFieldMember);
    }

    /**
     * Remove All formFieldMember
     *
     * @internal param \Hris\FormBundle\Entity\FormFieldMember $formFieldMember
     */
    public function removeAllFormFieldMember()
    {
        foreach($this->formFieldMember as $key=>$formFieldMember) {
            $this->formFieldMember->removeElement($formFieldMember);
        }
    }

    /**
     * Get formFieldMember
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormFieldMember()
    {
        return $this->formFieldMember;
    }

    /**
     * Add dashboardChart
     *
     * @param DashboardChart $dashboardChart
     * @return Form
     */
    public function addDashboardChart(DashboardChart $dashboardChart)
    {
        $this->dashboardChart[$dashboardChart->getId()] = $dashboardChart;
    
        return $this;
    }

    /**
     * Remove dashboardChart
     *
     * @param DashboardChart $dashboardChart
     */
    public function removeDashboardChart(DashboardChart $dashboardChart)
    {
        $this->dashboardChart->removeElement($dashboardChart);
    }

    /**
     * Get dashboardChart
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDashboardChart()
    {
        return $this->dashboardChart;
    }
    

    /**
     * Add record
     *
     * @param Record $record
     * @return Form
     */
    public function addRecord(Record $record)
    {
        $this->record[$record->getId()] = $record;
    
        return $this;
    }

    /**
     * Remove record
     *
     * @param Record $record
     */
    public function removeRecord(Record $record)
    {
        $this->record->removeElement($record);
    }

    /**
     * Get record
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecord()
    {
        return $this->record;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->uniqueRecordFields = new ArrayCollection();
        $this->formFieldMember = new ArrayCollection();
        $this->record = new ArrayCollection();
        $this->formVisibleFields = new ArrayCollection();
        $this->dashboardChart = new ArrayCollection();
        $this->uid = uniqid();

        if(empty($this->datecreated))
        {
            $this->datecreated = new \DateTime('now');
        }
    }
    
    /**
     * Add formVisibleFields
     *
     * @param FormVisibleFields $formVisibleFields
     * @return Form
     */
    public function addFormVisibleField(FormVisibleFields $formVisibleFields)
    {
        $this->formVisibleFields[] = $formVisibleFields;
    
        return $this;
    }

    /**
     * Remove formVisibleField
     *
     * @param FormVisibleFields $formVisibleField
     */
    public function removeFormVisibleField(FormVisibleFields $formVisibleField)
    {
        $this->formVisibleFields->removeElement($formVisibleField);
    }

    /**
     * Remove All formVisibleField
     *
     * @internal param \Hris\FormBundle\Entity\FormVisibleFields $formVisibleField
     */
    public function removeAllFormVisibleFields()
    {
        foreach($this->formVisibleFields as $key=>$formVisibleField) {
            $this->formVisibleFields->removeElement($formVisibleField);
        }
    }

    /**
     * Get formVisibleFields
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormVisibleFields()
    {
        return $this->formVisibleFields;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Form
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
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