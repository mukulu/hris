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
namespace Hris\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\MessageBundle\Entity\ThreadMetadata as BaseThreadMetadata;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * Hris\MessageBundle\Entity\ThreadMetadata
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="hris_message_thread_metadata")
 * @ORM\Entity(repositoryClass="Hris\MessageBundle\Entity\ThreadMetadataRepository")
 */
class ThreadMetadata extends BaseThreadMetadata
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $uid
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="uid", type="string", length=13, unique=true)
     */
    protected $uid;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\MessageBundle\Entity\Thread", inversedBy="metadata")
     * @ORM\JoinColumn(name="thread_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $thread;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="participant_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $participant;

    /**
     * @var \DateTime $datecreated
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="datecreated", type="datetime")
     */
    protected $datecreated;

    /**
     * @var \DateTime $lastupdated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="lastupdated", type="datetime", nullable=true)
     */
    protected $lastupdated;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->uid = uniqid();
        $this->datecreated = new \DateTime('now');
    }

    /**
     * set Thread
     * @param ThreadInterface $thread
     * @return null|void
     */
    public function setThread(ThreadInterface $thread) {
        $this->thread = $thread;
    }

    /**
     * set Participant
     * @param ParticipantInterface $participant
     * @return $this|null
     */
    public function setParticipant(ParticipantInterface $participant) {
        $this->participant = $participant;
        return $this;
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
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return ThreadMetadata
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
     * @return ThreadMetadata
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
     * @return ThreadMetadata
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
}
