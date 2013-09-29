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

use Hris\FormBundle\Entity\InputType;

class LoadInputTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @var inputTypes
     */
    private $inputTypes;

    /**
     * Returns Array of inputTypes fixtures
     *
     * @return mixed
     */
    public function getInputTypes()
    {
        return $this->inputTypes;
    }

    /**
     * Returns Array of dummy fields
     *
     * @return array
     */
    public function addDummyInputTypes()
    {
        // Load Public Data
        $this->inputTypes = Array(
            0=>Array(
                'name'=>'Text',
                'description'=>'Textbox HTML Tag',
                'htmltag'=>'<input type="text" name="fieldName" id="fieldId"/>'),
            1=>Array(
                'name'=>'Password',
                'description'=>'Password HTML Input Tag',
                'htmltag'=>'<input type="password" name="fieldName" id="fieldId"/>'),
            2=>Array(
                'name'=>'Radio',
                'description'=>'Radio HTML Input Tag',
                'htmltag'=>'<input type="radio" name="fieldName" id="fieldId"/>'),
            3=>Array(
                'name'=>'Checkbox',
                'description'=>'Checkbox HTML Input Tag',
                'htmltag'=>'<input type="checkbox" name="fieldName" id="fieldId"/>'),
            4=>Array(
                'name'=>'TextArea',
                'description'=>'TextArea HTML Tag',
                'htmltag'=>'<textarea name="fieldName" id="fieldId"></textarea>'),
            5=>Array(
                'name'=>'Date',
                'description'=>'Date HTML Input Tag',
                'htmltag'=>'<input type="date" name="fieldName" id="fieldId" />'),
            6=>Array(
                'name'=>'Select',
                'description'=>'Select Options HTML Tag',
                'htmltag'=>'<select name="fieldName" id="fieldId" onload="loadFieldOptions(fieldId)" onchange="changeRelatedFieldOptions(fieldId)"></select>'),
        );
        return $this->inputTypes;
    }

	/**
	 * {@inheritDoc}
	 * @see Doctrine\Common\DataFixtures.FixtureInterface::load()
	 */
	public function load(ObjectManager $manager)
	{
        $this->addDummyInputTypes();
		// Load Public Data
		$inputTypeNames = Array('Text','Password','Radio','Checkbox','TextArea','Date','Select');
		foreach($this->inputTypes as $inputTypeKey=>$humanResourceInputType) {
            $inputType = new InputType();
            $inputType->setName($humanResourceInputType['name']);
            $inputType->setDescription($humanResourceInputType['description']);
            $inputType->setHtmltag($humanResourceInputType['htmltag']);
			$manager->persist($inputType);
			$this->addReference(strtolower($humanResourceInputType['name']).'-inputtype', $inputType);
		}
		$manager->flush();
	}
	
	/**
     * The order in which this fixture will be loaded
	 * @return integer
	 */
	public function getOrder()
	{
        // LoadUser follows
		return 2;
        //LoadDataType follows
	}

}
