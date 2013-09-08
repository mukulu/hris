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
 * @author Wilfred Felix Senyoni <senyoni@gmail.com>
 *
 */
namespace Hris\ReportsBundle\Controller;

use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Hris\FormBundle\Entity\Field;
use Hris\ReportsBundle\Form\ReportRecordsType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\ReportsBundle\Entity\Report;
use Hris\ReportsBundle\Form\ReportType;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Zend\Json\Expr;

/**
 * Report Records controller.
 *
 * @Route("/reports/records")
 */
class ReportRecordsController extends Controller
{

    /**
     * Show Report Records
     *
     * @Route("/", name="report_records")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

        $recordsForm = $this->createForm(new ReportRecordsType(),null,array('em'=>$this->getDoctrine()->getManager()));

        return array(
            'recordsForm'=>$recordsForm->createView(),
        );
    }

    /**
     * Generate records reports
     *
     * @Route("/", name="report_records_generate")
     * @Method("PUT")
     * @Template()
     */
    public function generateAction(Request $request)
    {
        $recordsForm = $this->createForm(new ReportRecordsType(),null,array('em'=>$this->getDoctrine()->getManager()));
        $recordsForm->bind($request);

        if ($recordsForm->isValid()) {
            $recordsFormData = $recordsForm->getData();
            $organisationUnit = $recordsFormData['organisationunit'];
            $forms = $recordsFormData['forms'];
            $withLowerLevels = $recordsFormData['withLowerLevels'];
        }




        return array(
        );
    }


    /**
     * Aggregation Engine
     *
     * @param Organisationunit $organisationUnit
     * @param ArrayCollection $forms
     * @param Field $fields
     * @param ArrayCollection $organisationunitGroup
     * @param $withLowerLevels
     * @param Field $fieldsTwo
     * @param $graphType
     * @return mixed
     */
    private function aggregationEngine(Organisationunit $organisationUnit,  ArrayCollection $forms, Field $fields, ArrayCollection $organisationunitGroup, $withLowerLevels, Field $fieldsTwo)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $selectedOrgunitStructure = $entityManager->getRepository('HrisOrganisationunitBundle:OrganisationunitStructure')->findOneBy(array('organisationunit' => $organisationUnit->getId()));

        //get the list of options to exclude from the reports
        /*$fieldOptionsToExclude = $entityManager->getRepository('HrisFormBundle:FieldOption')->findBy (
            array('excludeAggregate' => "YES")
        );

        //remove the value which have field option set to exclude in reports
        //but check to see if the first field is in the list of fields to remove.
        foreach($fieldOptionsToExclude as $key => $fieldOptionToExclude)
                if($fieldOptionToExclude->getField()->getId() == $fields->getId())
                        unset($fieldOptionsToExclude[$key]);*/

        //create the query to aggregate the records from the static resource table
        $query = "SELECT ResourceTable.".$fields->getName();
        if ($fieldsTwo->getId() != 8) {
            $query .= " , ResourceTable.".$fieldsTwo->getName()." , count(ResourceTable.".$fieldsTwo->getName().") as total";
       }else{
            $query .= " , count(ResourceTable.".$fields->getName().") as total";
       }
        $resourceTableName = "_resource_all_fields";

        $query .= " FROM ".$resourceTableName." ResourceTable inner join hris_organisationunit as Orgunit ON Orgunit.id = ResourceTable.organisationunit_id INNER JOIN hris_organisationunitstructure AS Structure ON Structure.organisationunit_id = ResourceTable.organisationunit_id";

        $query .= " WHERE ResourceTable.".$fields->getName()." is not NULL ";
        if ($fieldsTwo->getId() != 8) {
            $query .= " AND ResourceTable.".$fieldsTwo->getName()." is not NULL";
        }

        //filter the records by the selected form and facility
        $query .= " AND ResourceTable.form_id IN (";
        foreach($forms as $form){
            $query .= $form->getId()." ,";
        }

        //remove the last comma in the query
        $query = rtrim($query,",").")";

        if($withLowerLevels){
            $query .= " AND Structure.level".$selectedOrgunitStructure->getLevel()->getLevel()."_id=".$organisationUnit->getId();
            $query .= " AND  Structure.level_id >= ";
            $query .= "(SELECT hris_organisationunitstructure.level_id FROM hris_organisationunitstructure WHERE hris_organisationunitstructure.organisationunit_id=".$organisationUnit->getId()." )";
        }else{
            $query .= " AND ResourceTable.organisationunit_id=".$organisationUnit->getId();
        }

        //filter the records if the organisation group was choosen
        /*if(!empty($organisationunitGroup)){
            foreach($organisationunitGroup as $organisationunitGroups){
                $groups .= "'".$organisationunitGroups->getName()."',";
            }
            //remove the last comma in the query
            $groups = rtrim($groups,",");

            $query .= " AND (ResourceTable.type IN (".$groups.") OR ownership IN (".$groups.") )";//OR administrative IN (".$groups.")
        }*/

        //remove the record which have field option set to exclude in reports
        //foreach($fieldOptionsToExclude as $key => $fieldOptionToExclude)
        //    $query .= " AND ResourceTable.".$fieldOptionToExclude->getField()->getName()." != '".$fieldOptionToExclude->getValue()."'";

        $query .= " GROUP BY ResourceTable.".$fields->getName();
        if ($fieldsTwo->getId() != 8) {
            $query .= " , ResourceTable.".$fieldsTwo->getName();
        }

        $query .= " ORDER BY ResourceTable.".$fields->getName();
        if ($fieldsTwo->getId() != 8) {
            $query .= " , ResourceTable.".$fieldsTwo->getName();
        }
        //echo $query;exit();
        //get the records
        $report = $entityManager -> getConnection() -> executeQuery($query) -> fetchAll();
        return $report;
        /*
        $tabulationValues = '';
        $tempFieldOption = '';
        if ($fieldTwoId != SystemProperties::$useonefieldID) {//when two fields are  selected
            foreach ($report as $key => $reportValue) {

                if($tempFieldOption != $reportValue[$fieldOne->getName()]){
                    if($key != 0){ //first time of the loop or the last time of the loop
                        foreach ($fieldTwoOption as $keys => $fieldTwoOptions) {
                            if (!array_key_exists($fieldTwoOptions, $tempArray)) {
                                $tempArray[$fieldTwoOptions] = 0;
                            }
                        }
                        ksort($tempArray);
                        $tabulationValues[$tempFieldOption] = $tempArray;

                    }
                    $tempFieldOption = $reportValue[$fieldOne->getName()];
                    $tempArray = '';
                    $tempArray[$reportValue[$fieldTwo->getName()]] = $reportValue['total'];

                }else{
                    $tempArray[$reportValue[$fieldTwo->getName()]] = $reportValue['total'];
                }

                if($key == count($report)-1){//deal with the last record
                    foreach ($fieldTwoOption as $keys => $fieldTwoOptions) {
                        if (!array_key_exists($fieldTwoOptions, $tempArray)) {
                            $tempArray[$fieldTwoOptions] = 0;
                        }
                    }
                    ksort($tempArray);
                    $tabulationValues[$tempFieldOption] = $tempArray;
                }
            }

        }else{//when one field is  selected
            foreach ($report as $key => $reportValue) {
                $tabulationValues[$reportValue[$fieldOne->getName()]] = $reportValue['total'];
            }
        }*/
    }

}
