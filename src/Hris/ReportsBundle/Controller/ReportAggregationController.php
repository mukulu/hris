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
            $fields = $aggregationFormData['fields'];
        }

        $results = $this->aggregationEngine($organisationUnit, $forms, $fields);

        foreach($results as $result){
            $categories[] = $result[strtolower($fields->getName())];
            $data[] =  $result['total'];
        }
        //var_dump($categories);exit();

        /*
        return array(
            'organisationunit' => $organisationunit,
            'forms'   => $forms,
            'fields' => $fields,
        );*/

        $series = array(
            array(
                'name'  => $fields->getCaption(),
                'type'  => 'column',
                'color' => '#0D0DC1',
                'yAxis' => 1,
                'data'  => $data,
            ),

        );
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
                'gridLineWidth' => 0,
            ),
        );
        //$categories = array('2003', '2004', '2005', '2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013');

        $dashboardchart = new Highchart();
        $dashboardchart->chart->renderTo('chart_placeholder'); // The #id of the div where to render the chart
        $dashboardchart->chart->type('column');
        $dashboardchart->title->text($fields->getCaption().' Distribution');
        $dashboardchart->subtitle->text('Ministry of Health And Social Welfare with lower levels');
        $dashboardchart->xAxis->categories($categories);
        $dashboardchart->yAxis($yData);
        $dashboardchart->legend->enabled(false);
        $formatter = new Expr('function () {
                 var unit = {

                     $fields->getCaption(): strtolower($fields->getCaption()),

                 }[this.series.name];
                 if(this.point.name) {
                    return ""+this.point.name+": <b>"+ this.y+"</b> "+ unit;
                 }else {
                    return this.x + ": <b>" + this.y + "</b> " + unit;
                 }
             }');
        $dashboardchart->tooltip->formatter($formatter);
        $dashboardchart->series($series);

        return array(
            'chart'=>$dashboardchart
        );
    }


    /**
     * @param Organisationunit $organisationUnit
     * @param Form $forms
     * @param Field $fields
     */
    private function aggregationEngine(Organisationunit $organisationUnit,  ArrayCollection $forms, Field $fields)
    {
        //var_dump($forms);exit();
        $entityManager = $this->getDoctrine()->getManager();
        $query = "SELECT ResourceTable.".$fields->getName();
       // if ($fieldTwoId != SystemProperties::$useonefieldID) {
       //     $query .= " , ResourceTable.".$fieldTwo->getName()." , count(ResourceTable.".$fieldTwo->getName().") as total";
       //}else{
            $query .= " , count(ResourceTable.".$fields->getName().") as total";
       // }
        $resourceTableName = "_resource_all_fields";
        $selectedOrgunitStructure = $entityManager->getRepository('HrisOrganisationunitBundle:OrganisationunitStructure')->findOneBy(array('organisationunit' => $organisationUnit->getId()));
        $query .= " FROM ".$resourceTableName." ResourceTable inner join hris_organisationunit as Orgunit ON Orgunit.id = ResourceTable.organisationunit_id INNER JOIN hris_organisationunitstructure AS Structure ON Structure.organisationunit_id = ResourceTable.organisationunit_id";

        $query .= " WHERE ResourceTable.".$fields->getName()." is not NULL ";
        //if ($fieldTwoId != SystemProperties::$useonefieldID) {
        //    $query .= " AND ResourceTable.".$fieldTwo->getName()." is not NULL";
        //}

        //filter the records by the selected form and facility
        foreach($forms as $form){
            $query .= " AND ResourceTable.form_id IN (".$form->getId().")";
        }

        //if($selectUnit == "Yes"){
            $query .= " AND Structure.level".$selectedOrgunitStructure->getLevel()->getLevel()."_id=".$organisationUnit->getId();
            $query .= " AND  Structure.level_id >= ";
            $query .= "(SELECT hris_organisationunitstructure.level_id FROM hris_organisationunitstructure WHERE hris_organisationunitstructure.organisationunit_id=".$organisationUnit->getId()." )";
        //}else{
        //    $query .= " AND ResourceTable.orgunit_id=".$orgunitId;
        //}

        //filter the records if the organisation group was choosen
        //if(!empty($orgunitGroupId))$subQuery .= " AND (type='".$orgunitGroup->getName()."' OR ownership='".$orgunitGroup->getName()."' OR administrative='".$orgunitGroup->getName()."')";

        //remove the record which have field option set to exclude in reports
        /*foreach($fieldOptionsToExclude as $key => $fieldOptionToExclude)
            $query .= " AND ResourceTable.".$fieldOptionToExclude->getField()->getName()." != '".$fieldOptionToExclude->getValue()."'";
        */
        $query .= " GROUP BY ResourceTable.".$fields->getName();
        //if ($fieldTwoId != SystemProperties::$useonefieldID) {
        //    $query .= " , ResourceTable.".$fieldTwo->getName();
        //}

        $query .= " ORDER BY ResourceTable.".$fields->getName();
        //if ($fieldTwoId != SystemProperties::$useonefieldID) {
        //    $query .= " , ResourceTable.".$fieldTwo->getName();
        //}
        //echo $query;
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
