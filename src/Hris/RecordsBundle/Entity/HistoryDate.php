<?php

namespace Hris\RecordsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

use Hris\FormBundle\Entity\Field;

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
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="history", type="string", length=32)
     */
    private $history;

    /**
     * @var \DateTime
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="previousdate", type="date")
     */
    private $previousdate;

    /**
     * @var Field $field
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\FormBundle\Entity\Field",inversedBy="historyDate")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="field_id", referencedColumnName="id", onDelete="CASCADE")
     * })
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
     * @return HistoryDate
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
     * Set history
     *
     * @param string $history
     * @return HistoryDate
     */
    public function setHistory($history)
    {
        $this->history = $history;
    
        return $this;
    }

    /**
     * Get history
     *
     * @return string 
     */
    public function getHistory()
    {
        return $this->history;
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
     * @param Field $field
     * @return HistoryDate
     */
    public function setField(Field $field)
    {
        $this->field = $field;
    
        return $this;
    }

    /**
     * Get field
     *
     * @return Field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return HistoryDate
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
     * @return HistoryDate
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
