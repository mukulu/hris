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
namespace Hris\IndicatorBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Hris\IndicatorBundle\Entity\Target;
use Hris\IndicatorBundle\Entity\TargetFieldOption;
use Symfony\Component\Stopwatch\Stopwatch;

class LoadIndicatorData extends AbstractFixture implements OrderedFixtureInterface
{
	/**
	 * {@inheritDoc}
	 * @see Doctrine\Common\DataFixtures.FixtureInterface::load()
	 */
    private $targets;

    /**
     * Returns array of target fixtures.
     *
     * @return mixed
     */
    public function getTargets()
    {
        return $this->targets;
    }

    /**
     * Returns array of dummy targets
     * @return array
     */
    public function addDummyTargets()
    {
        // Load Public Data
        $this->targets = Array(
            0=>Array(
                'name'=>'Health Centres Professions',
                'description'=>'Professions for Health Centres',
                'organisationunitGroup'=>'healthcentres',
                'field'=>'Profession',
                'targets'=>Array(
                    Array('fieldOption'=>'Driver','target'=>2),
                    Array('fieldOption'=>'Clinical Assistant','target'=>2),
                    Array('fieldOption'=>'Dental Technologist','target'=>1),
                    Array('fieldOption'=>'Nursing Officer','target'=>2),
                    Array('fieldOption'=>'Assistant Medical Officer','target'=>2),
                    Array('fieldOption'=>'Environmental Health Officer','target'=>1),
                    Array('fieldOption'=>'Pharmacist','target'=>2),
                    Array('fieldOption'=>'Medical Doctor','target'=>2)
                )
            ),
            1=>Array(
                'name'=>'Hospital Professions',
                'description'=>'Professions for Hospitals',
                'organisationunitGroup'=>'hospitals',
                'field'=>'Profession',
                'targets'=>Array(
                    Array('fieldOption'=>'Driver','target'=>4),
                    Array('fieldOption'=>'Clinical Assistant','target'=>18),
                    Array('fieldOption'=>'Dental Technologist','target'=>5),
                    Array('fieldOption'=>'Nursing Officer','target'=>15),
                    Array('fieldOption'=>'Assistant Medical Officer','target'=>6),
                    Array('fieldOption'=>'Environmental Health Officer','target'=>5),
                    Array('fieldOption'=>'Pharmacist','target'=>3),
                    Array('fieldOption'=>'Medical Doctor','target'=>3)
                )
            ),
            2=>Array(
                'name'=>'Dispensary Professions',
                'description'=>'Professions for Dispensaries',
                'organisationunitGroup'=>'dispensaries',
                'field'=>'Profession',
                'targets'=>Array(
                    Array('fieldOption'=>'Driver','target'=>1),
                    Array('fieldOption'=>'Clinical Assistant','target'=>5),
                    Array('fieldOption'=>'Dental Technologist','target'=>1),
                    Array('fieldOption'=>'Nursing Officer','target'=>4),
                    Array('fieldOption'=>'Assistant Medical Officer','target'=>2),
                    Array('fieldOption'=>'Environmental Health Officer','target'=>2),
                    Array('fieldOption'=>'Pharmacist','target'=>1),
                    Array('fieldOption'=>'Medical Doctor','target'=>3)
                )
            )
        );
        return $this->targets;
    }
	public function load(ObjectManager $manager)
	{
        $stopwatch = new Stopwatch();
        $stopwatch->start('dummyIndicatorGeneration');

        // Populate dummy forms
        $this->addDummyTargets();

        foreach($this->getTargets() as $key=>$humanResourceTarget) {
            $target = new Target();
            $target->setName($humanResourceTarget['name']);
            $target->setDescription($humanResourceTarget['description']);
            $organisationunitGroupByReference = $manager->merge($this->getReference( strtolower(str_replace(' ','',$humanResourceTarget['organisationunitGroup'])).'-organisationunitgroup' ));
            $target->setOrganisationunitGroup($organisationunitGroupByReference);
            foreach($humanResourceTarget['targets'] as $targetKey=>$humaResourceTargetFieldOption) {
                $targetFieldOption = new TargetFieldOption();
                $fieldOptionReference = strtolower(str_replace(' ','',$humaResourceTargetFieldOption['fieldOption'])).str_replace('-field','',$humanResourceTarget['field']).'-fieldoption';
                $fieldOptionByReference = $manager->merge($this->getReference( $fieldOptionReference ));
                $targetFieldOption->setFieldOption($fieldOptionByReference);
                $targetFieldOption->setTarget($target);
                $targetFieldOption->setValue($humaResourceTargetFieldOption['target']);
                $target->addTargetFieldOption($targetFieldOption);
            }
            $this->addReference(strtolower(str_replace(' ','',$humanResourceTarget['name'])).'-target', $target);
            $manager->persist($target);
        }
		$manager->flush();

        /*
         * Check Clock for time spent
         */
        $dummyIndicatorGenerationTime = $stopwatch->stop('dummyIndicatorGeneration');
        $duration = $dummyIndicatorGenerationTime->getDuration()/1000;
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
        //echo "Dummy Indicators generation complete in ". $durationMessage .".\n\n";

	}
	
	/**
     * The order in which this fixture will be loaded
	 * @return integer
	 */
	public function getOrder()
	{
        //LoadValidation preceeds
		return 10;
        //LoadRecord follows
	}

}
