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
use Hris\OrganisationunitBundle\Entity\OrganisationunitGroupset;
use Symfony\Component\Stopwatch\Stopwatch;

class LoadOrganisationunitGroupData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Dummy organisationunit Groups
     * 
     * @var organisationunitGroups
     */
    private $organisationunitGroups;

    /**
     * Returns Array of organisationunit Group fixtures
     *
     * @return mixed
     */
    public function getOrganisationunitGroups()
    {
        return $this->organisationunitGroups;
    }

    /**
     * Dummy organisationunit Groupsets
     *
     * @var organisationunitGroupsets
     */
    private $organisationunitGroupsets;

    /**
     * Returns Array of organisationunit Groupset fixtures
     *
     * @return mixed
     */
    public function getOrganisationunitGroupsets()
    {
        return $this->organisationunitGroupsets;
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
                'description'=>'Dispensaries'),
            1=>Array(
                'code'=>'healthcentres',
                'name'=>'Health Centres',
                'groupset'=>'type',
                'description'=>'Health Centres'),
            2=>Array(
                'code'=>'hospitals',
                'name'=>'Hospitals',
                'groupset'=>'type',
                'description'=>'Hospitals'),
            4=>Array(
                'code'=>'private',
                'name'=>'Private Facilities',
                'groupset'=>'ownership',
                'description'=>'Private Facilities'),
            5=>Array(
                'code'=>'public',
                'name'=>'Public Facilities',
                'groupset'=>'ownership',
                'description'=>'Private Facilities'),
            6=>Array(
                'code'=>'faithbased',
                'name'=>'Faith Based Facilities',
                'groupset'=>'ownership',
                'description'=>'Faith Based Facilities'),
            7=>Array(
                'code'=>'army',
                'name'=>'Army Facilities',
                'groupset'=>'ownership',
                'description'=>'Faith Based Facilities'),
        );
        return $this->organisationunitGroups;
    }

    /**
     * Returns Array of dummy organisationunitGroups
     *
     * @return array
     */
    public function addDummyOrganisationunitGroupsets()
    {
        // Load Public Data
        $this->organisationunitGroupsets = Array(
            0=>Array(
                'code'=>'type',
                'name'=>'Type',
                'description'=>'Group of Health facilities by type services offered and human resource available'),
            1=>Array(
                'code'=>'ownership',
                'name'=>'Ownership',
                'description'=>'Group of Health facilities by ownership/administration managing the facility'),
        );
        return $this->organisationunitGroupsets;
    }

    /**
     * Loads metadata into the database
     *
     * @param ObjectManager $manager
     */

    public function load(ObjectManager $manager)
	{
        $stopwatch = new Stopwatch();
        $stopwatch->start('dummyOrganisationGroupGeneration');

        $this->addDummyOrganisationunitGroups();
        $this->addDummyOrganisationunitGroupsets();
        $organiastionunits = $manager->getRepository('HrisOrganisationunitBundle:Organisationunit')->findAll();

        // Populate dummy organisationunitGroupset
        foreach($this->organisationunitGroupsets as $organisationunitGroupsetKey=>$humanResourceOrganisationunitGroupset) {
            $organisationunitGroupset = new OrganisationunitGroupset();
            $organisationunitGroupset->setCode($humanResourceOrganisationunitGroupset['code']);
            $organisationunitGroupset->setName($humanResourceOrganisationunitGroupset['name']);
            $organisationunitGroupset->setDescription($humanResourceOrganisationunitGroupset['description']);
            $organisationunitGroupsetReference = strtolower(str_replace(' ','',$humanResourceOrganisationunitGroupset['code'])).'-organisationunitgroupset';
            $this->addReference($organisationunitGroupsetReference, $organisationunitGroupset);
            $manager->persist($organisationunitGroupset);
        }

        // Populate dummy organisationunitGroups
		foreach($this->organisationunitGroups as $organisationunitGroupKey=>$humanResourceOrganisationunitGroup) {
			$organisationunitGroup = new OrganisationunitGroup();
            $organisationunitGroup->setCode($humanResourceOrganisationunitGroup['code']);
            $organisationunitGroup->setName($humanResourceOrganisationunitGroup['name']);
            $organisationunitGroup->setDescription($humanResourceOrganisationunitGroup['description']);
            $organisationunitGroupsetByReference = $manager->merge($this->getReference( strtolower(str_replace(' ','',$humanResourceOrganisationunitGroup['groupset'])).'-organisationunitgroupset' ));
            $organisationunitGroup->setOrganisationunitGroupset($organisationunitGroupsetByReference);
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

        /*
         * Check Clock for time spent
         */
        $dummyOrganisationGroupGenerationTime = $stopwatch->stop('dummyOrganisationGroupGeneration');
        $duration = $dummyOrganisationGroupGenerationTime->getDuration()/1000;
        unset($stopwatch);
        if( $duration <60 ) {
            $durationMessage = round($duration,2).' seconds';
        }elseif( $duration >= 60 && $duration < 3600 ) {
            $durationMessage = round(($duration/60),2) .' minutes';
        }elseif( $duration >=3600 && $duration < 216000) {
            $durationMessage = round(($duration/3600),2) .' hours';
        }else {
            $durationMessage = round(($duration/86400),2) .' hours';
        }
        echo "Dummy Organisationunit Group generation complete in ". $durationMessage .".\n\n";
	}
	
	/**
     * The order in which this fixture will be loaded
	 * @return integer
	 */
	public function getOrder()
	{
        //LoadOrganisationunit preceeds
		return 8;
        //LoadValidation follows
	}

}
