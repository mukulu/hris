<?php

namespace Hris\DataQualityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hris\DataQualityBundle\Entity\Validation
 *
 * @ORM\Table(name="hris_validation")
 * @ORM\Entity(repositoryClass="Hris\DataQualityBundle\Entity\ValidationRepository")
 */
class Validation
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string $operator
     *
     * @ORM\Column(name="operator", type="string", length=10)
     */
    private $operator;

    /**
     * @var string $leftExpression
     *
     * @ORM\Column(name="leftExpression", type="text")
     */
    private $leftExpression;

    /**
     * @var string $rightExpression
     *
     * @ORM\Column(name="rightExpression", type="text")
     */
    private $rightExpression;


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
     * @return Validation
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
     * @return Validation
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
     * @return Validation
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
     * @return Validation
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
     * @return Validation
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
}