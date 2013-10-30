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
use Hris\FormBundle\Entity\ResourceTable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\ReportsBundle\Entity\Report;
use Hris\ReportsBundle\Form\ReportType;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Zend\Json\Expr;
use JMS\SecurityExtraBundle\Annotation\Secure;

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
     * @Secure(roles="ROLE_REPORTAGGREGATION_GENERATE,ROLE_USER")
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
     * @Secure(roles="ROLE_REPORTAGGREGATION_GENERATE,ROLE_USER")
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
        if(empty($organisationUnit)) {
            $organisationUnit = $this->getDoctrine()->getManager()->createQuery('SELECT organisationunit FROM HrisOrganisationunitBundle:Organisationunit organisationunit WHERE organisationunit.parent IS NULL')->getSingleResult();
        }

        $results = $this->aggregationEngine($organisationUnit, $forms, $fields, $organisationunitGroup, $withLowerLevels, $fieldsTwo);

        //Get the Id for the forms
        $formsId = '';
        foreach($forms as $form){
            $formsId .= $form->getId().",";
        }
        $formsId = rtrim($formsId,",");

        //Get the Id for the OrgansiationunitGoup
        $organisationunitGroupId = '';
        foreach($organisationunitGroup as $organisationunitGroups){
            $organisationunitGroupId .= $organisationunitGroups->getId().",";
        }
        $organisationunitGroupId = rtrim($organisationunitGroupId,",");

        //if only one field selected
        if($fieldsTwo->getId() == $fields->getId()){

            foreach($results as $result){
                $categories[] = $result[strtolower($fields->getName())];
                $data[] =  $result['total'];

                if($graphType == 'pie'){
                    $piedata[] = array('name' => $result[strtolower($fields->getName())],'y' => $result['total']);
                }
            }
            if($graphType == 'pie') $data = $piedata;
            $series = array(
                array(
                    'name'  => $fields->getName(),
                    'data'  => $data,
                ),
            );
            $formatterLabel = $fields->getCaption();

        }else{//Two fields selected
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
        if($fieldsTwo->getId() != $fields->getId()) $title .= " with ".$fieldsTwo->getCaption()." cross Tabulation ";
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
        if($fieldsTwo->getId() == $fields->getId())$dashboardchart->legend->enabled(true); else $dashboardchart->legend->enabled(true);

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
        if($graphType == 'pie')$dashboardchart->plotOptions->pie(array('allowPointSelect'=> true,'dataLabels'=> array ('format'=> '<b>{point.name}</b>: {point.percentage:.1f} %')));
        $dashboardchart->series($series);

        return array(
            'chart'=>$dashboardchart,
            'organisationUnit' => $organisationUnit,
            'formsId' => $formsId,
            'organisationunitGroupId' => $organisationunitGroupId,
            'withLowerLevels' => $withLowerLevels,
            'fields' => $fields,
            'fieldsTwo' => $fieldsTwo,
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
     * @return mixed
     */
    public function aggregationEngine(Organisationunit $organisationUnit,  ArrayCollection $forms, Field $fields, ArrayCollection $organisationunitGroup, $withLowerLevels, Field $fieldsTwo)
    {


        $entityManager = $this->getDoctrine()->getManager();

        $selectedOrgunitStructure = $entityManager->getRepository('HrisOrganisationunitBundle:OrganisationunitStructure')->findOneBy(array('organisationunit' => $organisationUnit->getId()));

        //get the list of options to exclude from the reports
        $fieldOptionsToExclude = $entityManager->getRepository('HrisFormBundle:FieldOption')->findBy (
            array('skipInReport' => TRUE)
        );

        //remove the value which have field option set to exclude in reports
        //but check to see if the first field is in the list of fields to remove.
        foreach($fieldOptionsToExclude as $key => $fieldOptionToExclude)
                if($fieldOptionToExclude->getField()->getId() == $fields->getId())
                        unset($fieldOptionsToExclude[$key]);

        //create the query to aggregate the records from the static resource table
        //check if field one is calculating field so to create the sub query
        $resourceTableName = ResourceTable::getStandardResourceTableName();
        if($fields->getIsCalculated()){
            // @todo implement calculated fields feature and remove hard-coding

        }
        $query = "SELECT ResourceTable.".$fields->getName();
        if ($fieldsTwo->getId() != $fields->getId()) {
            $query .= " , ResourceTable.".$fieldsTwo->getName()." , count(ResourceTable.".$fieldsTwo->getName().") as total";
       }else{
            $query .= " , count(ResourceTable.".$fields->getName().") as total";
       }

        $query .= " FROM ".$resourceTableName." ResourceTable inner join hris_organisationunit as Orgunit ON Orgunit.id = ResourceTable.organisationunit_id INNER JOIN hris_organisationunitstructure AS Structure ON Structure.organisationunit_id = ResourceTable.organisationunit_id";

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
        if($organisationunitGroup != NULL){
            $groups = NULL;
            foreach($organisationunitGroup as $organisationunitGroups){
                $groups .= "'".$organisationunitGroups->getName()."',";
            }
            //remove the last comma in the query
            $groups = rtrim($groups,",");

            if($groups != NULL) $query .= " AND (ResourceTable.type IN (".$groups.") OR ownership IN (".$groups.") )";//OR administrative IN (".$groups.")
        }

        //remove the record which have field option set to exclude in reports
        foreach($fieldOptionsToExclude as $key => $fieldOptionToExclude)
            $query .= " AND ResourceTable.".$fieldOptionToExclude->getField()->getName()." != '".$fieldOptionToExclude->getValue()."'";

        $query .= " GROUP BY ResourceTable.".$fields->getName();
        if ($fieldsTwo->getId() != $fields->getId()) {
            $query .= " , ResourceTable.".$fieldsTwo->getName();
        }

        $query .= " ORDER BY ResourceTable.".$fields->getName();
        if ($fieldsTwo->getId() != $fields->getId()) {
            $query .= " , ResourceTable.".$fieldsTwo->getName();
        }

        //get the records
        $report = $entityManager -> getConnection() -> executeQuery($query) -> fetchAll();
        return $report;
        /*
        $tabulationValues = '';
        $tempFieldOption = '';
        if ($fieldsTwo->getId() != $fields->getId()) {//when two fields are  selected
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

    /**
     * Download aggregated reports
     *
     * @Secure(roles="ROLE_REPORTAGGREGATION_DOWNLOAD,ROLE_USER")
     * @Route("/download", name="report_aggregation_download")
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
            if($organisationunitGroupId != NULL)$organisationunitGroups->add($em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroup')->find($organisationunitGroupId));
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
        $response->headers->set('Content-Disposition','attachment;filename='.$title.'.xls');

        // If you are using a https connection, you have to set those two headers and use sendHeaders() for compatibility with IE <9
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        //$response->sendHeaders();
        return $response;

    }

    /**
     * Download aggregated reports by Cadre
     *
     * @Secure(roles="ROLE_REPORTAGGREGATION_DOWNLOADRECORDS,ROLE_USER")
     * @Route("/records", name="report_aggregation_download_records")
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
            if($organisationunitGroupId != NULL) $organisationunitGroups->add($em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroup')->find($organisationunitGroupId));
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

        $resourceTableName = ResourceTable::getStandardResourceTableName();
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
        $groups = NULL;
        foreach($organisationunitGroups as $organisationunitGroup){
            
            if($organisationunitGroup != NULL)
                $groups .= "'".$organisationunitGroup->getName()."',";
        }

        if($groups != NULL){
            //remove the last comma in the query
            $groups = rtrim($groups,",");
            $query .= " AND (ResourceTable.type IN (".$groups.") OR ownership IN (".$groups.") )";//OR administrative IN (".$groups.")
        }

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
        //$response->sendHeaders();
        return $response;
    }

}
