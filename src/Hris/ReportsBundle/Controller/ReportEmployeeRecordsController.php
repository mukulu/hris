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
use Hris\RecordsBundle\Entity\Record;
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
                if(count($forms)==$incr) $formNames.=' and '.$form->getTitle(); else $formNames.=','.$form->getTitle();
            }
            // Accrue visible fields
            foreach($form->getFormVisibleFields() as $visibleFieldKey=>$visibleField) {

                if(!in_array($visibleField->getField(),$visibleFields)) $visibleFields[] =$visibleField->getField();
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
                if(empty($visibleFieldUids)) $visibleFieldUids  = $fieldObject->getId();
                else $visibleFieldUids .=','.$fieldObject->getId();
                $individualSearchClause .= '{type:"text"},';
                $visibleFieldsCounter++;
            }
        }else {
            foreach ($form->getFormFieldMember() as $key => $fieldObject) {
                    if(empty($visibleFieldUids)) $visibleFieldUids  = $fieldObject->getField()->getId();
                    else $visibleFieldUids .=','.$fieldObject->getField()->getId();
                    $individualSearchClause .= '{type:"text"},';
                    $visibleFieldsCounter++;
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
            'dataTableAjaxColumns'=>$dataTableAjaxColumns,
            'formsIds'=>$formIds,
            'organisationUnit' => $organisationUnit,
            'withLowerLevels' => $withLowerLevels,
        );
    }

    /**
     * Returns Employee records json.
     *
     * @Secure(roles="ROLE_RECORDS_EMPLOYEE_RECORDS,ROLE_USER")
     *
     * @Route("/{_format}", requirements={"_format"="json|"}, defaults={"_format"="json"}, name="report_employeerecords_ajax")
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
        $searchString = $this->getRequest()->request->get('sSearch');
        $sEcho = $this->getRequest()->request->get('sEcho');

        if(!isset($searchString)) $searchString=NULL;

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
        $fieldObjects = $queryBuilder->select('field')->from('HrisFormBundle:Field','field')->where($queryBuilder->expr()->in('field.id',$visibleFieldIds))->getQuery()->getResult();
        foreach ($fieldObjects as $key => $fieldObject) {
            $field[strtolower($fieldObject->getName())] = $fieldObject->getId();
            $fieldKeys[strtolower($fieldObject->getName())]=$key; // Used later in tracing FieldObject for resolving fieldOptions
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
            $organisationunitLevelsWhereClause = " Structure.level".$organisationunit->getOrganisationunitStructure()->getLevel()->getLevel()."_id=$organisationunitId AND Structure.level_id >= ( SELECT hris_organisationunitlevel.level FROM hris_organisationunitstructure INNER JOIN hris_organisationunitlevel ON hris_organisationunitstructure.level_id=hris_organisationunitlevel.id  WHERE hris_organisationunitstructure.organisationunit_id=$organisationunitId )  AND Organisationunit.active=True";
        }else {
            $organisationunitLevelsWhereClause = " ResourceTable.organisationunit_id=$organisationunitId AND Organisationunit.active=True";
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
        for ( $i=0 ; $i<count($visibleFieldIds) ; $i++ )
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
                if($fieldObjects[$fieldKeys[$fieldName]]->getInputType()->getName() == 'Date') {
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
        for ( $i=0 ; $i<count($visibleFieldIds) ; $i++ )
        {
            // Building the Select column clause
            if(isset($selectColumnClause)) {
                $selectColumnClause.=",ResourceTable.".strtolower($fieldObjects[$visibleFieldKeysById[$visibleFieldIds[$i]]]->getName())." as ".strtolower($fieldObjects[$visibleFieldKeysById[$visibleFieldIds[$i]]]->getName());
            }else {
                $selectColumnClause="ResourceTable.".strtolower($fieldObjects[$visibleFieldKeysById[$visibleFieldIds[$i]]]->getName())." as ".strtolower($fieldObjects[$visibleFieldKeysById[$visibleFieldIds[$i]]]->getName());
            }
        }
        if(isset($selectColumnClause)) {
            $selectColumnClause.=",ResourceTable.instance as instance,ResourceTable.lastupdated as lastupdated,ResourceTable.organisationunit_name as organisationunit_name";
        }else {
            $selectColumnClause="ResourceTable.instance as instance,ResourceTable.lastupdated as lastupdated,ResourceTable.organisationunit_name as organisationunit_name";
        }
        // Constructing where clause
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
            "sEcho" => intval($sEcho),
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
                    for ( $i=0 ; $i<count($visibleFieldIds) ; $i++ ) {
                        $fieldObject = $fieldObjects[$visibleFieldKeysById[$visibleFieldIds[$i]]];
                        //$fieldObject = $fieldObjects[$i];
                        if ($fieldObject->getInputType()->getName() == 'Select') {
                            $displayValue = $recordArray[strtolower($fieldObject->getName())];
                        }else if ($fieldObject->getInputType()->getName() == 'Date') {
                            if($fieldObject->getIsCalculated()) {

                                if(preg_match_all('/\#{([^\}]+)\}/',$fieldObject->getCalculatedExpression(),$match)) {
                                    foreach($fieldObjects as $fieldKey=>$field) {
                                        if($field->getName()==$match[1][0]) {
                                            // Translates to $field->getName()
                                            $valueKey = $field->getName();
                                        }
                                    }
                                }
                                $derivedDate = new \DateTime($recordArray[strtolower($field->getName())]);
                                // Date Field Value
                                $formattedDerivedDate = $derivedDate->format('d/m/Y');
                                $expression = @@str_replace($match[0][0],$formattedDerivedDate,$fieldObject->getCalculatedExpression());

                                $displayValue = eval("return $expression;");
                            }else {
                                if(isset($recordArray[strtolower($fieldObject->getName())])) {
                                    $displayValue = new \DateTime($recordArray[strtolower($fieldObject->getName())]);
                                    // Date Field Value
                                    $displayValue = $displayValue->format('d/m/Y');
                                }
                            }
                        } else {
                            $displayValue = $recordArray[strtolower($fieldObject->getName())];
                        }
                        if(!empty($displayValue)) $row[]=$displayValue; else $row[] = "";

                    }
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

    /**
     * Download records reports
     *
     * @Route("/download", name="report_employeerecords_download")
     * @Method("GET")
     * @Template()
     */
    public function downloadAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $organisationUnitid =$request->query->get('organisationUnit');
        $withLowerLevels =$request->query->get('withLowerLevels');
        $forms = new ArrayCollection();

        //Get the objects from the the variables
        $formNames = '';
        $formid = '';
        $organisationUnit = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($organisationUnitid);
        foreach($request->query->get('formsId') as $formIds){
            $form = $em->getRepository('HrisFormBundle:Form')->find($formIds);
            $formNames .= $form->getName().',';
            $forms->add($form);
            $formid .= $formIds.',';
        }
        $formNames = rtrim($formNames,",");
        $formid = rtrim($formid,",");

        $title = "Records Report for ".$organisationUnit->getLongname();
        if($withLowerLevels) {
            $title .=" with Lower Levels";
        }
        $title .= " for ".$formNames;

        $selectedOrgunitStructure = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitStructure')->findOneBy(array('organisationunit' => $organisationUnit->getId()));

        $resourceTableName = ResourceTable::getStandardResourceTableName();

        $sql = "SELECT * FROM (SELECT DISTINCT ON (field_id) field_id, form_id, sort, name,caption FROM hris_form_fieldmembers inner join hris_field ON hris_field.id=hris_form_fieldmembers.field_id WHERE form_id in ($formid)) as T order by T.sort";

        $results = $em -> getConnection() -> executeQuery($sql) -> fetchAll();

        //get the list of options to exclude from the reports
        $fieldOptionsToExclude = $em->getRepository('HrisFormBundle:FieldOption')->findBy (
            array('skipInReport' => "YES")
        );

        //create the query to select the records from the resource table
        $query ="SELECT ";

        foreach ($results as $key => $value) {
            $query .= "ResourceTable.".strtolower($value['name'])." ,";
        }
        // Make Levels of orgunit
        $orgunitLevels = $em->createQuery('SELECT DISTINCT ol FROM HrisOrganisationunitBundle:OrganisationunitLevel ol ORDER BY ol.level ')->getResult();

        foreach($orgunitLevels as $orgunitLevelLevelKey=>$orgunitLevel) {
            $orgunitLevelName = "level".$orgunitLevel->getLevel()."_".str_replace(' ','_',str_replace('.','_',str_replace('/','_',$orgunitLevel->getName()))) ;
            $query .= "ResourceTable.".$orgunitLevelName." ,";
        }
        // Make Groupset Column
        $groupsets = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroupset')->findAll();
        foreach($groupsets as $groupsetKey=>$groupset) {
            $query .= "ResourceTable.".$groupset->getName()." ,";
        }

        // Calculated fields
        $query .= "ResourceTable.form_name ,";

        $query .= " Orgunit.longname FROM ".$resourceTableName." ResourceTable inner join hris_organisationunit as Orgunit ON Orgunit.id = ResourceTable.organisationunit_id INNER JOIN hris_organisationunitstructure AS Structure ON Structure.organisationunit_id = ResourceTable.organisationunit_id";
        $query .= " WHERE ResourceTable.form_id in (".$formid.")";
        if($withLowerLevels){
            $query .= " AND Structure.level".$selectedOrgunitStructure->getLevel()->getLevel()."_id=".$organisationUnit->getId();
            $query .= " AND  Structure.level_id >= ";
            $query .= "(SELECT hris_organisationunitstructure.level_id FROM hris_organisationunitstructure WHERE hris_organisationunitstructure.organisationunit_id=".$organisationUnit->getId()." )";
        }else{
            $query .= " AND ResourceTable.organisationunit_id=".$organisationUnit->getId();
        }

        //filter the records with exclude report tag
        foreach($fieldOptionsToExclude as $key => $fieldOptionToExclude){
            $query .= " AND ".$fieldOptionToExclude->getField()->getName()." !='".$fieldOptionToExclude->getValue()."'";
        }

        $report = $em -> getConnection() -> executeQuery($query) -> fetchAll();

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
        $columnmerge = 'A';
        $row += 2;

        //calculate the width of the styles
        for ($i = 1; $i < (count($results)+2+sizeof($orgunitLevels)+sizeof($groupsets)+1); $i++) {
            $columnmerge++;
        }

        //apply the styles
        $excelService->excelObj->getActiveSheet()->getStyle('A1:'.$columnmerge.'2')->applyFromArray($heading_format);
        $excelService->excelObj->getActiveSheet()->mergeCells('A1:'.$columnmerge.'1');
        $excelService->excelObj->getActiveSheet()->mergeCells('A2:'.$columnmerge.'2');

        //write the table heading of the values
        $excelService->excelObj->getActiveSheet()->getStyle('A4:'.$columnmerge.'4')->applyFromArray($header_format);
        $excelService->excelObj->setActiveSheetIndex(0)
            ->setCellValue($column++.$row, 'SN');
        foreach ($results as $key => $value) {
            $excelService->excelObj->setActiveSheetIndex(0)
                ->setCellValue($column++.$row, $value['caption']);
        }
        // Make Levels of orgunit
        foreach($orgunitLevels as $orgunitLevelLevelKey=>$orgunitLevel) {
            $excelService->excelObj->setActiveSheetIndex(0)
                ->setCellValue($column++.$row, $orgunitLevel->getName());
        }
        // Make Groupset Column
        foreach($groupsets as $groupsetKey=>$groupset) {
            $excelService->excelObj->setActiveSheetIndex(0)
                ->setCellValue($column++.$row, $groupset->getName());
        }
        // Calculated fields
        $excelService->excelObj->setActiveSheetIndex(0)
            ->setCellValue($column++.$row, 'Form Name')
            ->setCellValue($column.$row, 'Duty Post');

        //write the values
        $i =1; //count the row
        foreach($report as $rows){
            $column = 'A';//return to the 1st column
            $row++; //increment one row

            //format of the row
            if (($row % 2) == 1)
                $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':'.$columnmerge.$row)->applyFromArray($text_format1);
            else
                $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':'.$columnmerge.$row)->applyFromArray($text_format2);

            //Display the serial number
            $excelService->excelObj->setActiveSheetIndex(0)
                ->setCellValue($column++.$row, $i++);

            foreach ($results as $key => $value) {
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row, $rows[strtolower($value['name'])]);
            }

            // Make Levels of orgunit
            foreach($orgunitLevels as $orgunitLevelLevelKey=>$orgunitLevel) {
                $orgunitLevelName = "level".$orgunitLevel->getLevel()."_".str_replace(' ','_',str_replace('.','_',str_replace('/','_',$orgunitLevel->getName()))) ;
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row,  $rows[strtolower($orgunitLevelName)]);
            }

            // Make Groupset Column
            foreach($groupsets as $groupsetKey=>$groupset) {
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row,  $rows[strtolower($groupset->getName())]);
            }
            // Calculated fields
            $excelService->excelObj->setActiveSheetIndex(0)
                ->setCellValue($column++.$row,  $rows["form_name"])
                ->setCellValue($column.$row,  $rows["longname"]);

        }
        $excelService->excelObj->getActiveSheet()->setTitle('List of Records');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $excelService->excelObj->setActiveSheetIndex(0);

        //create the response
        $title = str_replace(',',' ',$title);
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
     * Download records reports
     *
     * @Route("/download_bycarde", name="report_employeerecords_download_bycarde")
     * @Method("GET")
     * @Template()
     */
    public function downloadByCardeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $organisationUnitid =$request->query->get('organisationUnit');
        $withLowerLevels =$request->query->get('withLowerLevels');
        $forms = new ArrayCollection();

        //Get the objects from the the variables
        $formNames = '';
        $formid = '';
        $organisationUnit = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($organisationUnitid);
        $proffesionFieldId = $em->getRepository('HrisFormBundle:Field')->findOneBy(
                array(
                    'name'=>"Profession"
                ))->getId();;
        foreach($request->query->get('formsId') as $formIds){
            $form = $em->getRepository('HrisFormBundle:Form')->find($formIds);
            $formNames .= $form->getName().',';
            $forms->add($form);
            $formid .= $formIds.',';
        }
        $formNames = rtrim($formNames,",");
        $formid = rtrim($formid,",");

        $title = "Records Report for ".$organisationUnit->getLongname();
        if($withLowerLevels) {
            $title .=" with Lower Levels";
        }
        $title .= " for ".$formNames;
        $title .= ' Order by Cadre';

        $selectedOrgunitStructure = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitStructure')->findOneBy(array('organisationunit' => $organisationUnit->getId()));

        $resourceTableName = ResourceTable::getStandardResourceTableName();

        $sql = "SELECT * FROM (SELECT DISTINCT ON (field_id) field_id, form_id, sort, name,caption FROM hris_form_fieldmembers inner join hris_field ON hris_field.id=hris_form_fieldmembers.field_id WHERE form_id in ($formid)) as T order by T.sort";

        $results = $em -> getConnection() -> executeQuery($sql) -> fetchAll();

        //get the list of options to exclude from the reports
        $fieldOptionsToExclude = $em->getRepository('HrisFormBundle:FieldOption')->findBy (
            array('skipInReport' => "YES")
        );

        //create the query to select the records from the resource table
        $query ="SELECT ";

        foreach ($results as $key => $value) {
            $query .= "ResourceTable.".strtolower($value['name'])." ,";
        }
        // Make Levels of orgunit
        $orgunitLevels = $em->createQuery('SELECT DISTINCT ol FROM HrisOrganisationunitBundle:OrganisationunitLevel ol ORDER BY ol.level ')->getResult();

        foreach($orgunitLevels as $orgunitLevelLevelKey=>$orgunitLevel) {
            $orgunitLevelName = "level".$orgunitLevel->getLevel()."_".str_replace(' ','_',str_replace('.','_',str_replace('/','_',$orgunitLevel->getName()))) ;
            $query .= "ResourceTable.".$orgunitLevelName." ,";
        }
        // Make Groupset Column
        $groupsets = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroupset')->findAll();
        foreach($groupsets as $groupsetKey=>$groupset) {
            $query .= "ResourceTable.".$groupset->getName()." ,";
        }

        // Calculated fields
        $query .= "ResourceTable.form_name ,";

        //From Clause
        $query .= " Orgunit.longname FROM ".$resourceTableName." ResourceTable inner join hris_organisationunit as Orgunit ON Orgunit.id = ResourceTable.organisationunit_id INNER JOIN hris_organisationunitstructure AS Structure ON Structure.organisationunit_id = ResourceTable.organisationunit_id";
        $query .= " INNER JOIN ( SELECT * FROM hris_fieldoption where field_id=".$proffesionFieldId.") AS fieldoption ON fieldoption.value = ResourceTable.profession ";
        $query .= " WHERE ResourceTable.form_id in (".$formid.")";
        if($withLowerLevels){
            $query .= " AND Structure.level".$selectedOrgunitStructure->getLevel()->getLevel()."_id=".$organisationUnit->getId();
            $query .= " AND  Structure.level_id >= ";
            $query .= "(SELECT hris_organisationunitstructure.level_id FROM hris_organisationunitstructure WHERE hris_organisationunitstructure.organisationunit_id=".$organisationUnit->getId()." )";
        }else{
            $query .= " AND ResourceTable.organisationunit_id=".$organisationUnit->getId();
        }

        //filter the records with exclude report tag
        foreach($fieldOptionsToExclude as $key => $fieldOptionToExclude){
            $query .= " AND ".$fieldOptionToExclude->getField()->getName()." !='".$fieldOptionToExclude->getValue()."'";
        }

        $query .= " ORDER BY fieldoption.sort, ResourceTable.dateoffirstappointment";

        $report = $em -> getConnection() -> executeQuery($query) -> fetchAll();

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
        $columnmerge = 'A';
        $row += 2;

        //calculate the width of the styles
        for ($i = 1; $i < (count($results)+2+sizeof($orgunitLevels)+sizeof($groupsets)+1); $i++) {
            $columnmerge++;
        }

        //apply the styles
        $excelService->excelObj->getActiveSheet()->getStyle('A1:'.$columnmerge.'2')->applyFromArray($heading_format);
        $excelService->excelObj->getActiveSheet()->mergeCells('A1:'.$columnmerge.'1');
        $excelService->excelObj->getActiveSheet()->mergeCells('A2:'.$columnmerge.'2');

        //write the values
        $i =1; //count the row
        $currentProfessional = null;
        foreach($report as $rows){
            $column = 'A';//return to the 1st column
            $row++; //increment one row
            if($currentProfessional != $rows['profession'] ){
                //write the heading for the professional
                $row++;
                $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':D'.$row)->applyFromArray($heading_format);
                $excelService->excelObj->getActiveSheet()->mergeCells($column.$row.':D'.$row);
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column.$row, $rows['profession']);

                //Write the heading for the data
                $row++;
                $column = 'A';//reset to the first column
                $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':'.$columnmerge.$row)->applyFromArray($header_format);
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row, 'SN');
                foreach ($results as $key => $value) {
                    $excelService->excelObj->setActiveSheetIndex(0)
                        ->setCellValue($column++.$row, $value['caption']);
                }
                // Make Levels of orgunit
                foreach($orgunitLevels as $orgunitLevelLevelKey=>$orgunitLevel) {
                    $excelService->excelObj->setActiveSheetIndex(0)
                        ->setCellValue($column++.$row, $orgunitLevel->getName());
                }
                // Make Groupset Column
                foreach($groupsets as $groupsetKey=>$groupset) {
                    $excelService->excelObj->setActiveSheetIndex(0)
                        ->setCellValue($column++.$row, $groupset->getName());
                }
                // Calculated fields
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row, 'Form Name')
                    ->setCellValue($column.$row, 'Duty Post');

                $i =1;//reset the serial number
                $row++;
                $column = 'A';//return to the 1st column
            }

            //format of the row
            if (($row % 2) == 1)
                $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':'.$columnmerge.$row)->applyFromArray($text_format1);
            else
                $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':'.$columnmerge.$row)->applyFromArray($text_format2);

            //Display the serial number
            $excelService->excelObj->setActiveSheetIndex(0)
                ->setCellValue($column++.$row, $i++);

            foreach ($results as $key => $value) {
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row, $rows[strtolower($value['name'])]);
            }

            // Make Levels of orgunit
            foreach($orgunitLevels as $orgunitLevelLevelKey=>$orgunitLevel) {
                $orgunitLevelName = "level".$orgunitLevel->getLevel()."_".str_replace(' ','_',str_replace('.','_',str_replace('/','_',$orgunitLevel->getName()))) ;
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row,  $rows[strtolower($orgunitLevelName)]);
            }

            // Make Groupset Column
            foreach($groupsets as $groupsetKey=>$groupset) {
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row,  $rows[strtolower($groupset->getName())]);
            }
            // Calculated fields
            $excelService->excelObj->setActiveSheetIndex(0)
                ->setCellValue($column++.$row,  $rows["form_name"])
                ->setCellValue($column.$row,  $rows["longname"]);

            $currentProfessional = $rows['profession'];

        }
        $excelService->excelObj->getActiveSheet()->setTitle('List of Records');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $excelService->excelObj->setActiveSheetIndex(0);

        //create the response
        $title = str_replace(',',' ',$title);
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
