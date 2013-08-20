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
namespace Hris\FormBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Hris\OrganisationunitBundle\Entity\OrganisationunitGroup;

class LoadOrganisationunitGroupData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Dummy organisationunit Groups
     * 
     * @var organisationunitGroups
     */
    private $organisationunitGroups;

    /**
     * Returns Array of organisationunit fixtures
     *
     * @return mixed
     */
    public function getOrganisationunitGroups()
    {
        return $this->organisationunitGroups;
    }


    /**
     * Returns Array of dummy organisationunitGroups
     *
     * @return array
     */
    public function addDummyOrganisationunitGroups()
    {
        // Load Public Data
        $this->organisationunitGroups = Array(
            0=>Array(
                'code'=>'dispensaries',
                'name'=>'Dispensaries',
                'groupset'=>'type',
                'search'=>'dispensary',
                'description'=>'Dispensaries'),
            1=>Array(
                'code'=>'healthcentres',
                'name'=>'Health Centres',
                'groupset'=>'type',
                'search'=>'health centre',
                'description'=>'Health Centres'),
            2=>Array(
                'code'=>'hospitals',
                'name'=>'Hospitals',
                'groupset'=>'hospital',
                'search'=>'hospital',
                'description'=>'Hospitals'),
            4=>Array(
                'code'=>'private',
                'name'=>'Private Facilities',
                'groupset'=>'ownership',
                'search'=>'private',
                'description'=>'Private Facilities'),
            5=>Array(
                'code'=>'public',
                'name'=>'Public Facilities',
                'groupset'=>'ownership',
                'search'=>'private',
                'description'=>'Private Facilities'),
            6=>Array(
                'code'=>'faithbased',
                'name'=>'Faith Based Facilities',
                'groupset'=>'ownership',
                'search'=>'fbo',
                'description'=>'Faith Based Facilities'),
            7=>Array(
                'code'=>'army',
                'name'=>'Army Facilities',
                'groupset'=>'ownership',
                'search'=>'fbo',
                'description'=>'Faith Based Facilities'),
        );
        return $this->organisationunitGroups;
    }

    /**
     * Loads metadata into the database
     *
     * @param ObjectManager $manager
     */

    public function load(ObjectManager $manager)
	{
        $this->addDummyOrganisationunitGroups();
        $organiastionunits = $manager->getRepository('HrisOrganisationunitBundle:Organisationunit')->findAll();

        // Populate dummy organisationunitGroups
		foreach($this->organisationunitGroups as $organisationunitGroupKey=>$humanResourceOrganisationunitGroup) {
			$organisationunitGroup = new OrganisationunitGroup();
            $organisationunitGroup->setCode($humanResourceOrganisationunitGroup['code']);
            $organisationunitGroup->setName($humanResourceOrganisationunitGroup['name']);
            $organisationunitGroup->setDescription($humanResourceOrganisationunitGroup['description']);
            $organisationunitGroupReference = strtolower(str_replace(' ','',$humanResourceOrganisationunitGroup['code'])).'-organisationunitgroup';
            $this->addReference($organisationunitGroupReference, $organisationunitGroup);
            $manager->persist($organisationunitGroup);
        }

        /*
         * Add organisation unit members to ownership and type groupsets
         */
        if(!empty($organiastionunits)) {
            foreach($organiastionunits as $organiastionunitKey=>$organisationunit) {
                /*
                 * Assign type of facility based on their names
                 */
                // Appending dispensaries to dispensary group
                if( preg_match('/dispensary/i',$organisationunit->getLongname()) ) {
                    $organisationunitGroupCode = 'hospitals';
                    $organisationunitGroupByReference = $manager->merge($this->getReference( strtolower(str_replace(' ','',$organisationunitGroupCode)).'-organisationunitgroup' ));
                    $organisationunitGroupByReference->addOrganisationunit($organisationunit);
                }
                // Appending hospitals to dispensary group
                if( preg_match('/hospital/i',$organisationunit->getLongname()) ) {
                    $organisationunitGroupCode = 'dispensaries';
                    $organisationunitGroupByReference = $manager->merge($this->getReference( strtolower(str_replace(' ','',$organisationunitGroupCode)).'-organisationunitgroup' ));
                    $organisationunitGroupByReference->addOrganisationunit($organisationunit);
                }
                // Appending healthcentres to dispensary group
                if( preg_match('/health centre/i',$organisationunit->getLongname()) ) {
                    $organisationunitGroupCode = 'healthcentres';
                    $organisationunitGroupByReference = $manager->merge($this->getReference( strtolower(str_replace(' ','',$organisationunitGroupCode)).'-organisationunitgroup' ));
                    $organisationunitGroupByReference->addOrganisationunit($organisationunit);
                }

                /*
                 * Assign ownership randomly by chance.
                 */
                $ownershipOrganisationunitGroup = Array(
                    4=>Array(
                        'code'=>'private',
                        'name'=>'Private Facilities',
                        'groupset'=>'ownership',
                        'search'=>'private',
                        'description'=>'Private Facilities'),
                    5=>Array(
                        'code'=>'public',
                        'name'=>'Public Facilities',
                        'groupset'=>'ownership',
                        'search'=>'private',
                        'description'=>'Private Facilities'),
                    6=>Array(
                        'code'=>'faithbased',
                        'name'=>'Faith Based Facilities',
                        'groupset'=>'ownership',
                        'search'=>'fbo',
                        'description'=>'Faith Based Facilities'),
                    7=>Array(
                        'code'=>'army',
                        'name'=>'Army Facilities',
                        'groupset'=>'ownership',
                        'search'=>'fbo',
                        'description'=>'Faith Based Facilities'),
                );
                $randomOwnershipKey = array_rand($ownershipOrganisationunitGroup,1);
                $organisationunitGroupCode = $ownershipOrganisationunitGroup[$randomOwnershipKey]['code'];
                $organisationunitGroupByReference = $manager->merge($this->getReference( strtolower(str_replace(' ','',$organisationunitGroupCode)).'-organisationunitgroup' ));
                $organisationunitGroupByReference->addOrganisationunit($organisationunit);
                $manager->persist($organisationunit);
            }
        }
		$manager->flush();
	}
	
	/**
     * The order in which this fixture will be loaded
	 * @return integer
	 */
	public function getOrder()
	{
		return 7;
	}

}
