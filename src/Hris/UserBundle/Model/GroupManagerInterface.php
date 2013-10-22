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

namespace Hris\UserBundle\Model;

/**
 * Interface to be implemented by group managers. This adds an additional level
 * of abstraction between your application, and the actual repository.
 *
 * All changes to groups should happen through this interface.
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
interface GroupManagerInterface
{
    /**
     * Returns an empty group instance.
     *
     * @param string $name
     *
     * @return GroupInterface
     */
    public function createGroup($name);

    /**
     * Deletes a group.
     *
     * @param GroupInterface $group
     *
     * @return void
     */
    public function deleteGroup(GroupInterface $group);

    /**
     * Finds one group by the given criteria.
     *
     * @param array $criteria
     *
     * @return GroupInterface
     */
    public function findGroupBy(array $criteria);

    /**
     * Finds a group by name.
     *
     * @param string $name
     *
     * @return GroupInterface
     */
    public function findGroupByName($name);

    /**
     * Returns a collection with all user instances.
     *
     * @return \Traversable
     */
    public function findGroups();

    /**
     * Returns the group's fully qualified class name.
     *
     * @return string
     */
    public function getClass();

    /**
     * Updates a group.
     *
     * @param GroupInterface $group
     *
     * @return void
     */
    public function updateGroup(GroupInterface $group);
}
