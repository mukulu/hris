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
 * TIISEmployeeFieldRelation
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="hris_intergration_tiis_employee_fieldrelation")
 * @ORM\Entity(repositoryClass="Hris\IntergrationBundle\Entity\TIISEmployeeFieldRelationRepository")
 */
class TIISEmployeeFieldRelation
{
    /**
     * @var TIISDataConnection $tiisDataConnection
     *
     * @ORM\ManyToOne(targetEntity="Hris\IntergrationBundle\Entity\TIISDataConnection",inversedBy="tiisEmployeeFieldRelation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tiis_data_connection_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     * @ORM\Id
     */
    private $tiisDataConnection;

    /**
     * @var Field $field
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\Field",inversedBy="tiisEmployeeFieldRelation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="field_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $field;

    /**
     * @var string $columnName
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="columnName", type="string", length=255, nullable=true)
     */
    private $columnName;

}
