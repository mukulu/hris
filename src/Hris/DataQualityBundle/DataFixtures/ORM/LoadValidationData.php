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
namespace Hris\DataQualityBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Hris\DataQualityBundle\Entity\Validation;
use Symfony\Component\Stopwatch\Stopwatch;

class LoadValidationData extends AbstractFixture implements OrderedFixtureInterface
{
	/**
	 * {@inheritDoc}
	 * @see Doctrine\Common\DataFixtures.FixtureInterface::load()
	 */
    private $validations;

    /**
     * Returns array of validation fixtures.
     *
     * @return mixed
     */
    public function getValidations()
    {
        return $this->validations;
    }

    /**
     * Returns array of dummy validations
     * @return array
     */
    public function addDummyValidations()
    {
        // Load Public Data
        $this->validations = Array(
            0=>Array(
                'name'=>'DateOfBirth Vs DateOfFirstAppnt',
                'description'=>'Date of birth versus date of first appointment',
                'operator'=>'<',
                'leftExpression'=>'#{Birthdate}',
                'rightExpression'=>'#{DateofFirstAppointment}'),
            1=>Array(
                'name'=>'DateOfBirth Vs DateOfLastPromo',
                'description'=>'Date of Birth versus Date of last promotion',
                'operator'=>'<',
                'leftExpression'=>'#{Birthdate}',
                'rightExpression'=>'#{DateofLastPromotion}'),
            2=>Array(
                'name'=>'Staff Age',
                'description'=>'The Age of the staff plus 18 should be equal or greater than Now',
                'operator'=>'<=',
                'leftExpression'=>'#{Birthdate}',
                'rightExpression'=>'18'),
            3=>Array(
                'name'=>'FirstAppointment Vs DateOfLastPromo',
                'description'=>'First Appointment Vs Date of Last Promotion',
                'operator'=>'<',
                'leftExpression'=>'#{DateofFirstAppointment}',
                'rightExpression'=>'DateofLastPromotion'),
            4=>Array(
                'name'=>'First Appointment age greater than 18yrs',
                'description'=>'First Appointment age greater than 18 years old.',
                'operator'=>'<=',
                'leftExpression'=>'#{Birthdate}-18',
                'rightExpression'=>'#{DateofFirstAppointment}'),
        );
        return $this->validations;
    }
	public function load(ObjectManager $manager)
	{
        $stopwatch = new Stopwatch();
        $stopwatch->start('dummyValidationGeneration');
        // Populate dummy forms
        $this->addDummyValidations();

        foreach($this->getValidations() as $key=>$humanResourceValidation) {
            $validation = new Validation();
            $validation->setName($humanResourceValidation['name']);
            $validation->setDescription($humanResourceValidation['description']);
            $validation->setOperator($humanResourceValidation['operator']);
            $validation->setLeftExpression($humanResourceValidation['leftExpression']);
            $validation->setRightExpression($humanResourceValidation['rightExpression']);
            $this->addReference(strtolower(str_replace(' ','',$humanResourceValidation['name'])).'-form', $validation);
            $manager->persist($validation);
        }
		$manager->flush();

        /*
         * Check Clock for time spent
         */
        $dummyValidationGenerationTime = $stopwatch->stop('dummyValidationGeneration');
        $duration = $dummyValidationGenerationTime->getDuration()/1000;
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
        //echo "Dummy Validations generation complete in ". $durationMessage .".\n\n";
	}
	
	/**
     * The order in which this fixture will be loaded
	 * @return integer
	 */
	public function getOrder()
	{
        //LoadOrganisationunitGroup preeceds
		return 9;
        //LoadIndicator follows
	}

}
