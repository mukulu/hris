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
namespace Hris\MessageBundle\FormModel;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * Class for handling multiple recipients in thread
 */
class NewThreadMultipleMessage extends AbstractMessage
{
    /**
     * The user who receives the message
     *
     * @var ArrayCollection
     */
    protected $recipients;

    /**
     * The thread subject
     *
     * @var string
     */
    protected $subject;

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return null
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return ArrayCollection
     */
    public function getRecipients()
    {
        if ($this->recipients === null) {
            $this->recipients = new ArrayCollection();
        }

        return $this->recipients;
    }

    /**
     * Adds single recipient to collection
     *
     * @param ParticipantInterface $recipient
     *
     * @return null
     */
    public function addRecipient(ParticipantInterface $recipient)
    {
        if (!$this->recipients->contains($recipient)) {
            $this->recipients->add($recipient);
        }
    }


    /**
     * Removes recipient from collection
     *
     * @param ParticipantInterface $recipient
     *
     * @return null
     *
     */
    public function removeRecipient(ParticipantInterface $recipient)
    {
        $this->recipients->removeElement($recipient);
    }

}
