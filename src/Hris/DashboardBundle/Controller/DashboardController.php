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
namespace Hris\DashboardBundle\Controller;

use Hris\FormBundle\Entity\Field;
use Hris\ReportsBundle\Controller\ReportAggregationController;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Json\Expr;

/**
 * Dashboard controller.
 *
 * @Route("/", name="dashboard")
 */
class DashboardController extends Controller
{
    /**
     * Displays dashboard page
     *
     * @Secure(roles="ROLE_DASHBOARD_DASHBOARD_SHOW,ROLE_USER")
     *
     * @Route("/", name="hris_homepage")
     * @Route("/dashboard/", name="hris_dashboard_homepage")
     * @Method("GET")
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        //get the detail for the active user
        $user = $this->container->get('security.context')->getToken()->getUser();
        $reportAggregationController =  New ReportAggregationController();
        $entityManager = $this->getDoctrine()->getManager();
        $organisationunitGroups = new ArrayCollection();
        $fieldTwo = $entityManager->getRepository('HrisFormBundle:Field')->findOneByName
            (array('name'=> "ContactsofNextofKin"));



        $forms = new ArrayCollection();
        $formObjects = $entityManager->getRepository('HrisFormBundle:Form')->findAll();
        foreach($formObjects as $formObject){
            $forms->add($formObject) ;
        }

        //pull the fields needed
        $dashboardEmplyomentField = $entityManager->getRepository('HrisFormBundle:Field')->findOneByName
                (array('name'=> "EmploymentDistribution"));

        $dashboardRetirementField = $entityManager->getRepository('HrisFormBundle:Field')->findOneByName
            (array('name'=> "RetirementDistribution"));

        $dashboardAgeField = $entityManager->getRepository('HrisFormBundle:Field')->findOneByName
            (array('name'=> "AgeDistribution"));

        $dashboardGenderField = $entityManager->getRepository('HrisFormBundle:Field')->findOneByName
            (array('name'=> "Sex"));

        //get the data from the aggregation engine
        $dashboardEmplyomentChart = $reportAggregationController::aggregationEngine($user->getOrganisationunit(), $forms,$dashboardEmplyomentField, $organisationunitGroups, TRUE, $dashboardEmplyomentField);

        $dashboardRetirementChart = $reportAggregationController::aggregationEngine($user->getOrganisationunit(), $forms,$dashboardRetirementField, $organisationunitGroups, TRUE, $dashboardRetirementField);

        $dashboardAgeChart = $reportAggregationController::aggregationEngine($user->getOrganisationunit(), $forms,$dashboardAgeField, $organisationunitGroups, TRUE, $dashboardAgeField);

        $dashboardGenderChart = $reportAggregationController::aggregationEngine($user->getOrganisationunit(), $forms, $dashboardGenderField, $organisationunitGroups, TRUE, $dashboardGenderField);

        /*
         * Prepare the combinationdashboardchart Chart
         */
        $categories = range(date('Y')-10, date('Y'));
        $emplyomentData = array();
        $retirementData = array();
        foreach($categories as $category){
            $returnValue = $this->arraySearchAction(strval($category),$dashboardEmplyomentChart);
            if( $returnValue != false){
                $emplyomentData[] = $dashboardEmplyomentChart[$returnValue]['total'];
            }else{
                $emplyomentData[] = 0;
            }

            $returnValue = $this->arraySearchAction(strval($category),$dashboardRetirementChart);
            if( $returnValue != false){
                $retirementData[] = $dashboardRetirementChart[$returnValue]['total'];
            }else{
                $retirementData[] = 0;
            }

        }
        //get the values data
        $series = array(
            array(
                'name'  => 'Employment',
                'type'  => 'column',
                'color' => '#0D0DC1',
                'yAxis' => 0,
                'data'  => $emplyomentData,
            ),
            array(
                'name'  => 'Retirement',
                'type'  => 'spline',
                'color' => '#AA4643',
                'yAxis' => 1,
                'data'  => $retirementData,
                'dashStyle'=>'longdash',
            ),
            array(
                'name'  => 'Sex',
                'type'  => 'pie',
                'center'=> array(100,30),
                'size'=> 100,
                'showInLegend'=> false,
                'dataLabels'=> array('enabled'=>true),
                'data'  => array(
                    array(
                        'name'=>'Female',
                        'y'=> $dashboardGenderChart[0]['total'],
                        'color'=>'#66ECA0'
                    ),
                    array(
                        'name'=>'Male',
                        'y'=>$dashboardGenderChart[1]['total'],
                        'color'=>'#9494d4'
                    ),
                ),
            ),
        );
        $yData = array(
            array(
                'labels' => array(
                    'formatter' => new Expr('function () { return this.value + "" }'),
                    'style'     => array('color' => '#0D0DC1')
                ),
                'title' => array(
                    'text'  => 'Employments',
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
                'title' => array(
                    'text'  => 'Retirements',
                    'style' => array('color' => '#AA4643')
                ),
            ),
        );


        $combinationdashboardchart = new Highchart();
        $combinationdashboardchart->chart->renderTo('chart_placeholder'); // The #id of the div where to render the chart
        $combinationdashboardchart->chart->type('column');
        $combinationdashboardchart->title->text('Employment Distribution, Retirement Distribution with Gender Report');
        $combinationdashboardchart->subtitle->text($user->getOrganisationunit()->getLongname().' with lower levels');
        $combinationdashboardchart->xAxis->categories($categories);
        $combinationdashboardchart->yAxis($yData);
        $combinationdashboardchart->legend->enabled(false);
        $formatter = new Expr('function () {
                 var unit = {
                     "Retirement": "retirements",
                     "Employment": "employments",
                     "Sex":"employees"
                 }[this.series.name];
                 if(this.point.name) {
                    return ""+this.point.name+": <b>"+ this.y+"</b> "+ unit;
                 }else {
                    return this.x + ": <b>" + this.y + "</b> " + unit;
                 }
             }');
        $combinationdashboardchart->tooltip->formatter($formatter);
        $combinationdashboardchart->series($series);

        /*
         * Prepare the Retirement Chart
         */
        $categories = range(date('Y'), date('Y')+10);
        $retirementData = array();
        foreach($categories as $category){
            $returnValue = $this->arraySearchAction(strval($category),$dashboardRetirementChart);
            if( $returnValue != false){
                $retirementData[] = $dashboardRetirementChart[$returnValue]['total'];
            }else{
                $retirementData[] = 0;
            }
        }

        $retirementChart = $this->constructChartAction($dashboardRetirementField,$retirementData,$user->getOrganisationunit(),$categories,'column','Retirement Distribution','retirementdistribution');

        /*
        * Prepare the Age Distribution Chart
        */
        foreach($dashboardAgeChart as $dashboardAgeCharts){
            $categories[] = $dashboardAgeCharts[strtolower($dashboardAgeField->getName())];
            $data[] =  $dashboardAgeCharts['total'];
        }
        $ageChart = $this->constructChartAction($dashboardAgeField,$data,$user->getOrganisationunit(),$categories,'column','Age Distribution','agedistribution');

        /*
         * Messaging
         */
        $provider = $this->get('fos_message.provider');

        $unreadMessages = $provider->getNbUnreadMessages();
        if(empty($unreadMessages)) $unreadMessages= 0;

        return array(
            'combinationchart'=>$combinationdashboardchart,
            'retirementchart'=>$retirementChart,
            'agechart'=>$ageChart,
            'unreadmessages'=>$unreadMessages
        );
    }
    /**
     * Finds and displays a dashboards for the user.
     *
     * @Route("/dashboard", name="dashboard_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction()
    {
        $em = $this->getDoctrine()->getManager();
        exit();
    }

    /**search the multidimension array for a needle
     * @param $needle
     * @param $haystack
     * @return bool|int|string
     */
    private function arraySearchAction($needle,$haystack) {
        foreach($haystack as $key=>$value) {
            $current_key=$key;
            if($needle===$value OR (is_array($value) && $this->arraySearchAction($needle,$value) !== false)) {
                return $current_key;
            }
        }
        return false;
    }

