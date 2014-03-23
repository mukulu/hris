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
namespace Hris\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Hris\UserBundle\Entity\Group;
use FOS\MessageBundle\Model\ParticipantInterface;
use Hris\FormBundle\Entity\Form;
use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Hris\UserBundle\Entity\User
 *
 * @ORM\Table(name="hris_user")
 * @ORM\Entity(repositoryClass="Hris\UserBundle\Entity\UserRepository")
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @UniqueEntity(fields={"email"}, groups={"registration"}, message="Email exists")
 * @UniqueEntity(fields={"username"}, groups={"registration"}, message="Username exists")
 */
class User extends BaseUser implements ParticipantInterface
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $uid
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="uid", type="string", length=13, unique=true)
     */
    private $uid;

    /**
     * @var string $description
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string $username
     * @Gedmo\Versioned
     * @Assert\NotBlank(groups={"registration"})
     */
    protected $username;

    /**
     * @var string
     * @Gedmo\Versioned
     */
    protected $usernameCanonical;

    /**
     * @var string
     * @Gedmo\Versioned
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Email(groups={"registration"})
     */
    protected $email;

    /**
     * @var string
     * @Gedmo\Versioned
     */
    protected $emailCanonical;

    /**
     * @var boolean
     * @Gedmo\Versioned
     */
    protected $enabled;

    /**
     * The salt to use for hashing
     *
     * @var string
     * @Gedmo\Versioned
     */
    protected $salt;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     * @Gedmo\Versioned
     */
    protected $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     * @Gedmo\Versioned
     */
    protected $plainPassword;

    /**
     * @var string $phonenumber
     *
     * @ORM\Column(name="phonenumber", type="string", length=64, nullable=true)
     */
    private $phonenumber;

    /**
     * @var string $jobTitle
     *
     * @ORM\Column(name="jobTitle", type="string", length=64, nullable=true)
     */
    private $jobTitle;

    /**
     * @var string $firstName
     *
     * @ORM\Column(name="firstName", type="string", length=64, nullable=true)
     */
    private $firstName;

    /**
     * @var string $middleName
     *
     * @ORM\Column(name="middleName", type="string", length=64, nullable=true)
     */
    private $middleName;

    /**
     * @var string $surname
     *
     * @ORM\Column(name="surname", type="string", length=64, nullable=true)
     */
    private $surname;

    /**
     * @var Organisationunit $organisationunit
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\OrganisationunitBundle\Entity\Organisationunit",inversedBy="user")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organisationunit_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $organisationunit;

    /**
     * @var Group $groups
     *
     * @ORM\ManyToMany(targetEntity="Hris\UserBundle\Entity\Group", inversedBy="users")
     * @ORM\JoinTable(name="hris_user_group_members",
     *   joinColumns={
	 *     @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
	 *   },
     *   inverseJoinColumns={
	 *     @ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")
	 *   }
     * )
     */
    protected $groups;

    /**
     * @var Form $form
     *
     * @ORM\ManyToMany(targetEntity="Hris\FormBundle\Entity\Form", inversedBy="user")
     * @ORM\JoinTable(name="hris_user_formmembers",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="form_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $form;

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
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->uid = uniqid();
        $this->groups = new ArrayCollection();
        $this->form = new ArrayCollection();
        if(empty($this->datecreated))
        {
            $this->datecreated = new \DateTime('now');
        }
        if(empty($this->expiresAt)) {
            $this->expiresAt = new \DateTime('now +1 year');
        }
        if(empty($this->credentialsExpireAt)) {
            $this->credentialsExpireAt = new \DateTime('now +1 year');
        }
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
     * @return Organisationunit
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
     * Set lastupdated
     *
     * @param \DateTime $lastupdated
     * @return User
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
     * @return User
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
     * Get deletedAt
     *
     * @return \DateTime $deletedAt
     */
    public function getDeletedAt()
    {
    	return $this->deletedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
    	$this->deletedAt = $deletedAt;
    }

    /**
     * Get expiresAt
     *
     * @return \DateTime $expiresAt
     */
    public function getExpiresAt()
    {
    	return $this->expiresAt;
    }

    /**
     * Get credentialsExpireAt
     *
     * @return \DateTime $credentialsExpireAt
     */
    public function getCredentialsExpireAt()
    {
    	return $this->credentialsExpireAt;
    }

    /**
     * Get Entity verbose name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->username;
    }

    /**
     * Set organisationunit
     *
     * @param \Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit
     * @return User
     */
    public function setOrganisationunit(\Hris\OrganisationunitBundle\Entity\Organisationunit $organisationunit = null)
    {
        $this->organisationunit = $organisationunit;

        $organisationunit->addUser($this);

        return $this;
    }

    /**
     * Get organisationunit
     *
     * @return \Hris\OrganisationunitBundle\Entity\Organisationunit
     */
    public function getOrganisationunit()
    {
        return $this->organisationunit;
    }

    /**
     * Add form
     *
     * @param Form $form
     * @return User
     */
    public function addForm(Form $form)
    {
        $this->form[$form->getId()] = $form;

        return $this;
    }

    /**
     * Remove form
     *
     * @param Form $form
     */
    public function removeForm(Form $form)
    {
        $this->form->removeElement($form);
    }

    /**
     * Get form
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Add groups
     *
     * @param Group $groups
     * @return User
     */
    public function addGroups(Group $groups)
    {
        $this->groups[$groups->getId()] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param Group $groups
     */
    public function removeGroups(Group $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Set phonenumber
     *
     * @param string $phonenumber
     * @return User
     */
    public function setPhonenumber($phonenumber)
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    /**
     * Get phonenumber
     *
     * @return string
     */
    public function getPhonenumber()
    {
        return $this->phonenumber;
    }

    /**
     * Set jobTitle
     *
     * @param string $jobTitle
     * @return User
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    /**
     * Get jobTitle
     *
     * @return string
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     * @return User
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Group
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
}
