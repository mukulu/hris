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
 * @author Ismail Yusuf Koleleni <ismailkoleleni@gmail.com>
 *
 */
namespace Hris\ReportsBundle\Controller;

use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Hris\FormBundle\Entity\Form;
use Hris\FormBundle\Entity\Field;
use Hris\ReportsBundle\Form\ReportHistoryTrainingType;
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
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Report HistoryTraining controller.
 *
 * @Route("/reports/historytraining")
 */
class ReportHistoryTrainingController extends Controller
{

    /**
     * Show Report HistoryTraining
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_REPORTHISTORY_GENERATE,ROLE_USER")
     * @Route("/", name="report_historytraining")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

        $historytrainingForm = $this->createForm(new ReportHistoryTrainingType($this->getUser()),null,array('em'=>$this->getDoctrine()->getManager()));

        return array(
            'historytrainingForm'=>$historytrainingForm->createView(),
        );
    }

    /**
     * Generate aggregated reports
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_REPORTHISTORY_GENERATE,ROLE_USER")
     * @Route("/", name="report_historytraining_generate")
     * @Method("PUT")
     * @Template()
     */
    public function generateAction(Request $request)
    {
        $historytrainingForm = $this->createForm(new ReportHistoryTrainingType($this->getUser()),null,array('em'=>$this->getDoctrine()->getManager()));
        $historytrainingForm->bind($request);

        if ($historytrainingForm->isValid()) {
            $historytrainingFormData = $historytrainingForm->getData();
            $organisationUnit = $historytrainingFormData['organisationunit'];
            $forms = $historytrainingFormData['forms'];
            $reportType = $historytrainingFormData['reportType'];
            $withLowerLevels = $historytrainingFormData['withLowerLevels'];
            $fields = $historytrainingFormData['fields'];
            $graphType = $historytrainingFormData['graphType'];
        }

        //Create fields object is not passed from form
        if(is_null($fields)){
            $fields = new Field();
        }

        $results = $this->aggregationEngine($organisationUnit, $forms, $fields, $reportType, $withLowerLevels);

        //print_r($results);exit;

        //Get the Id for the form
        $formsId = $forms->getId();

        //If training report generation
        if( $reportType == "training" ){

            foreach($results as $result){
                $categories[] = $result['data'];
                $data[] =  $result['total'];

                if($graphType == 'pie'){
                    $piedata[] = array('name' => $result['data'],'y' => $result['total']);
                }
            }
            if($graphType == 'pie') $data = $piedata;
            $series = array(
                array(
                    'name'  => "Trainings",
                    'data'  => $data,
                ),
            );
            if ($withLowerLevels){
                $withLower = " with lower levels";
            }
            $formatterLabel = 'Trainings';
            $subtitle = "Trainings";

        }else if( $reportType == "history" ){

            foreach($results as $result){
                $categories[] = $result['data'];
                $data[] =  $result['total'];

                if($graphType == 'pie'){
                    $piedata[] = array('name' => $result['data'],'y' => $result['total']);
                }
            }
            if($graphType == 'pie') $data = $piedata;
            $series = array(
                array(
                    'name'  => $fields->getCaption(),
                    'data'  => $data,
                ),
            );
            if ($withLowerLevels){
                $withLower = " with lower levels";
            }
            $formatterLabel = $fields->getCaption();
            $subtitle = $fields->getCaption()." History";
        }

        //check which type of chart to display
        if($graphType == "bar"){
            $graph = "column";
        }elseif($graphType == "line"){
            $graph = "spline";
        }else{
            $graph = "pie";
        }
        //set the title and sub title
        $title = $subtitle." Distribution Report";

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
                    'text'  => $subtitle,
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
                'text'  => $subtitle,
                'style' => array('color' => '#AA4643')
            ),
        ),
        );

        $dashboardchart = new Highchart();
        $dashboardchart->chart->renderTo('chart_placeholder_historytraining'); // The #id of the div where to render the chart
        $dashboardchart->chart->type($graph);
        $dashboardchart->title->text($title);
        $dashboardchart->subtitle->text($organisationUnit->getLongname().$withLower);
        $dashboardchart->xAxis->categories($categories);
        $dashboardchart->yAxis($yData);
        $dashboardchart->legend->enabled(true);

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
        if($graphType == 'pie') $dashboardchart->plotOptions->pie(array('allowPointSelect'=> true,'dataLabels'=> array ('format'=> '<b>{point.name}</b>: {point.percentage:.1f} %')));
        $dashboardchart->series($series);

        return array(
            'chart'=>$dashboardchart,
            'data'=>$data,
            'categories'=>$categories,
            'organisationUnit' => $organisationUnit,
            'formsId' => $formsId,
            'reportType' => $reportType,
            'withLowerLevels' => $withLowerLevels,
            'fields' => $fields,
            'title'=> $title,
        );
    }


    /**
     * Aggregation Engine
     *
     * @param Organisationunit $organisationUnit
     * @param Form $forms
     * @param Field $fields
     * @param $reportType
     * @param $withLowerLevels
     * @return mixed
     */
    private function aggregationEngine(Organisationunit $organisationUnit,  Form $forms, Field $fields, $reportType, $withLowerLevels)
    {

        $entityManager = $this->getDoctrine()->getManager();
        //$selectedOrgunitStructure = $entityManager->getRepository('HrisOrganisationunitBundle:OrganisationunitStructure')->findOneBy(array('organisationunit' => $organisationUnit->getId()));

        if ($reportType == "training") {
            //Query all lower levels units from the passed orgunit
            if($withLowerLevels){
                $allChildrenIds = "SELECT hris_organisationunitlevel.level ";
                $allChildrenIds .= "FROM hris_organisationunitlevel , hris_organisationunitstructure ";
                $allChildrenIds .= "WHERE hris_organisationunitlevel.id = hris_organisationunitstructure.level_id AND hris_organisationunitstructure.organisationunit_id = ". $organisationUnit->getId();
                $subQuery = "V.organisationunit_id = ". $organisationUnit->getId() . " OR ";
                $subQuery .= " ( L.level >= ( ". $allChildrenIds .") AND S.level".$organisationUnit->getOrganisationunitStructure()->getLevel()->getLevel()."_id =".$organisationUnit->getId()." )";
            }else{
                $subQuery = "V.organisationunit_id = ". $organisationUnit->getId();
            }

            //Query all training data and count by start date year
            $query = "SELECT date_part('year',startdate) as data, count(date_part('year',startdate)) as total ";
            $query .= "FROM hris_record_training T ";
            $query .= "INNER JOIN hris_record as V on V.id = T.record_id ";
            $query .= "INNER JOIN hris_organisationunitstructure as S on S.organisationunit_id = V.organisationunit_id ";
            $query .= "INNER JOIN hris_organisationunitlevel as L on L.id = S.level_id ";
            $query .= "WHERE V.form_id = ". $forms->getId();
            $query .= " AND (". $subQuery .") ";
            $query .= " GROUP BY date_part('year',startdate) ";
            $query .= "ORDER BY data ASC";

        }else{
            if ($fields->getInputType()->getName() == "Select"){

                //Query all lower levels units from the passed orgunit
                if($withLowerLevels){
                    $allChildrenIds = "SELECT hris_organisationunitlevel.level ";
                    $allChildrenIds .= "FROM hris_organisationunitlevel , hris_organisationunitstructure ";
                    $allChildrenIds .= "WHERE hris_organisationunitlevel.id = hris_organisationunitstructure.level_id AND hris_organisationunitstructure.organisationunit_id = ". $organisationUnit->getId();
                    $subQuery = "V.organisationunit_id = ". $organisationUnit->getId() . " OR ";
                    $subQuery .= " ( L.level >= ( ". $allChildrenIds .") AND S.level".$organisationUnit->getOrganisationunitStructure()->getLevel()->getLevel()."_id =".$organisationUnit->getId()." )";
                }else{
                    $subQuery = "V.organisationunit_id = ". $organisationUnit->getId();
                }

                //Query all history data and count by field option
                $query = "SELECT H.history as data, count (H.history) as total ";
                $query .= "FROM hris_record_history H ";
                $query .= "INNER JOIN hris_record as V on V.id = H.record_id ";
                $query .= "INNER JOIN hris_organisationunitstructure as S on S.organisationunit_id = V.organisationunit_id ";
                $query .= "INNER JOIN hris_organisationunitlevel as L on L.id = S.level_id ";
                $query .= "WHERE V.form_id = ". $forms->getId()." AND H.field_id = ". $fields->getId();
                $query .= " AND (". $subQuery .") ";
                $query .= " GROUP BY H.history ";
                $query .= " ORDER BY data ASC";


            }else{  //For other fields which are not combo box, report is based on history dates

                //$subQuery="select Distinct(T.id),T.instance,date_part('year', startdate) from hris_history T, hris_values V where T.instance= V.instance AND V.form_id =".$forms->getId()." AND T.history_type_id = ".$fields->getId()."AND V.orgunit_id in (".$orgunitsid." )";
                //$query = "SELECT F.date_part as data, count (F.date_part) FROM (".$subQuery.") as F GROUP BY F.date_part";

                //Query all lower levels units from the passed orgunit
                if($withLowerLevels){
                    $allChildrenIds = "SELECT hris_organisationunitlevel.level ";
                    $allChildrenIds .= "FROM hris_organisationunitlevel , hris_organisationunitstructure ";
                    $allChildrenIds .= "WHERE hris_organisationunitlevel.id = hris_organisationunitstructure.level_id AND hris_organisationunitstructure.organisationunit_id = ". $organisationUnit->getId();
                    $subQuery = "V.organisationunit_id = ". $organisationUnit->getId() . " OR ";
                    $subQuery .= " ( L.level >= ( ". $allChildrenIds .") AND S.level".$organisationUnit->getOrganisationunitStructure()->getLevel()->getLevel()."_id =".$organisationUnit->getId()." )";
                }else{
                    $subQuery = "V.organisationunit_id = ". $organisationUnit->getId();
                }

                //Query all history data and history year
                $query = "SELECT date_part('year',startdate) as data, count(date_part('year',startdate)) as total ";
                $query .= "FROM hris_record_history H ";
                $query .= "INNER JOIN hris_record as V on V.id = H.record_id ";
                $query .= "INNER JOIN hris_organisationunitstructure as S on S.organisationunit_id = V.organisationunit_id ";
                $query .= "INNER JOIN hris_organisationunitlevel as L on L.id = S.level_id ";
                $query .= "WHERE V.form_id = ". $forms->getId()." AND H.field_id = ". $fields->getId();
                $query .= " AND (". $subQuery .") ";
                $query .= " GROUP BY date_part('year',startdate) ";
                $query .= " ORDER BY data ASC";
            }
        }
        //echo $query;exit;

        //get the records
        $report = $entityManager -> getConnection() -> executeQuery($query) -> fetchAll();
        return $report;
    }

    /**
     * Records Engine
     *
     * @param Organisationunit $organisationUnit
     * @param Form $forms
     * @param Field $fields
     * @param $reportType
     * @param $withLowerLevels
     * @return mixed
     */
    private function recordsEngine(Organisationunit $organisationUnit,  Form $forms, Field $fields, $reportType, $withLowerLevels)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $resourceTableName = "_resource_all_fields";

        if ($reportType == "training") {
            //Query all lower levels units from the passed orgunit
            if($withLowerLevels){
                $allChildrenIds = "SELECT hris_organisationunitlevel.level ";
                $allChildrenIds .= "FROM hris_organisationunitlevel , hris_organisationunitstructure ";
                $allChildrenIds .= "WHERE hris_organisationunitlevel.id = hris_organisationunitstructure.level_id AND hris_organisationunitstructure.organisationunit_id = ". $organisationUnit->getId();
                $subQuery = "V.organisationunit_id = ". $organisationUnit->getId() . " OR ";
                $subQuery .= " ( L.level >= ( ". $allChildrenIds .") AND S.level".$organisationUnit->getOrganisationunitStructure()->getLevel()->getLevel()."_id =".$organisationUnit->getId()." )";
            }else{
                $subQuery = "V.organisationunit_id = ". $organisationUnit->getId();
            }

            //Query all training data and count by start date year
            $query = "SELECT R.firstname, R.middlename, R.surname, R.designation, T.coursename, T.courselocation, T.sponsor, T.startdate, T.enddate, R.level5_facility ";
            $query .= "FROM hris_record_training T ";
            $query .= "INNER JOIN hris_record as V on V.id = T.record_id ";
            $query .= "INNER JOIN ".$resourceTableName." as R on R.instance = V.instance ";
            $query .= "INNER JOIN hris_organisationunitstructure as S on S.organisationunit_id = V.organisationunit_id ";
            $query .= "INNER JOIN hris_organisationunitlevel as L on L.id = S.level_id ";
            $query .= "WHERE V.form_id = ". $forms->getId();
            $query .= " AND (". $subQuery .") ";
            $query .= "ORDER BY R.firstname ASC";

        }else{

                //Query all lower levels units from the passed orgunit
                if($withLowerLevels){
                    $allChildrenIds = "SELECT hris_organisationunitlevel.level ";
                    $allChildrenIds .= "FROM hris_organisationunitlevel , hris_organisationunitstructure ";
                    $allChildrenIds .= "WHERE hris_organisationunitlevel.id = hris_organisationunitstructure.level_id AND hris_organisationunitstructure.organisationunit_id = ". $organisationUnit->getId();
                    $subQuery = "V.organisationunit_id = ". $organisationUnit->getId() . " OR ";
                    $subQuery .= " ( L.level >= ( ". $allChildrenIds .") AND S.level".$organisationUnit->getOrganisationunitStructure()->getLevel()->getLevel()."_id =".$organisationUnit->getId()." )";
                }else{
                    $subQuery = "V.organisationunit_id = ". $organisationUnit->getId();
                }

                //Query all history data and count by field option
                $query = "SELECT R.firstname, R.middlename, R.surname, R.designation, H.history, H.reason, H.startdate, R.level5_facility ";
                $query .= "FROM hris_record_history H ";
                $query .= "INNER JOIN hris_record as V on V.id = H.record_id ";
                $query .= "INNER JOIN ".$resourceTableName." as R on R.instance = V.instance ";
                $query .= "INNER JOIN hris_organisationunitstructure as S on S.organisationunit_id = V.organisationunit_id ";
                $query .= "INNER JOIN hris_organisationunitlevel as L on L.id = S.level_id ";
                $query .= "WHERE V.form_id = ". $forms->getId()." AND H.field_id = ". $fields->getId();
                $query .= " AND (". $subQuery .") ";
                $query .= " ORDER BY R.firstname ASC";
        }
        //echo $query;exit;

        //get the records
        $report = $entityManager -> getConnection() -> executeQuery($query) -> fetchAll();
        return $report;
    }

    /**
     * Download History reports
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_REPORTHISTORY_DOWNLOAD,ROLE_USER")
     * @Route("/download", name="report_historytraining_download")
     * @Method("GET")
     * @Template()
     */
    public function downloadAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $organisationUnitid =$request->query->get('organisationUnit');
        $formsId = $request->query->get('formsId');
        $reportType = $request->query->get('reportType');
        $withLowerLevels =$request->query->get('withLowerLevels');
        $fieldsId =$request->query->get('fields');

        //Get the objects from the the variables

        $organisationUnit = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($organisationUnitid);
        $forms = $em->getRepository('HrisFormBundle:Form')->find($formsId);

        if(is_null($fieldsId)){
            $fields = new Field();
        }
        else{
            $fields = $em->getRepository('HrisFormBundle:Field')->find($fieldsId);
        }

        $results = $this->aggregationEngine($organisationUnit, $forms, $fields, $reportType, $withLowerLevels );


        //create the title
        if ($reportType == "training"){
            $subtitle = "Training";
        }
        elseif( $reportType = "history"){
            $subtitle = $fields->getCaption()." History";
        }

        $title = $subtitle. " Distribution Report ".$organisationUnit->getLongname();

        if($withLowerLevels){
            $title .= " with lower levels";
        }

        // ask the service for a Excel5
        $excelService = $this->get('xls.service_xls5');
        $excelService->excelObj->getProperties()->setCreator("HRHIS3")
            ->setLastModifiedBy("HRHIS3")
            ->setTitle($title)
            ->setSubject("Office 2005 XLSX Test Document")
            ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("Test result file");

        //write the header of the report
        $column = 'A';
        $row  = 1;
        $date = "Date: ".date("jS F Y");
        $excelService->excelObj->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
        $excelService->excelObj->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
        $excelService->excelObj->setActiveSheetIndex(0)
            ->setCellValue($column.$row++, $title)
            ->setCellValue($column.$row, $date);
        //add style to the header
        $heading_format = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '3333FF'),
            ),
            'alignment' => array(
                'wrap'       => true,
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
        );
        //add style to the Value header
        $header_format = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => 'FFFFFF'),
            ),
            'alignment' => array(
                'wrap'       => true,
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => '000099') ,
            ),
        );
        //add style to the text to display
        $text_format1 = array(
            'font' => array(
                'bold' => false,
                'color' => array('rgb' => '000000'),
            ),
            'alignment' => array(
                'wrap'       => true,
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
        );
        //add style to the Value header
        $text_format2 = array(
            'font' => array(
                'bold' => false,
                'color' => array('rgb' => '000000'),
            ),
            'alignment' => array(
                'wrap'       => true,
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => 'E0E0E0') ,
            ),
        );

        $excelService->excelObj->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
        $excelService->excelObj->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

        //reset the colomn and row number
        $column == 'A';
        $row += 2;

        //Start populating excel with data
        if ($reportType == "history") {

            /*print_r($results);exit;
            foreach($results as $result){
                $keys[$result[strtolower($fields->getName())]][$result[strtolower($fieldsTwo->getName())]] = $result['total'];
                //$categoryKeys[$result[strtolower($fields->getName())]] = $result['total'];

            }*/

            //apply the styles
            $excelService->excelObj->getActiveSheet()->getStyle('A1:D2')->applyFromArray($heading_format);
            $excelService->excelObj->getActiveSheet()->mergeCells('A1:D1');
            $excelService->excelObj->getActiveSheet()->mergeCells('A2:D2');

            //write the table heading of the values
            $excelService->excelObj->getActiveSheet()->getStyle('A4:D4')->applyFromArray($header_format);
            $excelService->excelObj->setActiveSheetIndex(0)
                ->setCellValue($column++.$row, 'SN')
                ->setCellValue($column++.$row, $fields->getCaption())
                ->setCellValue($column.$row, 'Value');

            /*$fieldOptions = $em->getRepository('HrisFormBundle:FieldOption')->findBy(array('field'=>$fieldsTwo));

            foreach ($fieldOptions as $fieldOption) {
                $excelService->excelObj->setActiveSheetIndex(0)->setCellValue($column++.$row, $fieldOption->getValue());
            }*/

            //write the values
            $i =1; //count the row
            foreach($results as $result){
                $column = 'A';//return to the 1st column
                $row++; //increment one row

                //format of the row
                if (($row % 2) == 1)
                    $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':D'.$row)->applyFromArray($text_format1);
                else
                    $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':D'.$row)->applyFromArray($text_format2);
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row, $i++)
                    ->setCellValue($column++.$row, $result['data'])
                    ->setCellValue($column++.$row, $result['total']);

                /*foreach ($items as $item) {
                    $excelService->excelObj->setActiveSheetIndex(0)->setCellValue($column++.$row, $item);
                }*/
            }

        }elseif ($reportType == "training" ){
            //apply the styles
            $excelService->excelObj->getActiveSheet()->getStyle('A1:C2')->applyFromArray($heading_format);
            $excelService->excelObj->getActiveSheet()->mergeCells('A1:C1');
            $excelService->excelObj->getActiveSheet()->mergeCells('A2:C2');

            //write the table heading of the values
            $excelService->excelObj->getActiveSheet()->getStyle('A4:C4')->applyFromArray($header_format);
            $excelService->excelObj->setActiveSheetIndex(0)
                ->setCellValue($column++.$row, 'SN')
                ->setCellValue($column++.$row, 'Year')
                ->setCellValue($column.$row, 'Value');

            //write the values
            $i =1; //count the row
            foreach($results as $result){
                $column = 'A';//return to the 1st column
                $row++; //increment one row

                //format of the row
                if (($row % 2) == 1)
                    $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':C'.$row)->applyFromArray($text_format1);
                else
                    $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':C'.$row)->applyFromArray($text_format2);
                    $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row, $i++)
                    ->setCellValue($column++.$row, $result['data'])
                    ->setCellValue($column.$row, $result['total']);

            }
        }

        $excelService->excelObj->getActiveSheet()->setTitle('Training-History Report');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $excelService->excelObj->setActiveSheetIndex(0);

        //create the response

        $response = $excelService->getResponse();
        $response->headers->set('Content-Type', 'application/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$title.'.xls');

        // If you are using a https connection, you have to set those two headers and use sendHeaders() for compatibility with IE <9
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        //$response->sendHeaders();
        return $response;

    }

    /**
     * Download history reports by Cadre
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_REPORTHISTORY_DOWNLOADBYCADRE,ROLE_USER")
     * @Route("/records", name="report_historytraining_download_records")
     * @Method("GET")
     * @Template()
     */
    public function recordsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $organisationUnitid =$request->query->get('organisationUnit');
        $formsId = $request->query->get('formsId');
        $reportType = $request->query->get('reportType');
        $withLowerLevels =$request->query->get('withLowerLevels');
        $fieldsId =$request->query->get('fields');

        //Get the objects from the the variables

        $organisationUnit = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($organisationUnitid);
        $forms = $em->getRepository('HrisFormBundle:Form')->find($formsId);

        if(is_null($fieldsId)){
            $fields = new Field();
        }
        else{
            $fields = $em->getRepository('HrisFormBundle:Field')->find($fieldsId);
        }

        $results = $this->recordsEngine($organisationUnit ,$forms, $fields, $reportType, $withLowerLevels );

        //create the title
        if ($reportType == "training"){
            $subtitle = "In Service Training";
        }
        elseif( $reportType = "history"){
            $subtitle = $fields->getCaption()." History";
        }

        $title = $subtitle. " Report ".$organisationUnit->getLongname();

        if($withLowerLevels){
            $title .= " with lower levels";
        }


        // ask the service for a Excel5
        $excelService = $this->get('xls.service_xls5');
        $excelService->excelObj->getProperties()->setCreator("HRHIS3")
            ->setLastModifiedBy("HRHIS3")
            ->setTitle($title)
            ->setSubject("Office 2005 XLSX Test Document")
            ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("Test result file");

        //write the header of the report
        $column = 'A';
        $row  = 1;
        $date = "Date: ".date("jS F Y");
        $excelService->excelObj->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
        $excelService->excelObj->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
        $excelService->excelObj->setActiveSheetIndex(0)
            ->setCellValue($column.$row++, $title)
            ->setCellValue($column.$row, $date);
        //add style to the header
        $heading_format = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '3333FF'),
            ),
            'alignment' => array(
                'wrap'       => true,
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
        );
        //add style to the Value header
        $header_format = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => 'FFFFFF'),
            ),
            'alignment' => array(
                'wrap'       => true,
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => '000099') ,
            ),
        );
        //add style to the text to display
        $text_format1 = array(
            'font' => array(
                'bold' => false,
                'color' => array('rgb' => '000000'),
            ),
            'alignment' => array(
                'wrap'       => true,
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
        );
        //add style to the Value header
        $text_format2 = array(
            'font' => array(
                'bold' => false,
                'color' => array('rgb' => '000000'),
            ),
            'alignment' => array(
                'wrap'       => true,
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => 'E0E0E0') ,
            ),
        );

        $excelService->excelObj->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
        $excelService->excelObj->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

        //reset the colomn and row number
        $column == 'A';
        $row += 2;

        //Start populating excel with data
        if ($reportType == "history") {

            /*print_r($results);exit;
            foreach($results as $result){
                $keys[$result[strtolower($fields->getName())]][$result[strtolower($fieldsTwo->getName())]] = $result['total'];
                //$categoryKeys[$result[strtolower($fields->getName())]] = $result['total'];

            }*/

            //apply the styles
            $excelService->excelObj->getActiveSheet()->getStyle('A1:G2')->applyFromArray($heading_format);
            $excelService->excelObj->getActiveSheet()->mergeCells('A1:G1');
            $excelService->excelObj->getActiveSheet()->mergeCells('A2:G2');

            //write the table heading of the values
            $excelService->excelObj->getActiveSheet()->getStyle('A4:G4')->applyFromArray($header_format);
            $excelService->excelObj->setActiveSheetIndex(0)
                ->setCellValue($column++.$row, 'SN')
                ->setCellValue($column++.$row, 'Name')
                ->setCellValue($column++.$row, 'Designation')
                ->setCellValue($column++.$row, 'History')
                ->setCellValue($column++.$row, 'Reason')
                ->setCellValue($column++.$row, 'Date')
                ->setCellValue($column.$row, 'Duty Post');

            /*$fieldOptions = $em->getRepository('HrisFormBundle:FieldOption')->findBy(array('field'=>$fieldsTwo));

            foreach ($fieldOptions as $fieldOption) {
                $excelService->excelObj->setActiveSheetIndex(0)->setCellValue($column++.$row, $fieldOption->getValue());
            }*/

            //write the values
            $i =1; //count the row
            foreach($results as $result){
                $column = 'A';//return to the 1st column
                $row++; //increment one row

                //format of the row
                if (($row % 2) == 1)
                    $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':G'.$row)->applyFromArray($text_format1);
                else
                    $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':G'.$row)->applyFromArray($text_format2);
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row, $i++)
                    ->setCellValue($column++.$row, $result['firstname']." ".$result['middlename']." ".$result['surname'])
                    ->setCellValue($column++.$row, $result['presentdesignation'])
                    ->setCellValue($column++.$row, $result['history'])
                    ->setCellValue($column++.$row, $result['reason'])
                    ->setCellValue($column++.$row, $result['startdate'])
                    ->setCellValue($column.$row, $result['level5_level_5']);

                /*foreach ($items as $item) {
                    $excelService->excelObj->setActiveSheetIndex(0)->setCellValue($column++.$row, $item);
                }*/
            }

        }elseif ($reportType == "training" ){
            //apply the styles
            $excelService->excelObj->getActiveSheet()->getStyle('A1:I2')->applyFromArray($heading_format);
            $excelService->excelObj->getActiveSheet()->mergeCells('A1:I1');
            $excelService->excelObj->getActiveSheet()->mergeCells('A2:I2');

            //write the table heading of the values
            $excelService->excelObj->getActiveSheet()->getStyle('A4:I4')->applyFromArray($header_format);
            $excelService->excelObj->setActiveSheetIndex(0)
                ->setCellValue($column++.$row, 'SN')
                ->setCellValue($column++.$row, 'Name')
                ->setCellValue($column++.$row, 'Designation')
                ->setCellValue($column++.$row, 'Course Name')
                ->setCellValue($column++.$row, 'Course Location')
                ->setCellValue($column++.$row, 'Sponsor')
                ->setCellValue($column++.$row, 'Start Date')
                ->setCellValue($column++.$row, 'End Date')
                ->setCellValue($column.$row, 'Duty Post');

            //write the values
            $i =1; //count the row
            foreach($results as $result){
                $column = 'A';//return to the 1st column
                $row++; //increment one row

                //format of the row
                if (($row % 2) == 1)
                    $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':I'.$row)->applyFromArray($text_format1);
                else
                    $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':I'.$row)->applyFromArray($text_format2);
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row, $i++)
                    ->setCellValue($column++.$row, $result['firstname']." ".$result['middlename']." ".$result['surname'])
                    ->setCellValue($column++.$row, $result['presentdesignation'])
                    ->setCellValue($column++.$row, $result['coursename'])
                    ->setCellValue($column++.$row, $result['courselocation'])
                    ->setCellValue($column++.$row, $result['sponsor'])
                    ->setCellValue($column++.$row, $result['startdate'])
                    ->setCellValue($column++.$row, $result['enddate'])
                    ->setCellValue($column.$row, $result['level5_level_5']);

            }
        }

        $excelService->excelObj->getActiveSheet()->setTitle('List of Records');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $excelService->excelObj->setActiveSheetIndex(0);

        //create the response

        $response = $excelService->getResponse();
        $response->headers->set('Content-Type', 'application/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$title.'.xls');

        // If you are using a https connection, you have to set those two headers and use sendHeaders() for compatibility with IE <9
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        //$response->sendHeaders();
        return $response;
    }

    /**
     * Returns Fields json.
     *
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_REPORTHISTORY_GENERATE,ROLE_USER")
     * @Route("/reportFormFields.{_format}", requirements={"_format"="yml|xml|json"}, defaults={"_format"="json"}, name="report_formfields")
     * @Method("POST")
     * @Template()
     */
    public function reportFieldsAction($_format)
    {
        $em = $this->getDoctrine()->getManager();
        $formid = $this->getRequest()->request->get('formid');
        //$formid = 13;

        // Fetch existing feidls belonging to selected form
        $form = $em->getRepository('HrisFormBundle:Form')->findOneBy(array('id'=>$formid));
        $formFields = new ArrayCollection();
        foreach($form->getFormFieldMember() as $formFieldMemberKey=>$formFieldMember) {
            $formFields->add($formFieldMember->getField());
        }

        foreach($formFields as $formFieldsKey=>$formField) {
            if($formField->getHashistory() && $formField->getInputType()->getName() == "Select"){
                $fieldNodes[] = Array(
                    'name' => $formField->getCaption(),
                    'id' => $formField->getId()
                );
            }
        }

        $serializer = $this->container->get('serializer');

        return array(
            'entities' => $serializer->serialize($fieldNodes,$_format)
        );
    }

}
