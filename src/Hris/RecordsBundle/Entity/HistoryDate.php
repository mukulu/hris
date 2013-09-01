<?php

namespace Hris\RecordsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * HistoryDate
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="hris_record_history_date")
 * @ORM\Entity(repositoryClass="Hris\RecordsBundle\Entity\HistoryDateRepository")
 */
class HistoryDate
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
     * @Gedmo\Versioned
     * @ORM\Column(name="instance", type="string", length=64)
     */
    private $instance;

    /**
     * @var integer
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="historyInteger", type="integer")
     */
    private $historyInteger;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="historyString", type="string", length=32)
     */
    private $historyString;

    /**
     * @var \DateTime
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="previousdate", type="date")
     */
    private $previousdate;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="field", type="string", length=32)
     */
    private $field;

    /**
     * @var \DateTime $datecreated
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="datecreated", type="datetime", nullable=false)
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
     * Constructor
     */
    public function __construct()
    {
        $this->uid = uniqid();
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

    /**
     * Set uid
     *
     * @param string $uid
     * @return History
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
     * Set instance
     *
     * @param string $instance
     * @return HistoryDate
     */
    public function setInstance($instance)
    {
        $this->instance = $instance;
    
        return $this;
    }

    /**
     * Get instance
     *
     * @return string 
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Set historyInteger
     *
     * @param integer $historyInteger
     * @return HistoryDate
     */
    public function setHistoryInteger($historyInteger)
    {
        $this->historyInteger = $historyInteger;
    
        return $this;
    }

    /**
     * Get historyInteger
     *
     * @return integer 
     */
    public function getHistoryInteger()
    {
        return $this->historyInteger;
    }

    /**
     * Set historyString
     *
     * @param string $historyString
     * @return HistoryDate
     */
    public function setHistoryString($historyString)
    {
        $this->historyString = $historyString;
    
        return $this;
    }

    /**
     * Get historyString
     *
     * @return string 
     */
    public function getHistoryString()
    {
        return $this->historyString;
    }

    /**
     * Set previousdate
     *
     * @param \DateTime $previousdate
     * @return HistoryDate
     */
    public function setPreviousdate($previousdate)
    {
        $this->previousdate = $previousdate;
    
        return $this;
    }

    /**
     * Get previousdate
     *
     * @return \DateTime 
     */
    public function getPreviousdate()
    {
        return $this->previousdate;
    }

    /**
     * Set field
     *
     * @param string $field
     * @return HistoryDate
     */
    public function setField($field)
    {
        $this->field = $field;
    
        return $this;
    }

    /**
     * Get field
     *
     * @return string 
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return History
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
     * @return History
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
}
