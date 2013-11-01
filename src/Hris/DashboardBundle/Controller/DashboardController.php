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
namespace Hris\DashboardBundle\Controller;

use Hris\DashboardBundle\Entity\DashboardChart;
use Symfony\Component\HttpFoundation\Request;
use Hris\DashboardBundle\Form\DashboardType;
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
     * Note users without dashboard but with profile permissions
     * will be allowed in and redirected to profile.
     *
     * @Secure(roles="ROLE_DASHBOARD_HOME,ROLE_USERPROFILE_SHOW")
     *
     * @Route("/", name="hris_homepage")
     * @Route("/", name="dashboard")
     * @Route("/dashboard/", name="hris_dashboard_homepage")
     * @Method("GET")
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $securityContext = $this->container->get('security.context');
        if(! $securityContext->isGranted('ROLE_DASHBOARD_HOME'))
        {
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }
        //get the detail for the active user
        $user = $this->container->get('security.context')->getToken()->getUser();
        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
        $reportAggregationController =  New ReportAggregationController();
        $entityManager = $this->getDoctrine()->getManager();
        $organisationunitGroups = new ArrayCollection();
        $fieldTwo = $entityManager->getRepository('HrisFormBundle:Field')->findOneByName(array('name'=> "ContactsofNextofKin"));



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
        $organisationunit = $user->getOrganisationunit();
        if(empty($organisationunit)) $organisationunit =  $this->getDoctrine()->getManager()->createQuery('SELECT organisationunit FROM HrisOrganisationunitBundle:Organisationunit organisationunit WHERE organisationunit.parent IS NULL')->getSingleResult();
        $dashboardEmplyomentChart = $reportAggregationController::aggregationEngine($organisationunit, $forms,$dashboardEmplyomentField, $organisationunitGroups, TRUE, $dashboardEmplyomentField);

        $dashboardRetirementChart = $reportAggregationController::aggregationEngine($organisationunit, $forms,$dashboardRetirementField, $organisationunitGroups, TRUE, $dashboardRetirementField);

        $dashboardAgeChart = $reportAggregationController::aggregationEngine($organisationunit, $forms,$dashboardAgeField, $organisationunitGroups, TRUE, $dashboardAgeField);

        $dashboardGenderChart = $reportAggregationController::aggregationEngine($organisationunit, $forms, $dashboardGenderField, $organisationunitGroups, TRUE, $dashboardGenderField);

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
        $combinationdashboardchart->subtitle->text($organisationunit->getLongname().' with lower levels');
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

        $retirementChart = $this->constructChartAction($dashboardRetirementField,$dashboardRetirementField,$retirementData,$organisationunit,$categories,'column','Retirement Distribution','retirementdistribution');

        /*
        * Prepare the Age Distribution Chart
        */
        $categories = NULL;
        $data = NULL;
        foreach($dashboardAgeChart as $dashboardAgeCharts){
            $categories[] = $dashboardAgeCharts[strtolower($dashboardAgeField->getName())];
            $data[] =  $dashboardAgeCharts['total'];
        }
        $ageChart = $this->constructChartAction($dashboardAgeField,$dashboardAgeField,$data,$organisationunit,$categories,'column','Age Distribution','agedistribution');

        /*
         * Messaging
         */
        $provider = $this->get('fos_message.provider');

        $unreadMessages = $provider->getNbUnreadMessages();
        if(empty($unreadMessages)) $unreadMessages= 0;

        /*
        * Prepare the User Defined Dashboard Charts
        */
        $entitiesChart = array();
        $entities = $queryBuilder->select('dashboard')
            ->from('HrisDashboardBundle:DashboardChart','dashboard')
            ->where('dashboard.user = :uname')
            ->orWhere('dashboard.systemWide = :systemwide')
            ->setParameters(array(
                    'uname'=>$user,
                    'systemwide'=>true,
                )
            )->getQuery()->getResult();

        foreach($entities as $entity){
            $categories = NULL;
            $data = NULL;

            #TODO Deal with multi select of the organisation unit
            foreach($entity->getOrganisationunit() as $organisationunitEntity){
                $organisationunit = $organisationunitEntity;
            }
            $forms = new ArrayCollection();
            foreach($entity->getForm() as $form){
                $forms->add($form);
            }

            $entitiesData = $reportAggregationController::aggregationEngine($organisationunit, $forms, $entity->getFieldOne(),$organisationunitGroups, $entity->getLowerLevels(), $entity->getFieldTwo());

            //if only one field selected
            if($entity->getFieldOne()->getId() == $entity->getFieldTwo()->getId()){

                foreach($entitiesData as $result){
                    $categories[] = $result[strtolower($entity->getFieldOne()->getName())];
                    $data[] =  $result['total'];

                    if($entity->getGraphType() == 'pie'){
                        $piedata[] = array('name' => $result[strtolower($entity->getFieldOne()->getName())],'y' => $result['total']);
                    }
                }
                if($entity->getGraphType() == 'pie') $data = $piedata;


            }else{//Two fields selected
                foreach($entitiesData as $result){
                    $keys[$result[strtolower($entity->getFieldTwo()->getName())]][] = $result['total'];
                    $categoryKeys[$result[strtolower($entity->getFieldOne()->getName())]] = $result['total'];
                }
                $series = array();
                foreach($keys as $key => $values){
                    $series[] = array(
                        'name'  => $key,
                        'yAxis' => 1,
                        'data'  => $values,
                    );
                }
                $formatterLabel = $entity->getFieldTwo()->getCaption();
                $categories = array_keys($categoryKeys);
                $data = $series;
            }


            //check which type of chart to display
            if($entity->getGraphType() == "bar"){
                $graph = "column";
            }elseif($entity->getGraphType() == "line"){
                $graph = "spline";
            }else{
                $graph = "pie";
            }
            $entitiesChart[] = $this->constructChartAction($entity->getFieldOne(),$entity->getFieldTwo(),$data,$organisationunit,$categories,$graph,$entity->getName(),str_replace(' ','_',strtolower($entity->getName())));

        }

        return array(
            'combinationchart'=>$combinationdashboardchart,
            'retirementchart'=>$retirementChart,
            'agechart'=>$ageChart,
            'unreadmessages'=>$unreadMessages,
            'entitiesChart' =>$entitiesChart,
            'entities' => $entities
        );
    }
    /**
     * Finds and displays a dashboards for the user.
     *
     * @Secure(roles="ROLE_DASHBOARD_LIST")
     * @Route("/dashboardList", name="dashboard_list")
     * @Method("GET")
     * @Template("")
     */
    public function listAction()
    {
        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();

        //get the detail for the active user
        $user = $this->container->get('security.context')->getToken()->getUser();

        $entities = $queryBuilder->select('dashboard')
            ->from('HrisDashboardBundle:DashboardChart','dashboard')
            ->where('dashboard.user = :uname')
            ->orWhere('dashboard.systemWide = :systemwide')
            ->setParameters(array(
                    'uname'=>$user,
                    'systemwide'=>true,
                )
            )->getQuery()->getResult();

        $delete_forms = array();
        foreach($entities as $entity) {
            $delete_form= $this->createDeleteForm($entity->getId());
            $delete_forms[$entity->getId()] = $delete_form->createView();
        }//get the detail for the active user
        $user = $this->container->get('security.context')->getToken()->getUser();

        return array(
            'entities' => $entities,
            'delete_forms' => $delete_forms,
        );
    }
    /**
     * Adds a new dashboards for the user.
     *
     * @Secure(roles="ROLE_DASHBOARD_CREATE")
     * @Route("/dashboardAdd", name="dashboard_new")
     * @Method("GET")
     * @Template("")
     */
    public function newAction()
    {
        $entity = new DashboardChart();
        $aggregationForm = $this->createForm(new DashboardType(),$entity,array('em'=>$this->getDoctrine()->getManager()));

        return array(
            'aggregationForm'=>$aggregationForm->createView(),
        );
    }

    /**
     * Generate aggregated reports
     *
     * @Secure(roles="ROLE_DASHBOARD_CREATE")
     * @Route("/", name="dashboard_create")
     * @Method("POST")
     * @Template("")
     */
    public function createAction(Request $request)
    {
        $entity  = new DashboardChart();

        $form = $this->createForm(new DashboardType($this->getDoctrine()->getManager()),$entity, array('em'=>$this->getDoctrine()->getManager()));
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $requestcontent = $request->request->get('hris_dashboardbundle_dashboardtype');
            $organisationunitId = $requestcontent['organisationunit'];
            $organisationunit = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($organisationunitId);
            $entity->addOrganisationunit($organisationunit);
            $entity->setUser($this->container->get('security.context')->getToken()->getUser());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dashboard_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    /**
     * Finds and displays a Dashboard entity.
     *
     * @Secure(roles="ROLE_DASHBOARD_SHOW")
     * @Route("/{id}", requirements={"id"="\d+"}, name="dashboard_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisDashboardBundle:DashboardChart')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Dashboard entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Finds and Show the Dashboard entity to edit.
     *
     * @Secure(roles="ROLE_DASHBOARD_UPDATE")
     * @Route("/{id}/edit",  name="dashboard_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisDashboardBundle:DashboardChart')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Dashboard entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DashboardType(),$entity,array('em'=>$this->getDoctrine()->getManager()));

        return array(
            'entity'      => $entity,
            'aggregationForm' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Updates a Dashboard entity.
     *
     * @Secure(roles="ROLE_DASHBOARD_UPDATE")
     * @Route("/{id}",requirements={"id"="\d+"},  name="dashboard_update")
     * @Method("POST")
     * @Template()
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisDashboardBundle:DashboardChart')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DashboardChart entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DashboardType(), $entity,array('em'=>$this->getDoctrine()->getManager()));
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dashboard_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Finds and delete a Dashboard entity.
     *
     * @Secure(roles="ROLE_DASHBOARD_DELETE")
     * @Route("/{id}",  name="dashboard_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisDashboardBundle:DashboardChart')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Dashboard entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('dashboard_list'));
    }


    /**
     * Creates a form to delete a Dashboard entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
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

    private  function constructChartAction($fieldOne,$fieldTwo,$data,$organisationUnit,$categories, $graph, $title,$placeholder){


        if($fieldOne->getId() == $fieldTwo->getId()){
            $series = array(
                array(
                    'name'  => $fieldOne->getName(),
                    'data'  => $data,
                ),
            );
            $formatterLabel = $fieldOne->getCaption();
        }else{
            $series = $data;
            $formatterLabel = $fieldTwo->getCaption();
        }

        $yData = array(
            array(
                'labels' => array(
                    'formatter' => new Expr('function () { return this.value + "" }'),
                    'style'     => array('color' => '#0D0DC1')
                ),
                'title' => array(
                    'text'  => $fieldOne->getCaption(),
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
                    'text'  => $fieldOne->getCaption(),
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
        if($fieldOne->getId() == $fieldTwo->getId())$dashboardchart->legend->enabled(true); else $dashboardchart->legend->enabled(true);

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
