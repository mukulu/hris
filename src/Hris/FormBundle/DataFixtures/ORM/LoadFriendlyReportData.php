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
use Hris\FormBundle\Entity\FieldGroup;
use Hris\FormBundle\Entity\FieldOption;
use Hris\FormBundle\Entity\FieldOptionGroup;
use Hris\FormBundle\Entity\FieldOptionGroupset;
use Hris\FormBundle\Entity\FriendlyReport;
use Hris\FormBundle\Entity\FriendlyReportCategory;
use Symfony\Component\Stopwatch\Stopwatch;

class LoadFriendlyData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @var friendlyReports
     */
    private $friendlyReports;

    /**
     * Returns Array of friendlyReports fixtures
     *
     * @return mixed
     */
    public function getFriendlyReports()
    {
        return $this->friendlyReports;
    }

    /**
     * Returns Array of dummy fields
     *
     * @return array
     */
    public function addDummyFriendlyReports()
    {
        // Load Public Data
        $this->friendlyReports = Array(
            0=>Array(
                'name'=>'CCHP Table 11a - HR Requrements',
                'sort'=>1,
                'description'=>'Human Resource for Health and social welfare requirements',
                'series'=>'CCHP Professions',
                'categories'=>Array('Gender'),
            ),
            1=>Array(
                'name'=>'CCH Table 11b - Core&Coopted Members',
                'sort'=>2,
                'description'=>'CHMT & RHMT Core Staff and Co-opted Members',
                'series'=>'CCHP CoreAndCoopted',
                'categories'=>Array('Gender'),
            ),
            2=>Array(
                'name'=>'CCH Table 11c - Staff Availability Trend',
                'sort'=>3,
                'description'=>'The Health and Social Welfare Staff Attrition by Gender',
                'series'=>'Health Professions',
                'categories'=>Array('CCHP Staff Availability','Gender'),
            ),
            3=>Array(
                'name'=>'CCHP Table 11d - Staff Attrition',
                'sort'=>4,
                'description'=>'The Health and Social Welfare Staff Attrition',
                'series'=>'Health Professions',
                'categories'=>Array('CCHP Attrition'),
            )
        );
    }

    /**
     * Loads metadata into the database
     *
     * @param ObjectManager $manager
     */

    public function load(ObjectManager $manager)
	{
        $stopwatch = new Stopwatch();
        $stopwatch->start('dummyFriendlyReportGeneration');

        $this->addDummyFriendlyReports();
        //Persist friendly reports
        foreach($this->getFriendlyReports() as $friendlyReportKey=>$humanResourceFriendlyReport) {
            $friendlyReport = new FriendlyReport();
            $friendlyReport->setName($humanResourceFriendlyReport['name']);
            $friendlyReport->setSort($humanResourceFriendlyReport['sort']);
            $friendlyReport->setDescription($humanResourceFriendlyReport['description']);
            $seriesReference = strtolower(str_replace(' ','',$humanResourceFriendlyReport['series'])).'-fieldoptiongroup';
            $seriesByReference = $manager->merge($this->getReference( $seriesReference ));
            $friendlyReport->setSerie($seriesByReference);
            $manager->persist($friendlyReport);
            $sort=1;
            foreach($humanResourceFriendlyReport['categories'] as $friendlyCategoryKey=>$friendlyCategory) {
                $fieldOptionGroupReference = strtolower(str_replace(' ','',$friendlyCategory)).'-fieldoptiongroup';
                $fieldOptionGroupByReference = $manager->merge($this->getReference( $fieldOptionGroupReference ));
                $friendlyReportCategory = new FriendlyReportCategory();
                $friendlyReportCategory->setFriendlyReport($friendlyReport);
                $friendlyReportCategory->setFieldOptionGroup($fieldOptionGroupByReference);
                $friendlyReportCategory->setSort($sort++);
                $manager->persist($friendlyReportCategory);
            }
        }
		$manager->flush();

        /*
         * Check Clock for time spent
         */
        $dummyFriendlyReportGenerationTime = $stopwatch->stop('dummyFriendlyReportGeneration');
        $duration = $dummyFriendlyReportGenerationTime->getDuration()/1000;
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
        //echo "Dummy Form generation complete in ". $durationMessage .".\n\n";
	}
	
	/**
     * The order in which this fixture will be loaded
	 * @return integer
	 */
	public function getOrder()
	{
        //LoadForm preceeds
        return 6;
        //LoadOrganisationunit follows
	}

}
