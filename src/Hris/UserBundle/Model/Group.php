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

abstract class Group implements GroupInterface
{
    protected $id;
    protected $name;
    protected $roles;
    protected $users;

    public function __construct($name, $roles = array(), $users = array())
    {
        $this->name = $name;
        $this->roles = $roles;
        $this->users = $users;
    }

    /**
     * @param string $role
     *
     * @return Group
     */
    public function addRole($role)
    {
        if (!$this->hasRole($role)) {
            $this->roles[] = strtoupper($role);
        }

        return $this;
    }

    /**
     * @param string $user
     *
     * @return Group
     */
    public function addUser($user)
    {
        if (!$this->hasUser($user)) {
            $this->users[] = strtoupper($user);
        }

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $role
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->roles, true);
    }

    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param string $role
     *
     * @return Group
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * @param string $user
     */
    public function hasUser($user)
    {
        return in_array(strtoupper($user), $this->users, true);
    }

    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param string $user
     *
     * @return Group
     */
    public function removeUser($user)
    {
        if (false !== $key = array_search(strtoupper($user), $this->users, true)) {
            unset($this->users[$key]);
            $this->users = array_values($this->users);
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return Group
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param array $roles
     *
     * @return Group
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param array $users
     *
     * @return Group
     */
    public function setUsers(array $users)
    {
        $this->users = $users;

        return $this;
    }
}
