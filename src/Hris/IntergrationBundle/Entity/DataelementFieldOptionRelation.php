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


}
