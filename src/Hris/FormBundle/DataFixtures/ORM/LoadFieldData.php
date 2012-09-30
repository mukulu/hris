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

use Hris\FormBundle\Entity\Field;

class LoadFieldData extends AbstractFixture implements OrderedFixtureInterface
{
	/**
	 * {@inheritDoc}
	 * @see Doctrine\Common\DataFixtures.FixtureInterface::load()
	 */
	public function load(ObjectManager $manager)
	{
		// Load Public Data
		$humanResourceFields = Array(
				0=>Array(
						'dataType'=>'String',
						'inputType'=>'Text',
						'name'=>'Firstname',
						'caption'=>'First name',
						'compulsory'=>True,
						'unique'=>False,
						'description'=>"Employee's firstname (Compulsory)",
						'history'=>False),
				1=>Array(
						'dataType'=>'String',
						'inputType'=>'Text',
						'name'=>'Middlename',
						'caption'=>'Middle name',
						'compulsory'=>False,
						'unique'=>False,
						'description'=>"Employee's middlename (Optional)",
						'history'=>False),
				2=>Array(
						'dataType'=>'String',
						'inputType'=>'Text',
						'name'=>'Surname',
						'caption'=>'Surname',
						'compulsory'=>True,
						'unique'=>False,
						'description'=>"Employee's Surname/Lastname(Compulsory)",
						'history'=>True),
				3=>Array(
						'dataType'=>'Date',
						'inputType'=>'Date',
						'name'=>'Birthdate',
						'caption'=>'Date of Birth',
						'compulsory'=>True,
						'unique'=>False,
						'description'=>"Employee's Date of Birth(Compulsory)",
						'history'=>False),
		);
		foreach($humanResourceFields as $key=>$humanResourceField) {
			$field = new Field();
			$field->setDataType( $manager->merge($this->getReference(strtolower($humanResourceField['dataType']). '-datatype')));
			$field->setInputType($manager->merge($this->getReference(strtolower($humanResourceField['inputType']).'-inputtype')));
			$field->setName($humanResourceField['name']);
			$field->setCaption($humanResourceField['caption']);
			$field->setDescription($humanResourceField['description']);
			$field->setHashistory($humanResourceField['history']);
			$field->setCompulsory($humanResourceField['compulsory']);
			$field->setDatecreated(new \DateTime('now'));
			$this->addReference(strtolower($humanResourceField['name']).'-field', $field);
			$manager->persist($field);
		}
		$manager->flush();
	}
	
	/**
     * The order in which this fixture will be loaded
	 * @return integer
	 */
	public function getOrder()
	{
		return 4;
	}

}
