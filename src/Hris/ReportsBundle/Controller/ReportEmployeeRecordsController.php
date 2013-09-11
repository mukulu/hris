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
namespace Hris\ReportsBundle\Controller;

use Hris\FormBundle\Entity\Form;
use Hris\FormBundle\Entity\ResourceTable;
use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Hris\FormBundle\Entity\Field;
use Hris\ReportsBundle\Form\ReportAggregationType;
use Hris\ReportsBundle\Form\ReportEmployeeRecordsType;
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
 * Report Employee Records controller.
 *
 * @Route("/reports/employeerecords")
 */
class ReportEmployeeRecordsController extends Controller
{

    /**
     * Show Report Employee Records Form
     *
     * @Route("/", name="report_employeerecords")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

        $employeeRecordsForm = $this->createForm(new ReportEmployeeRecordsType(),null,array('em'=>$this->getDoctrine()->getManager()));

        return array(
            'employeeRecordsForm'=>$employeeRecordsForm->createView(),
        );
    }

    /**
     * Generate employee records reports
     *
     * @Route("/", name="report_employeerecords_generate")
     * @Method("PUT")
     * @Template()
     */
    public function generateAction(Request $request)
    {
        $serializer = $this->container->get('serializer');

        $employeeRecordsForm = $this->createForm(new ReportEmployeeRecordsType(),null,array('em'=>$this->getDoctrine()->getManager()));
        $employeeRecordsForm->bind($request);

        if ($employeeRecordsForm->isValid()) {
            $employeeRecordsFormData = $employeeRecordsForm->getData();
            $organisationUnit = $employeeRecordsFormData['organisationunit'];
            $forms = $employeeRecordsFormData['forms'];
            $withLowerLevels = $employeeRecordsFormData['withLowerLevels'];
        }
        $formNames = NULL;
        $visibleFields = Array();
        $formFields = Array();
        $incr=0;
        $formIds = Array();
        foreach($forms as $formKey=>$form) {
            $incr++;
            $formIds[] = $form->getId();
            // Concatenate form Names
            if(empty($formNames)) {
                $formNames = $form->getTitle();
            }else {
                if(count($formNames)==$incr) $formNames.=$form->getTitle();
            }
            // Accrue visible fields
            foreach($form->getFormVisibleFields() as $visibleFieldKey=>$visibleField) {
                if(!in_array($visibleField->getField(),$visibleFields) && !$visibleField->getField()->getIsCalculated()) $visibleFields[] =$visibleField->getField();
            }
            // Accrue form fields
            foreach($form->getFormFieldMember() as $formFieldKey=>$formField) {
                if(!in_array($formField->getField(),$formFields) && !$formField->getField()->getIsCalculated()) $formFields[] =$formField->getField();
            }
        }
        $title = "Records Report for ".$organisationUnit->getLongname();
        if($withLowerLevels) {
            $title .=" with Lower Levels";
        }
        $title .= " for ".$formNames;
        $individualSearchClause= NULL;
        $visibleFieldsCounter=1;
        $employeeRecordsParameters  = Array();
        if(!empty($visibleFields)) {
            foreach ($visibleFields as $key => $fieldObject) {
                if(empty($visibleFieldUids)) $visibleFieldUids  = $fieldObject->getField()->getId();
                else $visibleFieldUids .=','.$fieldObject->getField()->getId();
                $individualSearchClause .= '{type:"text"},';
                $visibleFieldsCounter++;
            }
        }else {
            foreach ($form->getFormFieldMember() as $key => $fieldObject) {
                if( !$fieldObject->getField()->getIsCalculated() ) {
                    if(empty($visibleFieldUids)) $visibleFieldUids  = $fieldObject->getField()->getId();
                    else $visibleFieldUids .=','.$fieldObject->getField()->getId();
                    $individualSearchClause .= '{type:"text"},';
                    $visibleFieldsCounter++;
                }
            }
        }
        $visibleFieldParameters['name'] = 'visibleFields';
        $visibleFieldParameters['value'] = $visibleFieldUids;
        $employeeRecordsParameters = $serializer->serialize($visibleFieldParameters,'json');
        $formParameters['name'] = 'forms';
        $formParameters['value'] = $formIds;
        $employeeRecordsParameters .= ','.$serializer->serialize($formParameters,'json');
        $organisationunitParameters['name']='organisationunit';
        $organisationunitParameters['value']=$organisationUnit->getId();
        $employeeRecordsParameters .= ','.$serializer->serialize($organisationunitParameters,'json');
        if($withLowerLevels) {
            $withLowerLevelsParameters['name']='withLowerLevels';
            $withLowerLevelsParameters['value']=True;
            $employeeRecordsParameters .= ','.$serializer->serialize($withLowerLevelsParameters,'json');
        }else {
            $withLowerLevelsParameters['name']='withLowerLevels';
            $withLowerLevelsParameters['value']=False;
            $employeeRecordsParameters .= ','.$serializer->serialize($withLowerLevelsParameters,'json');
        }

        // Create dataTable ajax placeholders
        $dataTableAjaxColumns = "[null,".$individualSearchClause."null,null]";

        return array(
            'visibleFields'=>$visibleFields,
            'formFields'=>$formFields,
            'title'=>$title,
            'employeeRecordsParameters'=>$employeeRecordsParameters,
            'dataTableAjaxColumns'=>$dataTableAjaxColumns
        );
    }

