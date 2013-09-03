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
namespace Hris\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Hris\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface 
{
    /**
     * @var users
     */
    private $users;


    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Returns Array of dummy Users
     *
     * @return array
     */
    public function addDummyUsers()
    {
        $this->users = Array(
            0=> Array(
                'username'=>'admin',
                'password'=>'district',
                'email'=>'admin@localhost.local',
                'role'=>'ROLE_SUPERUSER',
                'enabled'=>True,
            ),
            1=> Array(
                'username'=>'district',
                'password'=>'district',
                'email'=>'district@localhost.local',
                'role'=>'ROLE_USER',
                'enabled'=>True,
            )
        );
        return $this->users;
    }

	/**
	 * {@inheritDoc}
	 * @see Doctrine\Common\DataFixtures.FixtureInterface::load()
	 */
	public function load(ObjectManager $manager)
	{
        $this->addDummyUsers();
        foreach($this->getUsers() as $userKey=>$humanResourceUser) {
            $user = new User();
            $user->setUsername($humanResourceUser['username']);
            $user->setPlainPassword($humanResourceUser['password']);
            $user->setEmail($humanResourceUser['email']);
            $user->addRole($humanResourceUser['role']);
            $user->setEnabled($humanResourceUser['enabled']);
            $this->addReference($user->getUsername().'-user', $user);

            $manager->persist($user);

        }

		$manager->flush();
		

	}
	
	/**
     * The order in which this fixture will be loaded
	 * @return integer
	 */
	public function getOrder()
	{
		return 1;
	}


}
