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
use Symfony\Component\Stopwatch\Stopwatch;

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
                'htmltag'=>'<input type="text" name="fieldName" id="fieldId" required="required"/>'),
            1=>Array(
                'name'=>'Password',
                'description'=>'Password HTML Input Tag',
                'htmltag'=>'<input type="password" name="fieldName" id="fieldId" required="required"/>'),
            2=>Array(
                'name'=>'Radio',
                'description'=>'Radio HTML Input Tag',
                'htmltag'=>'<input type="radio" name="fieldName" id="fieldId" required="required"/>'),
            3=>Array(
                'name'=>'Checkbox',
                'description'=>'Checkbox HTML Input Tag',
                'htmltag'=>'<input type="checkbox" name="fieldName" id="fieldId" required="required"/>'),
            4=>Array(
                'name'=>'TextArea',
                'description'=>'TextArea HTML Tag',
                'htmltag'=>'<textarea name="fieldName" id="fieldId" required="required"></textarea>'),
            5=>Array(
                'name'=>'Date',
                'description'=>'Date HTML Input Tag',
                'htmltag'=>'<input type="text" name="fieldName" id="fieldId" class="date" required="required" />'),
            6=>Array(
                'name'=>'Select',
                'description'=>'Select Options HTML Tag',
                'htmltag'=>'<select name="fieldName" id="fieldId" onload="loadFieldOptions(fieldId)" onchange="changeRelatedFieldOptions(fieldId)" required="required"></select>'),
            7=>Array(
                'name'=>'Email',
                'description'=>'Email HTML Tag',
                'htmltag'=>'<input type="email" name="fieldName" id="fieldId" required="required" />'),
            8=>Array(
                'name'=>'Telephone',
                'description'=>'Telephonw HTML Tag',
                'htmltag'=>'<input type="tel" name="fieldName" id="fieldId" required="required" />'),
            9=>Array(
                'name'=>'Number',
                'description'=>'Number HTML Tag',
                'htmltag'=>'<input type="number" name="fieldName" id="fieldId" required="required" />'),
            10=>Array(
                'name'=>'Double',
                'description'=>'Double HTML Tag',
                'htmltag'=>'<input type="number" name="fieldName" id="fieldId" step="any" required="required" />'),
        );
        return $this->inputTypes;
    }

	/**
	 * {@inheritDoc}
	 * @see Doctrine\Common\DataFixtures.FixtureInterface::load()
	 */
	public function load(ObjectManager $manager)
	{
        $stopwatch = new Stopwatch();
        $stopwatch->start('dummyInputTypesGeneration');

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

        /*
         * Check Clock for time spent
         */
        $dummyInputTypesGenerationTime = $stopwatch->stop('dummyInputTypesGeneration');
        $duration = $dummyInputTypesGenerationTime->getDuration()/1000;
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
        //echo "Dummy Input Types generation complete in ". $durationMessage .".\n\n";
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
