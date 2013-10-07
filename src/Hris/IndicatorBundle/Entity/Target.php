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
namespace Hris\IndicatorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Hris\FormBundle\Entity\FieldOption;
use Hris\OrganisationunitBundle\Entity\OrganisationunitGroup;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Target
 *
 * @ORM\Table(name="hris_indicator_target")
 * @Gedmo\Loggable
 * @ORM\Entity(repositoryClass="Hris\IndicatorBundle\Entity\TargetRepository")
 */
class Target
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
     * @var string $uid
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="uid", type="string", length=13, unique=true)
     */
    private $uid;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Gedmo\Versioned
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @var string $description
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var TargetFieldOption $targetFieldOption
     *
     * @ORM\OneToMany(targetEntity="Hris\IndicatorBundle\Entity\TargetFieldOption", mappedBy="form",cascade={"ALL"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $targetFieldOption;

    /**
     * @var OrganisationunitGroup $organisationunitGroup
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="\Hris\OrganisationunitBundle\Entity\OrganisationunitGroup",inversedBy="target")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organisationunit_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     *
     */
    private $organisationunitGroup;

    /**
     * @var \DateTime $datecreated
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="datecreated", type="datetime")
     */
    private $datecreated;

    /**
     * @var \DateTime $lastupdated
     * @Gedmo\Timestampable(on="update")
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
     * Set uid
     *
     * @param string $uid
     * @return Target
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
     * Set name
     *
     * @param string $name
     * @return Target
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
     * Set description
     *
     * @param string $description
     * @return Target
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
     * Add targetFieldOption
     *
     * @param TargetFieldOption $targetFieldOption
     * @return Target
     */
    public function addTargetFieldOption(TargetFieldOption $targetFieldOption)
    {
        $this->targetFieldOption[] = $targetFieldOption;

        return $this;
    }

    /**
     * Remove targetFieldOption
     *
     * @param TargetFieldOption $targetFieldOption
     */
    public function removeTargetFieldOption(TargetFieldOption $targetFieldOption)
    {
        $this->targetFieldOption->removeElement($targetFieldOption);
    }

    /**
     * Remove All targetFieldOption
     *
     * @internal param \Hris\FormBundle\Entity\TargetFieldOption $targetFieldOption
     */
    public function removeAllTargetFieldOption()
    {
        foreach($this->targetFieldOption as $key=>$targetFieldOption) {
            $this->targetFieldOption->removeElement($targetFieldOption);
        }
    }

    /**
     * Get targetFieldOption
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTargetFieldOption()
    {
        return $this->targetFieldOption;
    }

    /**
     * Set organisationunitGroup
     *
     * @param OrganisationunitGroup $organisationunitGroup
     * @return Target
     */
    public function setOrganisationunitGroup(OrganisationunitGroup $organisationunitGroup = null)
    {
        $this->organisationunitGroup = $organisationunitGroup;

        return $this;
    }

    /**
     * Get organisationunitGroup
     *
     * @return OrganisationunitGroup
     */
    public function getOrganisationunitGroup()
    {
        return $this->organisationunitGroup;
    }

    /**
     * Set lastupdated
     *
     * @param \DateTime $lastupdated
     * @return Target
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
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return Target
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
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->uid = uniqid();

        $this->targetFieldOption = new ArrayCollection();
    }
}
