<?php

namespace Hris\DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Hris\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Settings
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="hris_usersettings", uniqueConstraints={@ORM\UniqueConstraint(name="unique_usersettings_idx",columns={"user_id"})})
 * @ORM\Entity(repositoryClass="Hris\DashboardBundle\Entity\SettingsRepository")
 */
class Settings
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
     * @var boolean
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="emailNotification", type="boolean")
     */
    private $emailNotification;

    /**
     * @var boolean
     *
     * @ORM\Column(name="smsNotification", type="boolean")
     */
    private $smsNotification;

    /**
     * @var boolean
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="completenessAlert", type="boolean")
     */
    private $completenessAlert;

    /**
     * @var boolean
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="qualityCheckAlert", type="boolean")
     */
    private $qualityCheckAlert;
    
    /**
     * @var User $user
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\UserBundle\Entity\User",inversedBy="dashboardChart")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $user;
    
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
     * Set uid
     *
     * @param string $uid
     * @return DashboardChart
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
     * Set emailNotification
     *
     * @param boolean $emailNotification
     * @return Settings
     */
    public function setEmailNotification($emailNotification)
    {
        $this->emailNotification = $emailNotification;
    
        return $this;
    }

    /**
     * Get emailNotification
     *
     * @return boolean 
     */
    public function getEmailNotification()
    {
        return $this->emailNotification;
    }

    /**
     * Set smsNotification
     *
     * @param boolean $smsNotification
     * @return Settings
     */
    public function setSmsNotification($smsNotification)
    {
        $this->smsNotification = $smsNotification;
    
        return $this;
    }

    /**
     * Get smsNotification
     *
     * @return boolean 
     */
    public function getSmsNotification()
    {
        return $this->smsNotification;
    }

    /**
     * Set completenessAlert
     *
     * @param boolean $completenessAlert
     * @return Settings
     */
    public function setCompletenessAlert($completenessAlert)
    {
        $this->completenessAlert = $completenessAlert;
    
        return $this;
    }

    /**
     * Get completenessAlert
     *
     * @return boolean 
     */
    public function getCompletenessAlert()
    {
        return $this->completenessAlert;
    }

    /**
     * Set qualityCheckAlert
     *
     * @param boolean $qualityCheckAlert
     * @return Settings
     */
    public function setQualityCheckAlert($qualityCheckAlert)
    {
        $this->qualityCheckAlert = $qualityCheckAlert;
    
        return $this;
    }

    /**
     * Get qualityCheckAlert
     *
     * @return boolean 
     */
    public function getQualityCheckAlert()
    {
        return $this->qualityCheckAlert;
    }
    
    /**
     * Set user
     *
     * @param User $user
     * @return DashboardChart
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return DashboardChart
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
     * @return DashboardChart
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
     * Constructor
     */
    public function __construct()
    {
        $this->uid = uniqid();
    }

    /**
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->user->getUsername().' settings';
    }
}
