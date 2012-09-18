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

use Hris\FormBundle\Entity\FieldOptionGroup;
use Hris\FormBundle\Entity\FriendlyReport;

/**
 * Hris\FormBundle\Entity\FriendlyReportCategory
 *
 * @ORM\Table(name="hris_friendlyreportcategory")
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\FriendlyReportCategoryRepository")
 */
class FriendlyReportCategory
{
    /**
     * @var Hris\FormBundle\Entity\FriendlyReport $friendlyReport
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\FriendlyReport",inversedBy="friendlyReportCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="friendlyreport_id", referencedColumnName="id",nullable=false)
     * })
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $friendlyReport;
    
    /**
     * @var Hris\FormBundle\Entity\FieldOptionGroup $fieldOptionGroup
     *
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\FieldOptionGroup",inversedBy="friendlyReportCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fieldoption_id", referencedColumnName="id",nullable=false)
     * })
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $fieldOptionGroup;

    /**
     * @var integer $sort
     *
     * @ORM\Column(name="sort", type="integer")
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
     * Set sort
     *
     * @param integer $sort
     * @return FriendlyReportCategory
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
     * Set friendlyReport
     *
     * @param Hris\FormBundle\Entity\FriendlyReport $friendlyReport
     * @return FriendlyReportCategory
     */
    public function setFriendlyReport(\Hris\FormBundle\Entity\FriendlyReport $friendlyReport)
    {
        $this->friendlyReport = $friendlyReport;
    
        return $this;
    }

    /**
     * Get friendlyReport
     *
     * @return Hris\FormBundle\Entity\FriendlyReport 
     */
    public function getFriendlyReport()
    {
        return $this->friendlyReport;
    }

    /**
     * Set fieldOptionGroup
     *
     * @param Hris\FormBundle\Entity\FieldOptionGroup $fieldOptionGroup
     * @return FriendlyReportCategory
     */
    public function setFieldOptionGroup(\Hris\FormBundle\Entity\FieldOptionGroup $fieldOptionGroup)
    {
        $this->fieldOptionGroup = $fieldOptionGroup;
    
        return $this;
    }

    /**
     * Get fieldOptionGroup
     *
     * @return Hris\FormBundle\Entity\FieldOptionGroup 
     */
    public function getFieldOptionGroup()
    {
        return $this->fieldOptionGroup;
    }
}