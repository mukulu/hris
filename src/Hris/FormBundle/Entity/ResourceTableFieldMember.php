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
use Hris\FormBundle\Entity\ResourceTable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\FormBundle\Entity\ResourceTableFieldMember
 *
 * @ORM\Table(name="hris_resourcetable_fieldmembers")
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\ResourceTableFieldMemberRepository")
 */
class ResourceTableFieldMember
{
	/**
	 * @var ResourceTable $resourceTable
	 *
	 * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\ResourceTable",inversedBy="resourceTableFieldMember")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="resourcetable_id", referencedColumnName="id",nullable=false, onDelete="CASCADE")
	 * })
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="NONE")
	 */
	private $resourceTable;
	
	/**
	 * @var Field $field
	 *
	 * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\Field",inversedBy="resourceTableFieldMember")
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


    /**
     * Set sort
     *
     * @param integer $sort
     * @return ResourceTableFieldMember
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
     * Set resourceTable
     *
     * @param ResourceTable $resourceTable
     * @return ResourceTableFieldMember
     */
    public function setResourceTable(ResourceTable $resourceTable)
    {
        $this->resourceTable = $resourceTable;
    
        return $this;
    }

    /**
     * Get resourceTable
     *
     * @return ResourceTable
     */
    public function getResourceTable()
    {
        return $this->resourceTable;
    }

    /**
     * Set field
     *
     * @param Field $field
     * @return ResourceTableFieldMember
     */
    public function setField(Field $field)
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
        $resourceTableMember = 'Resourcetable:'.$this->getResourceTable()->__toString().' Field:'.$this->getField()->__toString();
        return $resourceTableMember;
    }
}