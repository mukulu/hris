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

use Hris\FormBundle\Entity\FriendlyReport;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hris\FormBundle\Entity\ArithmeticFilter
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="hris_arithmeticfilter", uniqueConstraints={@ORM\UniqueConstraint(name="arithmetic_filter_idx",columns={"operator", "leftExpression","rightExpression"})})
 * @ORM\Entity(repositoryClass="Hris\FormBundle\Entity\ArithmeticFilterRepository")
 */
class ArithmeticFilter
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
     * @var string $description
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string $operator
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="operator", type="string",length=10)
     */
    private $operator;

    /**
     * @var string $leftExpression
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="leftExpression", type="string", length=255)
     */
    private $leftExpression;

    /**
     * @var string $rightExpression
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="rightExpression", type="string", length=255)
     */
    private $rightExpression;
    
    /**
     * @var FriendlyReport $friendlyReport
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\FriendlyReport", mappedBy="arithmeticFilter")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $friendlyReport;

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
     * @return ArithmeticFilter
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
     * @return ArithmeticFilter
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
     * Set operator
     *
     * @param string $operator
     * @return ArithmeticFilter
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    
        return $this;
    }

    /**
     * Get operator
     *
     * @return string 
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set leftExpression
     *
     * @param string $leftExpression
     * @return ArithmeticFilter
     */
    public function setLeftExpression($leftExpression)
    {
        $this->leftExpression = $leftExpression;
    
        return $this;
    }

    /**
     * Get leftExpression
     *
     * @return string 
     */
    public function getLeftExpression()
    {
        return $this->leftExpression;
    }

    /**
     * Set rightExpression
     *
     * @param string $rightExpression
     * @return ArithmeticFilter
     */
    public function setRightExpression($rightExpression)
    {
        $this->rightExpression = $rightExpression;
    
        return $this;
    }

    /**
     * Get rightExpression
     *
     * @return string 
     */
    public function getRightExpression()
    {
        return $this->rightExpression;
    }

    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return ArithmeticFilter
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
     * @return ArithmeticFilter
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
     * Set uid
     *
     * @param string $uid
     * @return ArithmeticFilter
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
     * Constructor
     */
    public function __construct()
    {
        $this->friendlyReport = new ArrayCollection();
        $this->uid = uniqid();
    }
    
    /**
     * Add friendlyReport
     *
     * @param FriendlyReport $friendlyReport
     * @return ArithmeticFilter
     */
    public function addFriendlyReport(FriendlyReport $friendlyReport)
    {
        $this->friendlyReport[$friendlyReport->getId()] = $friendlyReport;
    
        return $this;
    }

    /**
     * Remove friendlyReport
     *
     * @param FriendlyReport $friendlyReport
     */
    public function removeFriendlyReport(FriendlyReport $friendlyReport)
    {
        $this->friendlyReport->removeElement($friendlyReport);
    }

    /**
     * Get friendlyReport
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFriendlyReport()
    {
        return $this->friendlyReport;
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
