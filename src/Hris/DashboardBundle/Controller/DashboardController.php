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

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ob\HighchartsBundle\Highcharts\Highchart;
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
        $series = array(
            array(
                'name'  => 'Employment',
                'type'  => 'column',
                'color' => '#0D0DC1',
                'yAxis' => 1,
                'data'  => array(1952,1058,1805,2082,2964,4243,3251,4979,2991,3171,426),
            ),
            array(
                'name'  => 'Retirement',
                'type'  => 'spline',
                'color' => '#AA4643',
                'data'  => array(994,1503,1119,1380,1289,1840,1633,2048,1496,2045,1836),
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
                        'y'=> 41848,
                        'color'=>'#66ECA0'
                    ),
                    array(
                        'name'=>'Male',
                        'y'=>20315,
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
        $categories = array('2003', '2004', '2005', '2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013');

        $dashboardchart = new Highchart();
        $dashboardchart->chart->renderTo('chart_placeholder'); // The #id of the div where to render the chart
        $dashboardchart->chart->type('column');
        $dashboardchart->title->text('Employment Distribution');
        $dashboardchart->subtitle->text('Ministry of Health And Social Welfare with lower levels');
        $dashboardchart->xAxis->categories($categories);
        $dashboardchart->yAxis($yData);
        $dashboardchart->legend->enabled(false);
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
        $dashboardchart->tooltip->formatter($formatter);
        $dashboardchart->series($series);

        /*
         * Messaging
         */
        $provider = $this->get('fos_message.provider');

        $unreadMessages = $provider->getNbUnreadMessages();
        if(empty($unreadMessages)) $unreadMessages= 0;

        return array(
            'chart'=>$dashboardchart,
            'unreadmessages'=>$unreadMessages
        );
    }
}
