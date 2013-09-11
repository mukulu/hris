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

use Hris\FormBundle\Entity\ResourceTable;
use Hris\FormBundle\DataFixtures\ORM\LoadFieldData;
use Hris\FormBundle\Entity\ResourceTableFieldMember;

class LoadResourceTableData extends AbstractFixture implements OrderedFixtureInterface
{
	/**
	 * {@inheritDoc}
	 * @see Doctrine\Common\DataFixtures.FixtureInterface::load()
	 */
    private $resourceTables;

    /**
     * Returns array of form fixtures.
     *
     * @return mixed
     */
    public function getResourceTables()
    {
        return $this->resourceTables;
    }

    /**
     * Returns array of dummy forms
     * @return array
     */
    public function addDummyResourceTables()
    {
        // Load Public Data
        $this->resourceTables = Array(
            0=>Array(
                'name'=>'All Fields',
                'filter'=>false,
                'inputType'=>NULL,
                'compulsory'=> NULL,
                'description'=>'All Record Fields used',
            ),
            1=>Array(
                'name'=>'Combo Fields',
                'filter'=>true,
                'inputType'=>'Select',
                'compulsory'=>NULL,
                'description'=>'All Record Fields with select options',
            ),
            2=>Array(
                'name'=>'Compulsory Fields',
                'filter'=>true,
                'inputType'=>NULL,
                'compulsory'=>true,
                'description'=>'All Record Fields that are compulsory',
            ),
        );
        return $this->resourceTables;
    }

	public function load(ObjectManager $manager)
	{
        // Populate dummy forms
        $this->addDummyResourceTables();
        // Seek dummy fields
        $loadFieldData = new LoadFieldData();
        $loadFieldData->addDummyFields();
        $dummyFields = $loadFieldData->getFields();

        foreach($this->resourceTables as $resourceTableKey=>$humanResourceResourceTable) {
            $resourceTable = new ResourceTable();
            $resourceTable->setName($humanResourceResourceTable['name']);
            $resourceTable->setDescription($humanResourceResourceTable['description']);
            $resourceTableRefernce = strtolower(str_replace(' ','',$humanResourceResourceTable['name'])).'-resourcetable';
            $this->addReference($resourceTableRefernce, $resourceTable);
            $manager->persist($resourceTable);
            // Add Field Members for the resource table created
            $sort=1;
            foreach($dummyFields as $key => $dummyField)
            {
                //Filter addition of fields not compliant to filter
                if($humanResourceResourceTable['filter'] ==false || $humanResourceResourceTable['inputType']==$dummyField['inputType'] || $humanResourceResourceTable['compulsory']==$dummyField['compulsory'] ) {
                    $resourceTableMember = new ResourceTableFieldMember();
                    $resourceTableMember->setField($manager->merge($this->getReference( strtolower(str_replace(' ','',$dummyField['name'])).'-field' )));
                    $resourceTableMember->setResourceTable( $manager->merge($this->getReference($resourceTableRefernce)) );
                    $resourceTableMember->setSort($sort++);
                    $referenceName = strtolower(str_replace(' ','',$humanResourceResourceTable['name']).str_replace(' ','',$dummyField['name'])).'-resourcetable-field-member';
                    $this->addReference($referenceName, $resourceTableMember);
                    $manager->persist($resourceTableMember);
                    $resourceTable->addResourceTableFieldMember($resourceTableMember);
                }
            }
            $manager->persist($resourceTable);
        }
		$manager->flush();

        // Generate resource tables
        $resourceTables = $manager->getRepository('HrisFormBundle:ResourceTable')->findAll();
        foreach($resourceTables as $resourceTableKey=>$resourceTable) {
            // Ugly hack to generate resource table for "All Fields" only
            if($resourceTable->getName() == "All Fields") {
                $success = $resourceTable->generateResourceTable($manager);
                $messageLog = $resourceTable->getMessageLog();
                if($success) echo $messageLog;
                else echo "Failed with:".$messageLog;
            }
        }
	}
	
	/**
     * The order in which this fixture will be loaded
	 * @return integer
	 */
	public function getOrder()
	{
		return 11;
	}

}
