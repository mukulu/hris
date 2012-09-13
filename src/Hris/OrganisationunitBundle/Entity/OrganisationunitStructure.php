<?php

namespace Hris\OrganisationunitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hris\OrganisationunitBundle\Entity\OrganisationunitStructure
 *
 * @ORM\Table(name="hris_organiationunitstructure")
 * @ORM\Entity(repositoryClass="Hris\OrganisationunitBundle\Entity\OrganisationunitStructureRepository")
 */
class OrganisationunitStructure
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
     * @var integer $level
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;


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
     * Set level
     *
     * @param integer $level
     * @return OrganisationunitStructure
     */
    public function setLevel($level)
    {
        $this->level = $level;
    
        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }
}