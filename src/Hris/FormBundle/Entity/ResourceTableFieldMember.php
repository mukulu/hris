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

/**
 * Hris\FormBundle\Entity\ResourceTableFieldMember
 *
 * @ORM\Table(name="hris_resourcetable_fieldmembers")
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\ResourceTableFieldMemberRepository")
 */
class ResourceTableFieldMember
{
	/**
	 * @var \Hris\FormBundle\Entity\ResourceTable $resourceTable
	 *
	 * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\ResourceTable",inversedBy="resourceTableFieldMember")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="resourcetable_id", referencedColumnName="id",nullable=false)
	 * })
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="NONE")
	 */
	private $resourceTable;
	
	/**
	 * @var \Hris\FormBundle\Entity\Field $field
	 *
	 * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\Field",inversedBy="resourceTableFieldMember")
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
     * @param \Hris\FormBundle\Entity\ResourceTable $resourceTable
     * @return ResourceTableFieldMember
     */
    public function setResourceTable(\Hris\FormBundle\Entity\ResourceTable $resourceTable)
    {
        $this->resourceTable = $resourceTable;
    
        return $this;
    }

    /**
     * Get resourceTable
     *
     * @return \Hris\FormBundle\Entity\ResourceTable
     */
    public function getResourceTable()
    {
        return $this->resourceTable;
    }

    /**
     * Set field
     *
     * @param \Hris\FormBundle\Entity\Field $field
     * @return ResourceTableFieldMember
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