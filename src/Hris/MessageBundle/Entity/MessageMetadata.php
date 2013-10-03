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
use FOS\MessageBundle\Entity\MessageMetadata as BaseMessageMetadata;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * Hris\MessageBundle\Entity\MessageMetadata
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="hris_message_metadata")
 * @ORM\Entity(repositoryClass="Hris\MessageBundle\Entity\MessageMetadataRepository")
 */
class MessageMetadata extends BaseMessageMetadata
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
     * @ORM\ManyToOne(targetEntity="Hris\MessageBundle\Entity\Message", inversedBy="metadata")
     */
    protected $message;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Hris\UserBundle\Entity\User")
     */
    protected $participant;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->uid = uniqid();
        $this->datecreated = new \DateTime('now');
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
     * set Message
     * @param MessageInterface $message
     * @return $this
     */
    public function setMessage(MessageInterface $message) {
        $this->message = $message;
        return $this;
    }

    /**
     * set Participant
     * @param ParticipantInterface $participant
     * @return $this
     */
    public function setParticipant(ParticipantInterface $participant) {
        $this->participant = $participant;
        return $this;
    }

    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     * @return Field
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
     * @return Field
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
