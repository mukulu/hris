<?php

namespace Hris\IntergrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DataelementFieldOptionRelation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Hris\IntergrationBundle\Entity\DataelementFieldOptionRelationRepository")
 */
class DataelementFieldOptionRelation
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
     * @var string
     *
     * @ORM\Column(name="dataelementname", type="string", length=255)
     */
    private $dataelementname;

    /**
     * @var string
     *
     * @ORM\Column(name="optioname", type="string", length=64)
     */
    private $optioname;


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
     * Set dataelementname
     *
     * @param string $dataelementname
     * @return DataelementFieldOptionRelation
     */
    public function setDataelementname($dataelementname)
    {
        $this->dataelementname = $dataelementname;
    
        return $this;
    }

    /**
     * Get dataelementname
     *
     * @return string 
     */
    public function getDataelementname()
    {
        return $this->dataelementname;
    }

    /**
     * Set optioname
     *
     * @param string $optioname
     * @return DataelementFieldOptionRelation
     */
    public function setOptioname($optioname)
    {
        $this->optioname = $optioname;
    
        return $this;
    }

    /**
     * Get optioname
     *
     * @return string 
     */
    public function getOptioname()
    {
        return $this->optioname;
    }
}
