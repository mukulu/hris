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
namespace Hris\IntergrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\IntergrationBundle\Entity\DataelementFieldOptionRelation
 *
 * @ORM\Table(name="hris_intergration_dataelementfieldoption_relation")
 * @ORM\Entity(repositoryClass="Hris\IntergrationBundle\Entity\DataelementFieldOptionRelationRepository")
 */
class DataelementFieldOptionRelation
{
    /**
     * @var DHISDataConnection $dhisDataConnection
     *
     * @ORM\ManyToOne(targetEntity="Hris\IntergrationBundle\Entity\DHISDataConnection",inversedBy="dataelementFieldOptionRelation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dhis_data_connection_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     * @ORM\Id
     */
    private $dhisDataConnection;

    /**
     * @var string $dataelementUid
     *
     * @ORM\Column(name="dataelementUid", type="string", length=16, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $dataelementUid;

    /**
     * @var string $dataelementname
     *
     * @ORM\Column(name="dataelementname", type="string", length=255, nullable=false)
     */
    private $dataelementname;

    /**
     * @var FieldOption $fieldOption
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\FieldOption",inversedBy="dataelementFieldOptionRelation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fieldoption_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $fieldOption;

    /**
     * @var FieldOptionGroup $columnFieldOptionGroup
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\FieldOptionGroup",inversedBy="dataelementFieldOptionRelationColumn")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="column_fieldoption_group_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $columnFieldOptionGroup;

    /**
     * @var FieldOptionGroup $rowFieldOptionGroup
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\FieldOptionGroup",inversedBy="dataelementFieldOptionRelationRow")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="row_fieldoption_group_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $rowFieldOptionGroup;



    /**
     * Set dataelementUid
     *
     * @param string $dataelementUid
     * @return DataelementFieldOptionRelation
     */
    public function setDataelementUid($dataelementUid)
    {
        $this->dataelementUid = $dataelementUid;
    
        return $this;
    }

    /**
     * Get dataelementUid
     *
     * @return string 
     */
    public function getDataelementUid()
    {
        return $this->dataelementUid;
    }

    /**
     * Set dataelementname
     *
     * @param string $dataelementname
     * @return DataelementFieldOptionRelation
     */
    public function setDataelementname($dataelementname)
    {
        $this->dataelementname = $dataelementname;
    
        return $this;
    }

    /**
     * Get dataelementname
     *
     * @return string 
     */
    public function getDataelementname()
    {
        return $this->dataelementname;
    }

    /**
     * Set dhisDataConnection
     *
     * @param \Hris\IntergrationBundle\Entity\DHISDataConnection $dhisDataConnection
     * @return DataelementFieldOptionRelation
     */
    public function setDhisDataConnection(\Hris\IntergrationBundle\Entity\DHISDataConnection $dhisDataConnection)
    {
        $this->dhisDataConnection = $dhisDataConnection;
    
        return $this;
    }

    /**
     * Get dhisDataConnection
     *
     * @return \Hris\IntergrationBundle\Entity\DHISDataConnection 
     */
    public function getDhisDataConnection()
    {
        return $this->dhisDataConnection;
    }

    /**
     * Set fieldOption
     *
     * @param \Hris\FormBundle\Entity\FieldOption $fieldOption
     * @return DataelementFieldOptionRelation
     */
    public function setFieldOption(\Hris\FormBundle\Entity\FieldOption $fieldOption = null)
    {
        $this->fieldOption = $fieldOption;
    
        return $this;
    }

    /**
     * Get fieldOption
     *
     * @return \Hris\FormBundle\Entity\FieldOption 
     */
    public function getFieldOption()
    {
        return $this->fieldOption;
    }

    /**
     * Set columnFieldOptionGroup
     *
     * @param \Hris\FormBundle\Entity\FieldOptionGroup $columnFieldOptionGroup
     * @return DataelementFieldOptionRelation
     */
    public function setColumnFieldOptionGroup(\Hris\FormBundle\Entity\FieldOptionGroup $columnFieldOptionGroup = null)
    {
        $this->columnFieldOptionGroup = $columnFieldOptionGroup;
    
        return $this;
    }

    /**
     * Get columnFieldOptionGroup
     *
     * @return \Hris\FormBundle\Entity\FieldOptionGroup 
     */
    public function getColumnFieldOptionGroup()
    {
        return $this->columnFieldOptionGroup;
    }

    /**
     * Set rowFieldOptionGroup
     *
     * @param \Hris\FormBundle\Entity\FieldOptionGroup $rowFieldOptionGroup
     * @return DataelementFieldOptionRelation
     */
    public function setRowFieldOptionGroup(\Hris\FormBundle\Entity\FieldOptionGroup $rowFieldOptionGroup = null)
    {
        $this->rowFieldOptionGroup = $rowFieldOptionGroup;
    
        return $this;
    }

    /**
     * Get rowFieldOptionGroup
     *
     * @return \Hris\FormBundle\Entity\FieldOptionGroup 
     */
    public function getRowFieldOptionGroup()
    {
        return $this->rowFieldOptionGroup;
    }
}