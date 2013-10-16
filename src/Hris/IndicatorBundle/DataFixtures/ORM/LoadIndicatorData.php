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

use Hris\IndicatorBundle\Entity\Indicator;

class LoadIndicatorData extends AbstractFixture implements OrderedFixtureInterface
{
	/**
	 * {@inheritDoc}
	 * @see Doctrine\Common\DataFixtures.FixtureInterface::load()
	 */
    private $indicators;

    /**
     * Returns array of validation fixtures.
     *
     * @return mixed
     */
    public function getIndicators()
    {
        return $this->indicators;
    }

    /**
     * Returns array of dummy indicators
     * @return array
     */
    public function addDummyIndicators()
    {
        // Load Public Data
        $this->indicators = Array(
            0=>Array(
                'name'=>'Secondary Education Health Workers',
                'description'=>'All health works with atleast Secondary Education for Basic education level in Health Centre',
                'organisationunitGroup'=>'healthcentres',
                'fieldOptionGroup'=>'Atleast Secondary Education',
                'year'=>'2013',
                'value'=>5),
            1=>Array(
                'name'=>'University Education Hospital Employees',
                'description'=>'Hospital health workers with atleast university education',
                'organisationunitGroup'=>'hospitals',
                'fieldOptionGroup'=>'University Education',
                'year'=>'2010',
                'value'=>10),
        );
        return $this->indicators;
    }
	public function load(ObjectManager $manager)
	{
        /**
        // Populate dummy forms
        $this->addDummyIndicators();

        foreach($this->getIndicators() as $key=>$humanResourceIndicator) {
            $indicator = new Indicator();
            $indicator->setName($humanResourceIndicator['name']);
            $indicator->setDescription($humanResourceIndicator['description']);
            $organisationunitGroupByReference = $manager->merge($this->getReference( strtolower(str_replace(' ','',$humanResourceIndicator['organisationunitGroup'])).'-organisationunitgroup' ));
            $indicator->setOrganisationunitGroup($organisationunitGroupByReference);
            $fieldOptionGroupByReference = $manager->merge($this->getReference( strtolower(str_replace(' ','',$humanResourceIndicator['fieldOptionGroup'])).'-fieldoptiongroup' ));
            $indicator->setFieldOptionGroup($fieldOptionGroupByReference);
            $indicator->setYear($humanResourceIndicator['year']);
            $indicator->setValue($humanResourceIndicator['value']);

            $this->addReference(strtolower(str_replace(' ','',$humanResourceIndicator['name'])).'-form', $indicator);
            $manager->persist($indicator);
        }
		$manager->flush();
         * */
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
