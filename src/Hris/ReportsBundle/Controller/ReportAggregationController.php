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
 * @author Wilfred Felix Senyoni <senyoni@gmail.com>
 *
 */
namespace Hris\ReportsBundle\Controller;

use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Hris\FormBundle\Entity\Field;
use Hris\ReportsBundle\Form\ReportAggregationType;
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
 * Report Aggregation controller.
 *
 * @Route("/reports/aggregation")
 */
class ReportAggregationController extends Controller
{

    /**
     * Show Report Aggregation
     *
     * @Route("/", name="report_aggregation")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

        $aggregationForm = $this->createForm(new ReportAggregationType(),null,array('em'=>$this->getDoctrine()->getManager()));

        return array(
            'aggregationForm'=>$aggregationForm->createView(),
        );
    }

    /**
     * Generate aggregated reports
     *
     * @Route("/", name="report_aggregation_generate")
     * @Method("PUT")
     * @Template()
     */
    public function generateAction(Request $request)
    {
        $aggregationForm = $this->createForm(new ReportAggregationType(),null,array('em'=>$this->getDoctrine()->getManager()));
        $aggregationForm->bind($request);

        if ($aggregationForm->isValid()) {
            $aggregationFormData = $aggregationForm->getData();
            $organisationUnit = $aggregationFormData['organisationunit'];
            $forms = $aggregationFormData['forms'];
            $organisationunitGroup = $aggregationFormData['organisationunitGroup'];
            $withLowerLevels = $aggregationFormData['withLowerLevels'];
            $fields = $aggregationFormData['fields'];
            $fieldsTwo = $aggregationFormData['fieldsTwo'];
            $graphType = $aggregationFormData['graphType'];
        }


        $results = $this->aggregationEngine($organisationUnit, $forms, $fields, $organisationunitGroup, $withLowerLevels, $fieldsTwo);

        //if only one field selected
        if($fieldsTwo->getId() == 8){
            foreach($results as $result){
                $categories[] = $result[strtolower($fields->getName())];
                $data[] =  $result['total'];
                if($graphType == "pie"){
                    $piedata[] = array('name' => $result[strtolower($fields->getName())],'y' => $result['total']);
                }
            }
            if($graphType == "pie") $data = $piedata;
            $series = array(
                array(
                    'name'  => $fields->getName(),
                    'data'  => $data,
                ),
            );
            $formatterLabel = $fields->getCaption();

        }else{
            foreach($results as $result){
                $keys[$result[strtolower($fieldsTwo->getName())]][] = $result['total'];
                $categoryKeys[$result[strtolower($fields->getName())]] = $result['total'];
            }
            $series = array();
            foreach($keys as $key => $values){
                $series[] = array(
                    'name'  => $key,
                    'yAxis' => 1,
                    'data'  => $values,
                );
            }
            $formatterLabel = $fieldsTwo->getCaption();
            $categories = array_keys($categoryKeys);
        }
        //var_dump($series);exit();
        //check which type of chart to display
        if($graphType == "bar"){
            $graph = "column";
        }elseif($graphType == "line"){
            $graph = "spline";
        }else{
            $graph = "pie";
        }
        //set the title and sub title
        $title = $fields->getCaption()." Distribution";
        if($fieldsTwo->getId() != 8) $title .= " with ".$fieldsTwo->getCaption()." cross Tabulation ";
        /*
        return array(
            'organisationunit' => $organisationunit,
            'forms'   => $forms,
            'fields' => $fields,
        );*/





        $yData = array(
            array(
                'labels' => array(
                    'formatter' => new Expr('function () { return this.value + "" }'),
                    'style'     => array('color' => '#0D0DC1')
                ),
                'title' => array(
                    'text'  => $fields->getCaption(),
                    'style' => array('color' => '#0D0DC1')
                ),
                'opposite' => true,
            ),
            array(
                'labels' => array(
                'formatter' => new Expr('function () { return this.value + "" }'),
                'style'     => array('color' => '#AA4643')
            ),
            'gridLineWidth' => 1,
            'title' => array(
                'text'  => $fields->getCaption(),
                'style' => array('color' => '#AA4643')
            ),
        ),
        );



        $dashboardchart = new Highchart();
        $dashboardchart->chart->renderTo('chart_placeholder'); // The #id of the div where to render the chart
        $dashboardchart->chart->type($graph);
        $dashboardchart->title->text($title);
        $dashboardchart->subtitle->text($organisationUnit->getLongname().' with lower levels');
        $dashboardchart->xAxis->categories($categories);
        $dashboardchart->yAxis($yData);
        $dashboardchart->legend->enabled(false);

        $formatter = new Expr('function () {
                 var unit = {

                     "'.$formatterLabel.'" : "'. strtolower($formatterLabel).'",

                 }[this.series.name];
                 if(this.point.name) {
                    return ""+this.point.name+": <b>"+ this.y+"</b> "+ this.series.name;
                 }else {
                    return this.x + ": <b>" + this.y + "</b> " + this.series.name;
                 }
             }');
        $dashboardchart->tooltip->formatter($formatter);
        $dashboardchart->series($series);

        return array(
            'chart'=>$dashboardchart
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