    private  function constructChartAction($field,$data,$organisationUnit,$categories, $graph, $title,$placeholder){


        $series = array(
            array(
                'name'  => $field->getName(),
                'data'  => $data,
            ),
        );
        $formatterLabel = $field->getCaption();

        $yData = array(
            array(
                'labels' => array(
                    'formatter' => new Expr('function () { return this.value + "" }'),
                    'style'     => array('color' => '#0D0DC1')
                ),
                'title' => array(
                    'text'  => $field->getCaption(),
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
                    'text'  => $field->getCaption(),
                    'style' => array('color' => '#AA4643')
                ),
            ),
        );

        $dashboardchart = new Highchart();
        $dashboardchart->chart->renderTo($placeholder); // The #id of the div where to render the chart
        $dashboardchart->chart->type($graph);
        $dashboardchart->title->text($title);
        $dashboardchart->subtitle->text($organisationUnit->getLongname().' with lower levels');
        $dashboardchart->xAxis->categories($categories);
        $dashboardchart->yAxis($yData);
        if($field->getId() == $field->getId())$dashboardchart->legend->enabled(true); else $dashboardchart->legend->enabled(true);

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
        if($graph == 'pie')$dashboardchart->plotOptions->pie(array('allowPointSelect'=> true,'dataLabels'=> array ('format'=> '<b>{point.name}</b>: {point.percentage:.1f} %')));
        $dashboardchart->series($series);
        return $dashboardchart;
    }
}
