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
 * Hris\IntergrationBundle\Entity\DHISDataConnection
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="hris_intergration_dhis_data_connection",uniqueConstraints={@ORM\UniqueConstraint(name="unique_serverdatasetname_idx", columns={"host_url","dataset_name"}),@ORM\UniqueConstraint(name="unique_serverdatasetuid_idx", columns={"host_url", "dataset_uid"})})
 * @ORM\Entity(repositoryClass="Hris\IntergrationBundle\Entity\DHISDataConnectionRepository")
 */
class DHISDataConnection
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="name", type="string", length=64, nullable=false)
     */
    private $name;

    /**
     * @var string $datasetName
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="dataset_name", type="string", length=64, nullable=false)
     */
    private $datasetName;

    /**
     * @var string $datasetUid
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="dataset_uid", type="string", length=64, nullable=false)
     */
    private $datasetUid;

    /**
     * @var string $hostUrl
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="host_url", type="string", length=255, nullable=false)
     */
    private $hostUrl;

    /**
     * @var string $username
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="username", type="string", length=64, nullable=false)
     */
    private $username;

    /**
     * @var string $password
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="password", type="string", length=64, nullable=false)
     */
    private $password;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FieldOptionGroupset", inversedBy="dhisDataConnection")
     * @ORM\JoinTable(name="hris_intergration_fieldoptiongroupset_member",
     *   joinColumns={
     *     @ORM\JoinColumn(name="dhis_data_connection_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="field_option_groupset_id", referencedColumnName="id")
     *   }
     * )
     */
    private $fieldOptionGroupset;

    /**
     * @var Organisationunit $organisationunit
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="dhisDataConnection")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_organisationunit_id", referencedColumnName="id")
     * })
     */
    private $parentOrganisationunit;


    public function __construct()
    {
        $this->fieldOptionGroupset = new \Doctrine\Common\Collections\ArrayCollection();
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
}