    /**
     * Returns Employee records json.
     *
     * @Secure(roles="ROLE_RECORDS_EMPLOYEE_RECORDS,ROLE_USER")
     *
     * @Route("/{_format}", requirements={"_format"="json|"}, defaults={"format"="json"}, name="report_employeerecords_ajax")
     * @Method("POST")
     * @Template()
     */
    public function employeeRecordsAction($_format)
    {
        $em = $this->getDoctrine()->getManager();
        $visibleFieldIds = explode(',',$this->getRequest()->request->get('visibleFields'));
        $formIds = $this->getRequest()->request->get('forms');
        $organisationunitId = $this->getRequest()->request->get('organisationunit');
        $withLowerLevels = $this->getRequest()->request->get('withLowerLevels');

        // Query for Options to exclude from reports
        $fieldOptionsToSkip = $this->getDoctrine()->getManager()->getRepository('HrisFormBundle:FieldOption')->findBy (array('skipInReport' =>True));

        //filter the records with exclude report tag
        foreach($fieldOptionsToSkip as $key => $fieldOptionToSkip){
            if(empty($fieldOptionsToSkipQuery)) {
                $fieldOptionsToSkipQuery = "ResourceTable.".$fieldOptionToSkip->getField()->getName()." !='".$fieldOptionToSkip->getValue()."'";
            }else {
                $fieldOptionsToSkipQuery .= " AND ResourceTable.".$fieldOptionToSkip->getField()->getName()." !='".$fieldOptionToSkip->getValue()."'";
            }

        }
        //preparing array of Fields
        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
        $fieldObjects = $queryBuilder->select('field')->from('HrisFormBundle:Field','field')->where($queryBuilder->expr()->in('field.id',$visibleFieldIds))->andWhere('field.isCalculated=False')->getQuery()->getResult();
        foreach ($fieldObjects as $key => $fieldObject) {
            $field[strtolower($fieldObject->getName())] = $fieldObject->getId();
            $fieldKeys[$fieldObject->getName()]=$key; // Used later in tracing FieldObject for resolving fieldOptions
            if(in_array($fieldObject->getId(),$visibleFieldIds)) $visibleFieldKeysById[$fieldObject->getId()] = $key; // Used later in tracing Field object for resolving individual search
        }
        $visibleFields = $fieldObjects;

        /*
         * Initializing query for values from resource table
         */
        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
        $resourceTableName = str_replace(' ','_',trim(strtolower(ResourceTable::getStandardResourceTableName())));
        if(!empty($fieldOptionsToSkipQuery)) {
            $onlyActiveEmployeeWhereClause=" ( $fieldOptionsToSkipQuery ) ";
        }else {
            $onlyActiveEmployeeWhereClause = NULL;
        }
        $organisationunitLevelsJoinClause=" INNER JOIN hris_organisationunit as Organisationunit ON Organisationunit.id = ResourceTable.organisationunit_id INNER JOIN hris_organisationunitstructure AS Structure ON Structure.organisationunit_id = ResourceTable.organisationunit_id ";
        $fromClause=" FROM $resourceTableName ResourceTable ";

        // Clause for filtering target organisationunits
        if ($withLowerLevels=="true") {
            $organisationunit = $this->getDoctrine()->getManager()->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($organisationunitId);
            $organisationunitLevelsWhereClause = " Structure.level".$organisationunit->getOrganisationunitStructure()->getLevel()->getLevel()."_id=$organisationunitId AND Structure.level_id >= ( SELECT hris_organisationunitlevel.level FROM hris_organisationunitstructure INNER JOIN hris_organisationunitlevel ON hris_organisationunitstructure.level_id=hris_organisationunitlevel.id  WHERE hris_organisationunitstructure.organisationunit_id=$organisationunitId ) ";
        }else {
            $organisationunitLevelsWhereClause = " ResourceTable.organisationunit_id=$organisationunitId ";
        }
        // Clause for filtering target forms
        $formsWhereClause=" ResourceTable.form_id IN ($formIds) ";

        // Row count for the entire database(rows accessible by user)
        $selectQuery="SELECT COUNT(ResourceTable.instance) AS count $fromClause $organisationunitLevelsJoinClause WHERE $organisationunitLevelsWhereClause";
        if(!empty($onlyActiveEmployeeWhereClause)) $selectQuery .=" AND $onlyActiveEmployeeWhereClause";
        $entireDatabaseCount = $this->getDoctrine()->getManager()->getConnection()->executeQuery($selectQuery)->fetchColumn();

        /*
         * Individual column filtering
         */
        for ( $i=1 ; $i<count($visibleFieldIds) ; $i++ )
        {
            if ( isset($_POST['bSearchable_'.$i]) && $_POST['bSearchable_'.$i] == "true" && $_POST['sSearch_'.$i] != '' )
            {
                $fieldName=strtolower($fieldObjects[$visibleFieldKeysById[$visibleFieldIds[$i]]]->getName());
                $searchedIndividualColumns[]=$fieldName;
                if($fieldObjects[$visibleFieldKeysById[$visibleFieldIds[$i]]]->getInputType()->getName() == 'date') {
                    /* Complications of searching for date will be handled here!!! */

                    if( intval($_POST['sSearch_'.$i]) != 0  && isset($individualFieldNamesWhereClause) ) {
                        $individualFieldNamesWhereClause.=" AND ".$_POST['sSearch_'.$i]."  IN ( EXTRACT(YEAR FROM ResourceTable.$fieldName),EXTRACT(MONTH FROM ResourceTable.$fieldName),EXTRACT(DAY FROM ResourceTable.$fieldName)  )  ";
                    }elseif(intval($_POST['sSearch_'.$i]) != 0) {
                        $individualFieldNamesWhereClause=$_POST['sSearch_'.$i]." IN ( EXTRACT(YEAR FROM ResourceTable.$fieldName),EXTRACT(MONTH FROM ResourceTable.$fieldName),EXTRACT(DAY FROM ResourceTable.$fieldName)  )  ";
                    }
                }else {
                    if(isset($individualFieldNamesWhereClause)) {
                        $individualFieldNamesWhereClause.=" AND LOWER(ResourceTable.$fieldName) LIKE '%".strtolower($_POST['sSearch_'.$i])."%' ";
                    }else {
                        $individualFieldNamesWhereClause=" LOWER(ResourceTable.$fieldName) LIKE '%".strtolower($_POST['sSearch_'.$i])."%' ";
                    }
                }
            }
        }

        /*
         * General search (All Column filtering)
         */
        if ( isset($searchString) && $searchString != "" )
        {
            // Searching visible fields
            foreach ( $field as $fieldName=>$fieldId )
            {
                if(isset($searchedIndividualColumns) && in_array($fieldName,$searchedIndividualColumns)) continue; // Skip for fields already filtered in individual search
                // @workaround skip constraining individidual field search column if exist.
                if(gettype($fieldName) == "string") {
                    if($fieldObjects[$fieldKeys[$fieldName]]->getInputType()->getName() == 'date') {
                        if( intval($searchString) != 0 && isset($fieldNamesWhereClause)) {
                            $fieldNamesWhereClause.=" OR ".$searchString." IN ( EXTRACT(YEAR FROM ResourceTable.$fieldName),EXTRACT(MONTH FROM ResourceTable.$fieldName),EXTRACT(DAY FROM ResourceTable.$fieldName)  )  ";
                        }elseif(intval($searchString) != 0) {
                            $fieldNamesWhereClause=" ".$searchString." IN ( EXTRACT(YEAR FROM ResourceTable.$fieldName),EXTRACT(MONTH FROM ResourceTable.$fieldName),EXTRACT(DAY FROM ResourceTable.$fieldName)  )  ";
                        }
                    }else {
                        if(isset($fieldNamesWhereClause)) {
                            $fieldNamesWhereClause.=" OR LOWER(ResourceTable.$fieldName) SIMILAR TO '%".strtolower(str_replace(" ","|",$searchString))."%' ";
                        }else {
                            $fieldNamesWhereClause=" LOWER(ResourceTable.$fieldName) SIMILAR TO '%".strtolower(str_replace(" ","|",$searchString))."%' ";
                        }
                    }
                }
            }
            // Searching calculated fields
            /* Searching based on age */

            /* Searching based on employment duration */

            /* Searching based on retirement date */

            /* Searching based on last updated */

            /* Searching based on organisationunit */
            if(isset($fieldNamesWhereClause)) {
                $fieldNamesWhereClause.=" OR LOWER(ResourceTable.organisationunit_name) SIMILAR TO '%".strtolower(str_replace(" ","|",$searchString))."%' ";
            }else {
                $fieldNamesWhereClause=" LOWER(ResourceTable.organisationunit_name) SIMILAR TO '%".strtolower(str_replace(" ","|",$searchString))."%' ";
            }
        }

        // Putting select column clause together the query
        $birthDateNotPresent=True;
        $firstAppointmentNotPresent=True;
        foreach($visibleFields as $key=>$visibleFieldObject) {
            // Building the Select column clause
            if(isset($selectColumnClause)) {
                $selectColumnClause.=",ResourceTable.".strtolower($visibleFieldObject->getName())." as ".strtolower($visibleFieldObject->getName());
            }else {
                $selectColumnClause="ResourceTable.".strtolower($visibleFieldObject->getName())." as ".strtolower($visibleFieldObject->getName());
            }
            if($visibleFieldObject->getName()=="dateoffirstappointment") {
                $firstAppointmentNotPresent=False;
            }
            if($visibleFieldObject->getName()=="birthdate") {
                $birthDateNotPresent=False;
            }
        }
        if(isset($selectColumnClause)) {
            $selectColumnClause.=",ResourceTable.instance as instance,ResourceTable.lastupdated as lastupdated,ResourceTable.organisationunit_name as organisationunit_name";
            if($firstAppointmentNotPresent==True) {
                $selectColumnClause .=",ResourceTable.dateoffirstappointment as dateoffirstappointment";
            }
            if($birthDateNotPresent == True) {
                $selectColumnClause .=",ResourceTable.birthdate as birthdate";
            }
        }else {
            $selectColumnClause="ResourceTable.instance as instance,ResourceTable.lastupdated as lastupdated,ResourceTable.organisationunit_name as organisationunit_name";
            if($firstAppointmentNotPresent==True) {
                $selectColumnClause .=",ResourceTable.dateoffirstappointment as dateoffirstappointment";
            }
            if($birthDateNotPresent == True) {
                $selectColumnClause .=",ResourceTable.birthdate as birthdate";
            }
        }
        $whereClause=NULL;
        if(!empty($onlyActiveEmployeeWhereClause)) {
            $whereClause=" WHERE $organisationunitLevelsWhereClause AND $onlyActiveEmployeeWhereClause AND $formsWhereClause";
        }else {
            $whereClause=" WHERE $organisationunitLevelsWhereClause AND $formsWhereClause";
        }

        $countOfUnFilteredRecordsQuery="SELECT COUNT(instance) as count $fromClause $organisationunitLevelsJoinClause $whereClause";

        if(!empty($fieldNamesWhereClause) && !empty($whereClause)) {
            $whereClause.=" AND ($fieldNamesWhereClause)";
        }elseif(!empty($fieldNamesWhereClause)) {
            $whereClause=" WHERE $fieldNamesWhereClause";
        }
        if(!empty($individualFieldNamesWhereClause) && !empty($whereClause))  {
            $whereClause.=" AND ($individualFieldNamesWhereClause) ";
        }elseif(!empty($individualFieldNamesWhereClause)) {
            $whereClause=" WHERE $individualFieldNamesWhereClause";
        }
        $countOfFilteredRecordsQuery="SELECT COUNT(instance) as count $fromClause $organisationunitLevelsJoinClause $whereClause";
        // Total rows of filtered records(searched records out of all records accessible to user

        $countOfFilteredRecords = $this->getDoctrine()->getManager()->getConnection()->executeQuery($countOfFilteredRecordsQuery)->fetchColumn();
        $countOfUnFilteredRecords = $this->getDoctrine()->getManager()->getConnection()->executeQuery($countOfUnFilteredRecordsQuery)->fetchColumn();

        /*
         * Ordering
        */
        $sOrder = NULL;
        if ( isset( $_POST['iSortingCols'] ) )
        {
            for ( $i=0 ; $i<intval( $_POST['iSortingCols'] ) ; $i++ ) {
                if ( $_POST[ 'bSortable_'.intval($_POST['iSortCol_'.$i]) ] == "true" ) {
                    if(isset($visibleFieldIds[intval($_POST['iSortCol_'.$i])])) {
                        $fieldName=$fieldObjects[$visibleFieldKeysById[$visibleFieldIds[intval($_POST['iSortCol_'.$i])]]]->getName();
                        if ( empty($sOrder) ) {
                            $sOrder= strtolower($fieldName)." ".strtoupper($_POST['sSortDir_'.$i]);
                        }else {
                            $sOrder.= ",".strtolower($fieldName)." ".strtoupper($_POST['sSortDir_'.$i]);
                        }
                    }elseif($i==0) {
                        // Serial number sorting
                        if ( empty($sOrder) ) {
                            $sOrder= "ResourceTable.instance ".strtoupper($_POST['sSortDir_'.$i]);
                        }else {
                            $sOrder.= ",ResourceTable.instance ".strtoupper($_POST['sSortDir_'.$i]);
                        }
                        $sortingOrder = $_POST['sSortDir_'.$i];
                    }
                }
            }
            if ( !empty($sOrder) ) {
                $sOrder = " ORDER BY ".$sOrder;
            }
        }

        /*
         * Paging
        */
        $sLimit = "";
        if ( isset( $_POST['iDisplayStart'] ) && $_POST['iDisplayLength'] != '-1' )
        {
            $filteredRecordsWithLimitsWithoutSelectQuery= $fromClause. $organisationunitLevelsJoinClause . $whereClause. $sOrder ." OFFSET ".$_POST['iDisplayStart']." LIMIT ".$_POST['iDisplayLength'];
        }else {
            $filteredRecordsWithLimitsWithoutSelectQuery= $fromClause. $organisationunitLevelsJoinClause . $whereClause. $sOrder;
        }

        /*
         * Get data to display
         * The Final Query(factoring pagination, searching and sorting)
         */
        $recordsToDisplayQuery="SELECT ".$selectColumnClause.$filteredRecordsWithLimitsWithoutSelectQuery;

        $recordsArray = $this->getDoctrine()->getManager()->getConnection()->fetchAll($recordsToDisplayQuery);
        /*
         * Output
        */
        $employeeRecordsArray = array(
            "sEcho" => intval($_POST['sEcho']),
            "iTotalRecords" => $countOfUnFilteredRecords,
            "iTotalDisplayRecords" => $countOfFilteredRecords,
            "aaData" => array()
        );
        if(isset($sortingOrder) && strtolower($sortingOrder)=="desc") {
            $counter = $countOfFilteredRecords - $_POST['iDisplayStart'];
        }else {
            $counter = $_POST['iDisplayStart']+1;
        }

        if (!empty($recordsArray)) {
            foreach ($recordsArray as $key => $recordArray) {
                if (!empty($recordArray)) {
                    $row = array();
                    $row[]=$counter;
                    if(isset($sortingOrder) && strtolower($sortingOrder)=="desc") {
                        $counter--;
                    }else {
                        $counter++;
                    }
                    for ( $i=0 ; $i<count($visibleFieldIds)-1 ; $i++ ) {
                        $fieldObject = $fieldObjects[$i];
                        if ($fieldObject->getInputType()->getName() == 'Select') {
                            $displayValue = $recordArray[strtolower($fieldObject->getName())];
                        }else if ($fieldObject->getInputType()->getName() == 'Date') {
                            if(isset($recordArray[$fieldObject->getName()])) {
                                $displayValue = new \DateTime($recordArray[$fieldObject->getName()]);
                                // Date Field Value
                                $displayValue = $displayValue->format('d/m/Y');
                            }
                        } else {
                            $displayValue = $recordArray[strtolower($fieldObject->getName())];
                        }
                        if(!empty($displayValue)) $row[]=$displayValue; else $row[] = "";
                    }
                    // Employment duration calcuation @wild hack and hard coded
                    if(isset($recordArray["dateoffirstappointment"])) {
                        $firstAppointment = new \DateTime($recordArray["dateoffirstappointment"]);
                        if (!empty($firstAppointment)){
                            if(gettype($firstAppointment)=="object") {
                                $correctFormat="Y-m-d";
                                $firstAppointment = $firstAppointment->format($correctFormat);
                            }else {
                                // Workaround for dates that got saved as d/m/Y instead of Y-m-d
                                $correctFormat = 'd/m/Y';
                                $firstAppointmentDateObject = \DateTime::createFromFormat($correctFormat, $firstAppointment);
                                if(empty($firstAppointmentDateObject)) {
                                    $correctFormat = 'Y-m-d';
                                    $firstAppointment = \DateTime::createFromFormat($correctFormat, $firstAppointment);
                                }else {
                                    $correctFormat="Y-m-d";
                                    $firstAppointment = $firstAppointmentDateObject->format('Y-m-d');
                                }
                            }
                            $datediff = $this->dateDiff("-", date($correctFormat), $firstAppointment);
                            if (round($datediff / 12, 0) < 12) {
                                $employmentDuration = round($datediff / 12, 0) . "(m)";
                            } else {
                                $employmentDuration = floor($datediff / 365) . "(y) " . floor(($datediff % 365) / 30) . "(m)";
                            }
                        }else {
                            $employmentDuration = "";
                        }
                    }
                    if(isset($recordArray["birthdate"])) {
                        // Hard coding calculated fields
                        // Calculating age and retirement
                        $birthdate = new \DateTime($recordArray["birthdate"]);
                        $correctFormat = 'Y-m-d';
                        if (!empty($birthdate)){
                            if(gettype($birthdate)=="object") {
                                $birthdate = $birthdate->format('Y-m-d');
                            }else {
                                // Workaround for dates that got saved as d/m/Y instead of Y-m-d
                                $correctFormat = 'd/m/Y';
                                $birthdate = \DateTime::createFromFormat($correctFormat, $birthdate);
                                $birthdate = $birthdate->format('Y-m-d');
                            }

                        }
                        if (!empty($birthdate)) {

                            $age = round($this->dateDiff("-", date($correctFormat), $birthdate) / 365, 0);
                            $date = explode('-', $birthdate);
                            $retirementDate = $date[2] . '/' . $date[1] . '/' . ($date[0] + 60);
                            $birthdate = $date[2] . '/' . $date[1] . '/' . $date[0];
                        } else {
                            $age = "";
                            $retirementDate = "";
                        }
                    }
                    if(isset($age)) $row[] = $age;else $row[]='';
                    if(isset($employmentDuration)) $row[] = $employmentDuration;else $row[]='';
                    if(isset($retirementDate)) $row[] = $retirementDate;else $row[]='';
                    $lastUpdated = new \DateTime($recordArray["lastupdated"]);
                    $row[] = $lastUpdated->format('d/m/Y');
                    $row[] = $recordArray["organisationunit_name"];
                    $row["DT_RowId"] =$recordArray["instance"];
                    $employeeRecordsArray['aaData'][] = $row;
                }
            }
        }

        $serializer = $this->container->get('serializer');

        return array(
            'employeeRecords' => $serializer->serialize($employeeRecordsArray,$_format)
        );
    }

    private function dateDiff($dformat, $endDate, $beginDate) {
        $correctFormat="Y-m-d";
        if(gettype($beginDate)=="object") {
            $beginDate = $beginDate->format($correctFormat);
        }
        $endDate = \DateTime::createFromFormat($correctFormat, $endDate);
        $endDate = $endDate->format('Y-m-d');

        $date_parts1 = explode($dformat, $beginDate);
        $date_parts2 = explode($dformat, $endDate);
        $start_date = gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
        $end_date = gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
        return $end_date - $start_date;
    }
}
