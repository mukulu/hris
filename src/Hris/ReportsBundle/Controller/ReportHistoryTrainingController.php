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
     * @Route("/", name="report_historytraining")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

        $historytrainingForm = $this->createForm(new ReportHistoryTrainingType(),null,array('em'=>$this->getDoctrine()->getManager()));

        return array(
            'historytrainingForm'=>$historytrainingForm->createView(),
        );
    }

    /**
     * Generate aggregated reports
     *
     * @Route("/", name="report_historytraining_generate")
     * @Method("PUT")
     * @Template()
     */
    public function generateAction(Request $request)
    {
        $historytrainingForm = $this->createForm(new ReportHistoryTrainingType(),null,array('em'=>$this->getDoctrine()->getManager()));
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
                $categories[] = $result['year'];
                $data[] =  $result['total'];

                if($graphType == 'pie'){
                    $piedata[] = array('name' => $result['year'],'y' => $result['total']);
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
            'organisationUnit' => $organisationUnit,
            'formsId' => $formsId,
            'reportType' => $reportType,
            'withLowerLevels' => $withLowerLevels,
            'fields' => $fields,
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
            $query = "SELECT date_part('year',startdate) as year, count(date_part('year',startdate)) as total ";
            $query .= "FROM hris_record_training T ";
            $query .= "INNER JOIN hris_record as V on V.id = T.record_id ";
            $query .= "INNER JOIN hris_organisationunitstructure as S on S.organisationunit_id = V.organisationunit_id ";
            $query .= "INNER JOIN hris_organisationunitlevel as L on L.id = S.level_id ";
            $query .= "WHERE V.form_id = ". $forms->getId();
            $query .= " AND (". $subQuery .") ";
            $query .= " GROUP BY date_part('year',startdate) ";
            $query .= "ORDER BY year ASC";

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
                $query .= " GROUP BY H.history";


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
                $query .= " GROUP BY date_part('year',startdate)";
                $query .= "ORDER BY data ASC";
            }
        }
        //echo $query;exit;

        //get the records
        $report = $entityManager -> getConnection() -> executeQuery($query) -> fetchAll();
        return $report;
    }

    /**
     * Download History reports
     *
     * @Route("/download", name="report_historytraining_download")
     * @Method("GET")
     * @Template()
     */
    public function downloadAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $organisationUnitid =$request->query->get('organisationUnit');
        $formsId = explode(",",$request->query->get('formsId'));
        $organisationunitGroupsId = explode(",",$request->query->get('organisationunitGroupsId'));
        $withLowerLevels =$request->query->get('withLowerLevels');
        $fieldsId =$request->query->get('fields');
        $fieldsTwoId =$request->query->get('fieldsTwo');
        $forms = new ArrayCollection();
        $organisationunitGroups = new ArrayCollection();

        //Get the objects from the the variables

        $organisationUnit = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($organisationUnitid);
        $fields = $em->getRepository('HrisFormBundle:Field')->find($fieldsId);
        $fieldsTwo = $em->getRepository('HrisFormBundle:Field')->find($fieldsTwoId);
        foreach($formsId as $formId){
            $forms->add($em->getRepository('HrisFormBundle:Form')->find($formId)) ;
        }

        foreach($organisationunitGroupsId as $organisationunitGroupId){
            $organisationunitGroups->add($em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroup')->find($organisationunitGroupId));
        }

        $results = $this->aggregationEngine($organisationUnit, $forms, $fields, $organisationunitGroups, $withLowerLevels, $fieldsTwo);


        //create the title
        $title = $fields->getCaption()." Aggregate Report ";
        if($fieldsId != $fieldsTwoId) $title .= "with ".$fieldsTwo->getCaption()." Sex cross tabulation ";
        $title .= "- ".$organisationUnit->getLongname();
        if($withLowerLevels == 1) $title .= " with lower levels";

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

        //work with cross tabulation report
        if ($fields->getId() != $fieldsTwo->getId()) {
            foreach($results as $result){
                $keys[$result[strtolower($fields->getName())]][$result[strtolower($fieldsTwo->getName())]] = $result['total'];
                //$categoryKeys[$result[strtolower($fields->getName())]] = $result['total'];

            }
            //apply the styles
            $excelService->excelObj->getActiveSheet()->getStyle('A1:D2')->applyFromArray($heading_format);
            $excelService->excelObj->getActiveSheet()->mergeCells('A1:D1');
            $excelService->excelObj->getActiveSheet()->mergeCells('A2:D2');

            //write the table heading of the values
            $excelService->excelObj->getActiveSheet()->getStyle('A4:D4')->applyFromArray($header_format);
            $excelService->excelObj->setActiveSheetIndex(0)
                ->setCellValue($column++.$row, 'SN')
                ->setCellValue($column++.$row, $fields->getCaption());
            $fieldOptions = $em->getRepository('HrisFormBundle:FieldOption')->findBy(array('field'=>$fieldsTwo));

            foreach ($fieldOptions as $fieldOption) {
                $excelService->excelObj->setActiveSheetIndex(0)->setCellValue($column++.$row, $fieldOption->getValue());
            }

            //write the values
            $i =1; //count the row
            foreach($keys as $key => $items){
                $column = 'A';//return to the 1st column
                $row++; //increment one row

                //format of the row
                if (($row % 2) == 1)
                    $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':D'.$row)->applyFromArray($text_format1);
                else
                    $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':D'.$row)->applyFromArray($text_format2);
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row, $i++)
                    ->setCellValue($column++.$row, $key);

                foreach ($items as $item) {
                    $excelService->excelObj->setActiveSheetIndex(0)->setCellValue($column++.$row, $item);
                }
            }

        }else{
            //apply the styles
            $excelService->excelObj->getActiveSheet()->getStyle('A1:C2')->applyFromArray($heading_format);
            $excelService->excelObj->getActiveSheet()->mergeCells('A1:C1');
            $excelService->excelObj->getActiveSheet()->mergeCells('A2:C2');

            //write the table heading of the values
            $excelService->excelObj->getActiveSheet()->getStyle('A4:C4')->applyFromArray($header_format);
            $excelService->excelObj->setActiveSheetIndex(0)
                ->setCellValue($column++.$row, 'SN')
                ->setCellValue($column++.$row, $fields->getCaption())
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
                    ->setCellValue($column++.$row, $result[strtolower($fields->getName())])
                    ->setCellValue($column.$row, $result['total']);

            }
        }

        $excelService->excelObj->getActiveSheet()->setTitle('Aggregate Report');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $excelService->excelObj->setActiveSheetIndex(0);

        //create the response

        $response = $excelService->getResponse();
        $response->headers->set('Content-Type', 'application/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$title.'.xls');

        // If you are using a https connection, you have to set those two headers and use sendHeaders() for compatibility with IE <9
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->sendHeaders();
        return $response;

    }

    /**
     * Download history reports by Cadre
     *
     * @Route("/records", name="report_historytraining_download_records")
     * @Method("GET")
     * @Template()
     */
    public function recordsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $organisationUnitid =$request->query->get('organisationUnit');
        $formsId = explode(",",$request->query->get('formsId'));
        $organisationunitGroupsId = explode(",",$request->query->get('organisationunitGroupsId'));
        $withLowerLevels =$request->query->get('withLowerLevels');
        $fieldsId =$request->query->get('fields');
        $fieldsTwoId =$request->query->get('fieldsTwo');
        $forms = new ArrayCollection();
        $organisationunitGroups = new ArrayCollection();

        //Get the objects from the the variables

        $organisationUnit = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($organisationUnitid);
        $fields = $em->getRepository('HrisFormBundle:Field')->find($fieldsId);
        $fieldsTwo = $em->getRepository('HrisFormBundle:Field')->find($fieldsTwoId);
        foreach($formsId as $formId){
            $forms->add($em->getRepository('HrisFormBundle:Form')->find($formId)) ;
        }

        foreach($organisationunitGroupsId as $organisationunitGroupId){
            $organisationunitGroups->add($em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroup')->find($organisationunitGroupId));
        }

        //get the list of options to exclude from the reports
        $fieldOptionsToExclude = $em->getRepository('HrisFormBundle:FieldOption')->findBy (
            array('skipInReport' => TRUE)
        );

        //remove the value which have field option set to exclude in reports
        //but check to see if the first field is in the list of fields to remove.
        foreach($fieldOptionsToExclude as $key => $fieldOptionToExclude)
            if($fieldOptionToExclude->getField()->getId() == $fields->getId())
                unset($fieldOptionsToExclude[$key]);


        //Pull the organisation unit Structure
        $selectedOrgunitStructure = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitStructure')->findOneBy(array('organisationunit' => $organisationUnit->getId()));

        $resourceTableName = "_resource_all_fields";
        //create the query to select the records from the resource table
        $query ="SELECT ResourceTable.firstname, ResourceTable.middlename, ResourceTable.surname, ResourceTable.profession,ResourceTable.".$fields->getName();

        if ($fieldsTwo->getId() != $fields->getId()) {
            $query .= " ,ResourceTable.".$fieldsTwo->getName();
        }
        $query .= " ,Orgunit.longname FROM ".$resourceTableName." ResourceTable inner join hris_organisationunit as Orgunit ON Orgunit.id = ResourceTable.organisationunit_id INNER JOIN hris_organisationunitstructure AS Structure ON Structure.organisationunit_id = ResourceTable.organisationunit_id";

        $query .= " WHERE ResourceTable.".$fields->getName()." is not NULL ";
        if ($fieldsTwo->getId() != $fields->getId()) {
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
        foreach($fieldOptionsToExclude as $key => $fieldOptionToExclude)
            $query .= " AND ResourceTable.".$fieldOptionToExclude->getField()->getName()." != '".$fieldOptionToExclude->getValue()."'";

        //sort the records by cadre
        $query .= " ORDER BY ResourceTable.profession, ".$fields->getName();

        $results = $em -> getConnection() -> executeQuery($query) -> fetchAll();

        //create the title
        $title = 'List of Records by '.$fields->getCaption();
        if($fieldsId != $fieldsTwoId) $title .= " and ".$fieldsTwo->getCaption();
        $title .= ' for ' . $organisationUnit->getLongname();
        if($withLowerLevels == 1) $title .= " with lower levels";


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

        //apply the styles
        if($fields->getId() != $fieldsTwo->getId()) $cellMerge = 'F'; else $cellMerge = 'E';
        $excelService->excelObj->getActiveSheet()->getStyle('A1:'.$cellMerge.'2')->applyFromArray($heading_format);
        $excelService->excelObj->getActiveSheet()->mergeCells('A1:'.$cellMerge.'1');
        $excelService->excelObj->getActiveSheet()->mergeCells('A2:'.$cellMerge.'2');

        //write the table heading of the values
        $excelService->excelObj->getActiveSheet()->getStyle('A4:'.$cellMerge.'4')->applyFromArray($header_format);
        $excelService->excelObj->setActiveSheetIndex(0)
            ->setCellValue($column++.$row, 'SN')
            ->setCellValue($column++.$row, 'Name')
            ->setCellValue($column++.$row, 'Profession');
        if ($fields->getName() != "profession")
            $excelService->excelObj->setActiveSheetIndex(0)->setCellValue($column++.$row, $fields->getCaption());
        if($fields->getId() != $fieldsTwo->getId())
            if ($fieldsTwo->getName() != "profession")
                $excelService->excelObj->setActiveSheetIndex(0)->setCellValue($column++.$row, $fieldsTwo->getCaption());
        $excelService->excelObj->setActiveSheetIndex(0)->setCellValue($column.$row, 'Facility Name');
        //write the values
        $i =1; //count the row
        foreach($results as $result){
            $column = 'A';//return to the 1st column
            $row++; //increment one row

            //format of the row
            if (($row % 2) == 1)
                $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':'.$cellMerge.$row)->applyFromArray($text_format1);
            else
                $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':'.$cellMerge.$row)->applyFromArray($text_format2);
            $excelService->excelObj->setActiveSheetIndex(0)
                ->setCellValue($column++.$row, $i++)
                ->setCellValue($column++.$row, $result['firstname'].' '.$result['middlename'].' '.$result['surname'])
                ->setCellValue($column++.$row, $result['profession']);
            if ($fields->getName() != "profession")
                $excelService->excelObj->setActiveSheetIndex(0)->setCellValue($column++.$row, $result[strtolower($fields->getName())]);
            if($fields->getId() != $fieldsTwo->getId())
                if ($fieldsTwo->getName() != "profession")
                    $excelService->excelObj->setActiveSheetIndex(0)->setCellValue($column++.$row, $result[strtolower($fieldsTwo->getName())]);
            $excelService->excelObj->setActiveSheetIndex(0)->setCellValue($column.$row, $result['longname']);

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
        $response->sendHeaders();
        return $response;
    }

}
