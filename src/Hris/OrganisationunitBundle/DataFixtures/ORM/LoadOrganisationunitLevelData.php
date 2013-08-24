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

use Doctrine\Tests\Common\Annotations\True;
use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Hris\OrganisationunitBundle\Entity\OrganisationunitLevel;
use Hris\OrganisationunitBundle\Entity\OrganisationunitStructure;

class LoadOrganisationunitLevelData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Dummy organisationunitLevels
     * 
     * @var organisationunitLevels
     */
    private $organisationunitLevels;

    /**
     * Returns Array of organisationunitlevel fixtures
     *
     * @return mixed
     */
    public function getOrganisationunitLevels()
    {
        return $this->organisationunitLevels;
    }

    /**
     * Returns Array of dummy organisationunits
     *
     * @return array
     */
    public function addDummyOrganisationunitLevels()
    {
        // Load Public Data
        $this->organisationunitLevels = Array(
            0=>Array(
                'name'=>'Level 1',
                'level'=>1,
                'description'=>'Highest level of organisation unit tree',
                'dataentrylevel'=>False),
            1=>Array(
                'name'=>'Level 2',
                'level'=>2,
                'description'=>'Divisions Level of Ministry',
                'dataentrylevel'=>False),
            2=>Array(
                'name'=>'Level 3',
                'level'=>3,
                'description'=>'Third administrative level',
                'dataentrylevel'=>False),
            3=>Array(
                'name'=>'Level 4',
                'level'=>4,
                'description'=>'Fourth administrative level',
                'dataentrylevel'=>True),
            4=>Array(
                'name'=>'Level 5',
                'level'=>5,
                'description'=>'Lowest level of service provision',
                'dataentrylevel'=>False),
        );
        return $this->organisationunitLevels;
    }

    /**
     * Loads metadata into the database
     *
     * @param ObjectManager $manager
     */

    public function load(ObjectManager $manager)
	{
        $this->addDummyOrganisationunitLevels();
        // Workound parent reference
        $organisationunitLevelReference = strtolower(str_replace(' ','','Level 1')).'-organisationunitlevel';
        if($this->hasReference($organisationunitLevelReference)) {
            // Get orgunitlevel from reference
            $organisationunitLevel = $this->getReference($organisationunitLevelReference);
        }else {
            // Persist and it's reference
            $organisationunitLevel = new OrganisationunitLevel();
            $organisationunitLevel->setLevel(1);
            $organisationunitLevel->setName('Level '.$organisationunitLevel->getLevel());
            $this->addReference($organisationunitLevelReference, $organisationunitLevel);
            $manager->persist($organisationunitLevel);
        }

        // Fetch organisation units from database order by parent and id
        $organiastionunits = $manager->getRepository('HrisOrganisationunitBundle:Organisationunit')->findAll($orderBy=Array('uid'));

        if(!empty($organiastionunits)) {
            foreach($organiastionunits as $organiastionunitKey=>$organisationunit) {

                // Populate orgunit structure
                $organisationunitStructure = new OrganisationunitStructure();
                $organisationunitStructure->setOrganisationunit($organisationunit);

                // Figureout level on the structure by parent
                if($organisationunit->getParent() == NULL) {
                    // Use created default first level for organisationunit structure
                    $organisationunitStructure->setLevel( $organisationunitLevel );
                    $organisationunitStructure->setLevel1Organisationunit($organisationunitStructure->getOrganisationunit());
                }else {
                    // Create new orgunit structure based parent structure

                    //Refer to previously created orgunit structure.
                    $parentOrganisationunitStructureReferenceName=strtolower(str_replace(' ','',$organisationunit->getParent()->getShortname())).'-organisationunitstructure';
                    file_put_contents('/tmp/references.txt', ">>>get ref:".$parentOrganisationunitStructureReferenceName,FILE_APPEND);
                    $parentOrganisationunitStructureByReference = $manager->merge($this->getReference( $parentOrganisationunitStructureReferenceName ));

                    // Cross check to see if level is already created for reusability.
                    $currentOrganisationunitLevelname = 'Level '.($parentOrganisationunitStructureByReference->getLevel()->getLevel()+1);

                    if($this->hasReference(strtolower(str_replace(' ','',$currentOrganisationunitLevelname)).'-organisationunitlevel')) {
                        // Reuse existing reference
                        $currentOrganisationunitLevel = $this->getReference(strtolower(str_replace(' ','',$currentOrganisationunitLevelname)).'-organisationunitlevel');
                        $organisationunitLevel = $manager->merge($currentOrganisationunitLevel);
                    }else {
                        // Create new Level and reference.
                        $organisationunitLevel = new OrganisationunitLevel();
                        $organisationunitLevel->setLevel($parentOrganisationunitStructureByReference->getLevel()->getLevel()+1);
                        $organisationunitLevel->setName('Level '.$organisationunitLevel->getLevel());
                        $organisationunitLevelReference = strtolower(str_replace(' ','',$organisationunitLevel->getName())).'-organisationunitlevel';
                        $this->addReference($organisationunitLevelReference, $organisationunitLevel);
                        $manager->persist($organisationunitLevel);
                    };

                    // Use reference of created/re-used level
                    $organisationunitStructure->setLevel( $organisationunitLevel );

                    /*
                     * Append Level organisation units based on their parent level.
                     */
                    if($organisationunitStructure->getLevel()->getLevel() == 1) {
                        $organisationunitStructure->setLevel1Organisationunit($organisationunitStructure->getOrganisationunit()->getParent());
                    }elseif($organisationunitStructure->getLevel()->getLevel() == 2) {
                        $organisationunitStructure->setLevel2Organisationunit($organisationunitStructure->getOrganisationunit());
                        $organisationunitStructure->setLevel1Organisationunit($organisationunitStructure->getOrganisationunit()->getParent());
                    }elseif($organisationunitStructure->getLevel()->getLevel() == 3) {
                        $organisationunitStructure->setLevel3Organisationunit($organisationunitStructure->getOrganisationunit());
                        $organisationunitStructure->setLevel2Organisationunit($organisationunitStructure->getOrganisationunit()->getParent());
                        $organisationunitStructure->setLevel1Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent());
                    }elseif($organisationunitStructure->getLevel()->getLevel() == 4) {
                        $organisationunitStructure->setLevel4Organisationunit($organisationunitStructure->getOrganisationunit());
                        $organisationunitStructure->setLevel3Organisationunit($organisationunitStructure->getOrganisationunit()->getParent());
                        $organisationunitStructure->setLevel2Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent());
                        $organisationunitStructure->setLevel1Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent()->getParent());
                    }elseif($organisationunitStructure->getLevel()->getLevel() == 5) {
                        $organisationunitStructure->setLevel5Organisationunit($organisationunitStructure->getOrganisationunit());
                        $organisationunitStructure->setLevel4Organisationunit($organisationunitStructure->getOrganisationunit()->getParent());
                        $organisationunitStructure->setLevel3Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent());
                        $organisationunitStructure->setLevel2Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent()->getParent());
                        $organisationunitStructure->setLevel1Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent()->getParent()->getParent());
                    }elseif($organisationunitStructure->getLevel()->getLevel() == 6) {
                        $organisationunitStructure->setLevel6Organisationunit($organisationunitStructure->getOrganisationunit());
                        $organisationunitStructure->setLevel5Organisationunit($organisationunitStructure->getOrganisationunit()->getParent());
                        $organisationunitStructure->setLevel4Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent());
                        $organisationunitStructure->setLevel3Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent()->getParent());
                        $organisationunitStructure->setLevel2Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent()->getParent()->getParent()->getParent());
                        $organisationunitStructure->setLevel1Organisationunit($organisationunitStructure->getOrganisationunit()->getParent()->getParent()->getParent()->getParent()->getParent()->getParent());
                    }
                }
                $organisationunitStructureReference = strtolower(str_replace(' ','',$organisationunit->getShortname())).'-organisationunitstructure';
                file_put_contents('/tmp/references.txt', "Registered:".$organisationunitStructureReference,FILE_APPEND);
                $this->addReference($organisationunitStructureReference, $organisationunitStructure);
                $manager->persist($organisationunitStructure);
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
