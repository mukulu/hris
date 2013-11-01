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

use Hris\RecordsBundle\Entity\Record;
use Hris\ReportsBundle\Form\ReportOrganisationunitCompletenessType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Report Organisationunit Completeness controller.
 *
 * @Route("/reports/organisationunit/completeness")
 */
class ReportOrganisationunitCompletenessController extends Controller
{

    /**
     * Show Report Form for generation of Organisation unit completeness
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_REPORTORGANISATIONUNITCOMPLETENESS_GENERATE,ROLE_USER")
     * @Route("/", name="report_organisationunit_completeness")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $organisationunitCompletenessForm = $this->createForm(new ReportOrganisationunitCompletenessType($this->getUser()),null,array('em'=>$this->getDoctrine()->getManager()));

        return array(
            'organisationunitCompletenessForm'=>$organisationunitCompletenessForm->createView(),
        );
    }

    /**
     * Generate Report for Organisationunit Completeness
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_REPORTORGANISATIONUNITCOMPLETENESS_GENERATE,ROLE_USER")
     * @Route("/", name="report_organisationunit_completeness_generate")
     * @Method("PUT")
     * @Template()
     */
    public function generateAction(Request $request)
    {
        $organisationunitCompletenessForm = $this->createForm(new ReportOrganisationunitCompletenessType($this->getUser()),null,array('em'=>$this->getDoctrine()->getManager()));
        $organisationunitCompletenessForm->bind($request);

        if ($organisationunitCompletenessForm->isValid()) {
            $organisationunitCompletenessFormData = $organisationunitCompletenessForm->getData();
            $this->organisationunit = $organisationunitCompletenessFormData['organisationunit'];
            $this->organisationunitLevel = $organisationunitCompletenessFormData['organisationunitLevel'];
            $this->forms = $organisationunitCompletenessFormData['forms'];

        }else {
            $organisationunitCompletenessFormData = $organisationunitCompletenessForm->getData();
            $this->organisationunit = $organisationunitCompletenessFormData['organisationunit'];
            $this->organisationunitLevel = $organisationunitCompletenessFormData['organisationunitLevel'];
            $this->forms = $organisationunitCompletenessFormData['forms'];
            if(empty($organisationunit) && empty($this->forms)) {
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Your changes were saved!'
                );
                return $this->redirect($this->generateUrl('report_organisationunit_completeness'));
            }
        }
        $this->processCompletenessFigures();

        return array(
            'title' => $this->title,
            'organisationunitChildren'=>$this->organisationunitChildren,
            'rootNodeOrganisationunit'=>$this->rootNodeOrganisationunit,
            'visibleFields'=>$this->visibleFields,
            'forms'=>$this->forms,
            'level'=>$this->organisationunitLevel,
            'sameLevel'=>$this->sameLevel,
            'completenessMatrix'=>$this->completenessMatrix,
            'expectedCompleteness'=>$this->expectedCompleteness,
            'totalCompletenessMatrix'=>$this->totalCompletenessMatrix,
            'totalExpectedCompleteness'=>$this->totalExpectedCompleteness,
            'recordsToDisplay'=>$this->recordsToDisplay,
            'recordInstances'=>$this->recordInstances,
            'parent'=>$this->parent,
        );
    }

    /**
     * Download Report for Organisationunit Completeness
     *
     * @Route("/download", name="report_organisationunit_completeness_download")
     * @Method("GET")
     * @Template()
     */
    public function downloadAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $organisationUnitid =$request->query->get('organisationUnitid');
        $formsId = explode(",",$request->query->get('formids'));
        $forms = new ArrayCollection();

        //Get the objects from the the variables
        $this->organisationunit = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($organisationUnitid);
        $this->organisationunitLevel = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitLevel')->findOneBy(array('level'=>$this->organisationunit->getOrganisationunitStructure()->getLevel()->getLevel()+1));
        foreach($formsId as $formId){
            $forms->add($em->getRepository('HrisFormBundle:Form')->find($formId)) ;
        }
        $this->forms = $forms;
        $this->processCompletenessFigures();

        // ask the service for a Excel5
        $excelService = $this->get('xls.service_xls5');
        $excelService->excelObj->getProperties()->setCreator("HRHIS3")
            ->setLastModifiedBy("HRHIS3")
            ->setTitle($this->title)
            ->setSubject("Office 2005 XLSX Test Document")
            ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("Test result file");

        //add style to the header
        $heading_format = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000099'),
                'size' => 12,
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
                'startcolor' => array('rgb' => '3333FF') ,
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

        //write the header of the report
        $column = 'A';
        $row  = 1;
        $date = "Date: ".date("jS F Y");
        $excelService->excelObj->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
        $excelService->excelObj->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
        //Merge the Title Rows
        $mergeColumnTitle = 'A';
        if($this->visibleFields)
            for($i=1; $i < count($this->visibleFields) + 2; $i++) $mergeColumnTitle++;
        else
            for($i=1; $i < count($this->forms)*3 + 2; $i++) $mergeColumnTitle++;
        $excelService->excelObj->getActiveSheet()->mergeCells($column.$row.':'.$mergeColumnTitle.$row);
        $excelService->excelObj->setActiveSheetIndex(0)
            ->setCellValue($column.$row++, $this->title);
        $excelService->excelObj->getActiveSheet()->mergeCells($column.$row.':'.$mergeColumnTitle.$row);
        $excelService->excelObj->setActiveSheetIndex(0)
            ->setCellValue($column.$row, $date);
        //Apply the heading Format and Set the Row dimension for the headers
        $excelService->excelObj->getActiveSheet()->getStyle('A1:'.$mergeColumnTitle.'2')->applyFromArray($heading_format);
        $excelService->excelObj->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
        $excelService->excelObj->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

        //reset the colomn and row number
        $column == 'A';
        $row += 2;

        if ($this->organisationunitChildren){
            if($this->organisationunitChildren){

                //write the table heading of the values
                $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':'.$mergeColumnTitle.($row+1))->applyFromArray($header_format);
                $excelService->excelObj->getActiveSheet()->mergeCells($column.$row.':'.$column.($row+1));
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row, 'SN');
                $excelService->excelObj->getActiveSheet()->mergeCells($column.$row.':'.$column.($row+1));
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row, 'Organisationunit');
            }
            foreach($this->forms as $forms){
                if(($this->organisationunitChildren) || ($this->sameLevel)){
                    if($this->visibleFields){
                        $colspan = count($this->visibleFields);
                    }else{
                        $colspan = 3;
                    }
                }else{
                    $colspan = 3;
                }

                if($this->organisationunitChildren){
                    $mergeColumn = $column;
                    for($i = 1; $i < $colspan; $i++)  $mergeColumn++;
                    $excelService->excelObj->getActiveSheet()->mergeCells($column.$row.':'.$mergeColumn.$row);
                    $excelService->excelObj->setActiveSheetIndex(0)
                        ->setCellValue($column++.$row,$forms->getName());
                    for($i = 1; $i < $colspan; $i++)  $column++;
                }
            }
        }elseif($this->visibleFields){
            //Headers for records compeleteness
            $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row,'SN');
            foreach($this->visibleFields as $visibleField){
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row,$visibleField->getCaption());
            }
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row,'Form name');
        }
        if(($this->organisationunitChildren) || ($this->sameLevel)){
            $row++;
            $column = 'C';
            foreach($this->forms as $form){
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row,'Entered Records')
                    ->setCellValue($column++.$row,'Expected Records')
                    ->setCellValue($column++.$row,'Percentage');
            }
        }

        $counter = 0;
        $row++;
        $column = 'A';
        //Start populating the data
        if($this->organisationunitChildren){

            foreach( $this->organisationunitChildren as $childOrganisationunit){
                $counter = $counter + 1;
                //format of the row
                if (($counter % 2) == 1)
                    $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':'.$mergeColumnTitle.$row)->applyFromArray($text_format1);
                else
                    $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':'.$mergeColumnTitle.$row)->applyFromArray($text_format2);

                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row,$counter)
                    ->setCellValue($column++.$row,$childOrganisationunit->getLongname());

                foreach($this->forms as $form){
                    # Entered records #
                    $excelService->excelObj->setActiveSheetIndex(0)
                        ->setCellValue($column++.$row,$this->completenessMatrix[$childOrganisationunit->getId()][$form->getId()]);
                    # Expected records and Percentage #
                    if($this->expectedCompleteness[$childOrganisationunit->getId()][$form->getId()]){
                        $excelService->excelObj->setActiveSheetIndex(0)
                            ->setCellValue($column++.$row,$this->expectedCompleteness[$childOrganisationunit->getId()][$form->getId()]);
                        if($this->completenessMatrix[$childOrganisationunit->getId()][$form->getId()] > $this->expectedCompleteness[$childOrganisationunit->getId()][$form->getId()])
                            $excelService->excelObj->setActiveSheetIndex(0)
                                ->setCellValue($column++.$row,'Above Expected');
                        elseif($this->expectedCompleteness[$childOrganisationunit->getId()][$form->getId()]<=0)
                            $excelService->excelObj->setActiveSheetIndex(0)
                                ->setCellValue($column++.$row,'');
                        else
                            $excelService->excelObj->setActiveSheetIndex(0)
                                ->setCellValue($column++.$row,round(($this->completenessMatrix[$childOrganisationunit->getId()][$form->getId()] / $this->expectedCompleteness[$childOrganisationunit->getId()][$form->getId()] )*100),2 );

                    }else{
                        # Expected records & percentage#
                        $excelService->excelObj->setActiveSheetIndex(0)
                            ->setCellValue($column++.$row,'')
                            ->setCellValue($column++.$row,'');
                    }
                }
                $row++;
                $column = 'A';
            }
        }else{
            foreach($this->recordInstances as $recordInstance){
                $counter = $counter + 1;
                //format of the row
                if (($counter % 2) == 1)
                    $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':'.$mergeColumnTitle.$row)->applyFromArray($text_format1);
                else
                    $excelService->excelObj->getActiveSheet()->getStyle($column.$row.':'.$mergeColumnTitle.$row)->applyFromArray($text_format2);
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row,$counter);

                foreach($this->visibleFields as $visibleField){
                    $excelService->excelObj->setActiveSheetIndex(0)
                        ->setCellValue($column++.$row,$this->recordsToDisplay[$recordInstance][$visibleField->getUid()]);
                }
                $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue($column++.$row,$this->recordsToDisplay[$recordInstance]['form']);
                $row++;
                $column = 'A';

            }
        }




        $excelService->excelObj->getActiveSheet()->setTitle('Completeness Report');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $excelService->excelObj->setActiveSheetIndex(0);

        //create the response

        $response = $excelService->getResponse();
        $response->headers->set('Content-Type', 'application/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition','attachment;filename='.$this->title.'.xls');

        // If you are using a https connection, you have to set those two headers and use sendHeaders() for compatibility with IE <9
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        //$response->sendHeaders();
        return $response;
    }

    /**
     * Generate a Report Redirect for Organisationunit Completeness
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_REPORTORGANISATIONUNITCOMPLETENESS_GENERATE,ROLE_USER")
     * @Route("/generate/redirect", name="report_organisationunit_completeness_generate_redirect")
     * @Method("GET")
     * @Template("HrisReportsBundle:ReportOrganisationunitCompleteness:generate.html.twig")
     */
    public function generateRedirectAction(Request $request)
    {

        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
        $organisationunitId = $this->getRequest()->query->get('organisationunit');
        $formIds = $this->getRequest()->query->get('forms');

        $em = $this->getDoctrine()->getManager();
        $this->organisationunit = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->findOneBy(array('id'=>$organisationunitId));
        $this->organisationunitLevel = $this->getDoctrine()->getManager()->getRepository('HrisOrganisationunitBundle:OrganisationunitLevel')->findOneBy(array('level' => ($this->organisationunit->getOrganisationunitStructure()->getLevel()->getLevel()+1) ));
        $this->forms = $queryBuilder->select('form')->from('HrisFormBundle:Form','form')->where($queryBuilder->expr()->in('form.id',$formIds))->getQuery()->getResult();

        $this->processCompletenessFigures();

        return array(
            'title' => $this->title,
            'organisationunitChildren'=>$this->organisationunitChildren,
            'rootNodeOrganisationunit'=>$this->rootNodeOrganisationunit,
            'visibleFields'=>$this->visibleFields,
            'forms'=>$this->forms,
            'sameLevel'=>$this->sameLevel,
            'completenessMatrix'=>$this->completenessMatrix,
            'expectedCompleteness'=>$this->expectedCompleteness,
            'totalCompletenessMatrix'=>$this->totalCompletenessMatrix,
            'totalExpectedCompleteness'=>$this->totalExpectedCompleteness,
            'recordsToDisplay'=>$this->recordsToDisplay,
            'recordInstances'=>$this->recordInstances,
            'parent'=>$this->parent,
        );
    }
    
    public function processCompletenessFigures()
    {
        // Create FormIds
        $formIds = NULL;
        foreach($this->forms as $formKey=>$formObject) {
            if(empty($formIds)) $formIds=$formObject->getId();else $formIds.=','.$formObject->getId();
        }
        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
        // Determine lowest level in organisationunit structure
        $lowestOrganisationunitLevel = $this->array_value_recursive('maxLevel',$this->getDoctrine()->getManager()->createQuery('SELECT MAX(organisationunitLevel.level) as maxLevel FROM HrisOrganisationunitBundle:OrganisationunitLevel organisationunitLevel')->getResult());
        // Determine organisation unit(childrens) to display
        if(!empty($this->organisationunitLevel) && $this->organisationunitLevel->getLevel() > $this->organisationunit->getOrganisationunitStructure()->getLevel()->getLevel() ) {
            // Display children for the given level is passed, provided level is below the parent organisationunit
            if(!isset($this->sameLevel)) {
                $this->title = "Completeness Report for All " . $this->organisationunitLevel->getName() . " Under ". $this->organisationunit->getLongname(); // Create title
            }else{
                $this->title = "Completeness Report for Employees directly under ". $this->organisationunit->getLongname(); // Create title
            }
            $this->organisationunitChildren = $queryBuilder->select('organisationunit')
                ->from('HrisOrganisationunitBundle:Organisationunit', 'organisationunit')
                ->innerJoin('organisationunit.organisationunitStructure', 'organisationunitStructure')
                ->where('organisationunitStructure.level=:organisationunitLevel')
                ->andWhere('organisationunitStructure.level'.$this->organisationunit->getOrganisationunitStructure()->getLevel()->getLevel().'Organisationunit=:levelOrganisationunit')
                ->andWhere('organisationunit.active=True')
                ->setParameters(array(
                    'organisationunitLevel'=>$this->organisationunitLevel,
                    'levelOrganisationunit'=>$this->organisationunit,
                ))
                ->getQuery()->getResult();
        }else {
            // Display children for lower level of the selected organisationunit
            $lowerOrganisationunitCount = $this->getDoctrine()->getManager()->createQuery("SELECT COUNT(lowerOrganisationunit.id)
                                                            FROM HrisOrganisationunitBundle:Organisationunit lowerOrganisationunit
                                                            INNER JOIN lowerOrganisationunit.parent parentOrganisationunit
                                                            WHERE lowerOrganisationunit.active=True AND parentOrganisationunit.id=".$this->organisationunit->getId())->getSingleScalarResult();
            if($this->organisationunit->getOrganisationunitStructure()->getLevel()->getLevel() !== $lowestOrganisationunitLevel  && !empty($lowerOrganisationunitCount)) {
                // Deal with organisationunit with children
                $this->organisationunitLevel = $this->getDoctrine()->getManager()->getRepository('HrisOrganisationunitBundle:OrganisationunitLevel')->findOneBy(array('level' => ($this->organisationunit->getOrganisationunitStructure()->getLevel()->getLevel()+1) ));
                $levelName=$this->organisationunitLevel->getName() ." Under ";
            }else {
                $levelName=NULL;
            }
            $this->organisationunitChildren = $this->getDoctrine()->getRepository('HrisOrganisationunitBundle:Organisationunit')->getImmediateChildren($this->organisationunit);
            if(!isset($this->sameLevel)) {
                $this->title = "Completeness Report for ". $levelName . $this->organisationunit->getLongname(); // Create title
            }else{
                $this->title = "Completeness Report for Employees directly under ". $this->organisationunit->getLongname(); // Create title
            }

        }
        $this->rootNodeOrganisationunit = $this->organisationunit; // Establish the root node
        $this->parent = NULL;
        //$userObject = $this->getDoctrine()->getManager()->getRepository('User')->findOneBy(array('username' => $user->getUsername()));

        //check to make sure you can not go beyond your assigned level
        //@todo implement checking user organisationunit
//        if ($this->organisationunit->getOrganisationunitStructure()->getLevel()->getLevel() >= $userObject->getOrganisationunit()->getOrganisationunitStructure()->getLevel()) {
//            $this->parent = $this->organisationunit->getParent();
//        }else {
//            $this->parent = $userObject->getOrganisationunit();
//        }
        $this->parent = $this->organisationunit->getParent();


        // Query for Options to exclude from reports
        $fieldOptionsToSkip = $this->getDoctrine()->getManager()->getRepository('HrisFormBundle:FieldOption')->findBy (array('skipInReport' =>True));
        $maskIncr=1;
        $maskParameters=NULL;$whereExpression=NULL;
        if(!empty($fieldOptionsToSkip)) {
            foreach($fieldOptionsToSkip as $key => $fieldOptionToSkip) {
                /**
                 * Made dynamic, on which field column is used as key, i.e. uid, name or id.
                 */
                // Translates to $field->getUid()
                // or $field->getUid() depending on value of $recordKeyName
                $recordFieldKey = ucfirst(Record::getFieldKey());
                $valueKey = call_user_func_array(array($fieldOptionToSkip->getField(), "get${recordFieldKey}"),array());

                $recordFieldOptionKey = ucfirst(Record::getFieldOptionKey());
                // Translates to $fieldOptionToskip->getUid() assuming Record::getFieldOptionKey returns "uid"
                $valuePattern[$valueKey] = call_user_func_array(array($fieldOptionToSkip, "get${recordFieldOptionKey}"),array());

                $jsonPattern = json_encode($valuePattern);
                $subJsonpatern = str_replace("{", "", $jsonPattern);
                $subJsonpatern = str_replace("}", "", $subJsonpatern);
                if($whereExpression==NULL) $whereExpression =' record.value NOT LIKE ?'.$maskIncr; else $whereExpression .=' AND record.value NOT LIKE ?'.$maskIncr;
                $maskParameters[$maskIncr]='%'.$subJsonpatern.'%';
                $maskIncr++;
            }
            if($whereExpression==NULL) $whereExpression =' record.value LIKE ?'.$maskIncr; else $whereExpression .=' AND record.value LIKE ?'.$maskIncr;
            // Translates to $field->getUid()
            // or $field->getUid() depending on value of $recordKeyName
            $recordFieldKey = ucfirst(Record::getFieldKey());
            $valueKey = call_user_func_array(array($fieldOptionToSkip->getField(), "get${recordFieldKey}"),array());
            $maskParameters[$maskIncr]='%"'.$valueKey.'":%';
        }
        if (isset($this->organisationunitChildren)) {

            // user choose district and above show total records in the lower facilities
            $this->completenessMatrix=NULL;
            $this->expectedCompleteness=NULL;
            $this->totalExpectedCompleteness=NULL;
            $this->totalCompletenessMatrix=NULL;
            foreach ($this->organisationunitChildren as $key => $childOrganisationunit) {
                /*
                 * Construct completeness matrix
                 * @Note: $this->completenessMatrix[organisationunitId][formId] //Holds particular value
                 * @Note: $this->totalCompletenessMatrix[formId] // Holds total for all records of the organisationunits
                 * @Note: $childrenNames[organisationunitId] hold names of OrganisationUnits in completeness matrix
                 * @Note: $this->expectedCompleteness[organisationunitId][formId]
                 */
                foreach($this->forms as $key=>$form ) {
                    $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
                    if($childOrganisationunit->getOrganisationunitStructure()->getLevel()->getLevel() !== $lowestOrganisationunitLevel ) {
                        // Calculation for levels above the lowest ( Sum for only records below the selectedLevel)
                        $aliasParameters=$maskParameters;
                        $aliasParameters['levelId']=$childOrganisationunit->getId();
                        $valuecount = $queryBuilder->select('COUNT(record.instance) as employeeCount ')
                            ->from('HrisRecordsBundle:Record','record')
                            ->join('record.organisationunit','organisationunit')
                            ->join('record.form','form')
                            ->join('organisationunit.organisationunitStructure','organisationunitStructure')
                            ->join('organisationunitStructure.level','level')
                            ->andWhere('
					            			( 
						            			(
					            					level.level >= :organisationunitLevel
						            				AND organisationunitStructure.level'.$childOrganisationunit->getOrganisationunitStructure()->getLevel()->getLevel().'Organisationunit=:levelId
						            			)
					            				OR organisationunit.id=:organisationunitId
					            			)'
                            )
                            ->andWhere($queryBuilder->expr()->in('form.id',$form->getId()))
                            ->andWhere('organisationunit.active=True')
                            ->andWhere($whereExpression); // Append in query, field options to exclude
                        $aliasParameters['organisationunitLevel']=$childOrganisationunit->getOrganisationunitStructure()->getLevel()->getLevel();
                        $aliasParameters['organisationunitId']=$childOrganisationunit->getId();
                        $valuecount=$valuecount->setParameters($aliasParameters) // Set mask value for all parameters
                            ->getQuery()->getResult();

                        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
                        $expectations = $queryBuilder->select('SUM(organisationunitCompleteness.expectation) as expectation')
                            ->from('HrisOrganisationunitBundle:Organisationunit','organisationunit')
                            ->join('organisationunit.organisationunitCompleteness','organisationunitCompleteness')
                            ->join('organisationunitCompleteness.form','form')
                            ->join('organisationunit.organisationunitStructure','organisationunitStructure')
                            ->join('organisationunitStructure.level','level')
                            ->andWhere('(
					            			( level.level >= :organisationunitLevel AND organisationunitStructure.level'.$childOrganisationunit->getOrganisationunitStructure()->getLevel()->getLevel().'Organisationunit=:levelId )
					            			OR organisationunit.id=:organisationunitid
					            	    )'
                            )
                            ->andWhere($queryBuilder->expr()->in('form.id',$form->getId()))
                            ->andWhere('organisationunit.active=True')
                            ->setParameters(array(
                                'levelId'=>$childOrganisationunit->getId(),
                                'organisationunitLevel'=>$childOrganisationunit->getOrganisationunitStructure()->getLevel()->getLevel(),
                                'organisationunitid'=>$childOrganisationunit->getId(),
                            ))
                            ->getQuery()->getResult();
                        $this->completenessMatrix[$childOrganisationunit->getId()][$form->getId()] = $this->array_value_recursive('employeeCount',$valuecount);
                        $this->expectedCompleteness[$childOrganisationunit->getId()][$form->getId()] = $this->array_value_recursive('expectation',$expectations);
                        if(isset($this->totalCompletenessMatrix[$form->getId()])) {
                            $this->totalCompletenessMatrix[$form->getId()] += $this->completenessMatrix[$childOrganisationunit->getId()][$form->getId()];
                        }else {
                            $this->totalCompletenessMatrix[$form->getId()] = $this->completenessMatrix[$childOrganisationunit->getId()][$form->getId()];
                        }
                        if(isset($this->totalExpectedCompleteness[$form->getId()])) {
                            $this->totalExpectedCompleteness[$form->getId()] += $this->expectedCompleteness[$childOrganisationunit->getId()][$form->getId()];
                        }else {
                            $this->totalExpectedCompleteness[$form->getId()] = $this->expectedCompleteness[$childOrganisationunit->getId()][$form->getId()];
                        }
                        $childrenNames[$childOrganisationunit->getId()]= $childOrganisationunit->getLongname();
                    }else {
                        // Calculation for the lowest level ( Sum for records of that particular organisationunits )
                        $aliasParameters=$maskParameters;
                        $aliasParameters['organisationunitId']=$childOrganisationunit->getId();
                        $valuecount = $queryBuilder->select('COUNT(record.instance) as employeeCount ')
                            ->from('HrisRecordsBundle:Record','record')
                            ->join('record.organisationunit','organisationunit')
                            ->join('record.form','form')
                            ->andWhere($queryBuilder->expr()->in('form.id',$form->getId()))
                            ->andWhere('organisationunit.id=:organisationunitId')
                            ->andWhere('organisationunit.active=True')
                            ->andWhere($whereExpression) // Append in query, field options to exclude
                            ->setParameters($aliasParameters) // Set mask value for all parameters
                            ->getQuery()->getResult();
                        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
                        // Sum of expectation records
                        $expectations = $queryBuilder->select('SUM(organisationunitCompleteness.expectation) as expectation')
                            ->from('HrisOrganisationunitBundle:Organisationunit','organisationunit')
                            ->join('organisationunit.organisationunitCompleteness','organisationunitCompleteness')
                            ->join('organisationunitCompleteness.form','form')
                            ->join('organisationunit.organisationunitStructure','organisationunitStructure')
                            ->andWhere($queryBuilder->expr()->in('form.id',$form->getId()))
                            ->andWhere('organisationunit.id=:organisationunitId')
                            ->andWhere('organisationunit.active=True')
                            ->setParameters(array(
                                'organisationunitId'=>$childOrganisationunit->getId()
                            ))
                            ->getQuery()->getResult();
                        $this->completenessMatrix[$childOrganisationunit->getId()][$form->getId()] = $this->array_value_recursive('employeeCount',$valuecount);
                        $this->expectedCompleteness[$childOrganisationunit->getId()][$form->getId()] = $this->array_value_recursive('expectation',$expectations);
                        if(isset($this->totalCompletenessMatrix[$form->getId()])) {
                            $this->totalCompletenessMatrix[$form->getId()] += $this->completenessMatrix[$childOrganisationunit->getId()][$form->getId()];
                        }else {
                            $this->totalCompletenessMatrix[$form->getId()] = $this->completenessMatrix[$childOrganisationunit->getId()][$form->getId()];
                        }
                        if(isset($this->totalExpectedCompleteness[$form->getId()])) {
                            $this->totalExpectedCompleteness[$form->getId()] += $this->expectedCompleteness[$childOrganisationunit->getId()][$form->getId()];
                        }else {
                            $this->totalExpectedCompleteness[$form->getId()] = $this->expectedCompleteness[$childOrganisationunit->getId()][$form->getId()];
                        }
                        $childrenNames[$childOrganisationunit->getId()]= $childOrganisationunit->getLongname();
                    }
                }
            }
            // Completeness for the root node organisationunit
            foreach($this->forms as $key=>$form ) {
                $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
                $aliasParameters=$maskParameters;
                $aliasParameters['organisationunitId']=$this->rootNodeOrganisationunit->getId();
                $valuecount = $queryBuilder->select('COUNT(record.instance) as employeeCount ')
                    ->from('HrisRecordsBundle:Record','record')
                    ->join('record.organisationunit','organisationunit')
                    ->join('record.form','form')
                    ->andWhere($queryBuilder->expr()->in('form.id',$form->getId()))
                    ->andWhere('organisationunit.id=:organisationunitId')
                    ->andWhere('organisationunit.active=True')
                    ->andWhere($whereExpression) // Append in query, field options to exclude
                    ->setParameters($aliasParameters) // Set mask value for all parameters
                    ->getQuery()->getResult();
                $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
                $expectations = $queryBuilder->select('SUM(organisationunitCompleteness.expectation) as expectation')
                    ->from('HrisOrganisationunitBundle:Organisationunit','organisationunit')
                    ->join('organisationunit.organisationunitCompleteness','organisationunitCompleteness')
                    ->join('organisationunitCompleteness.form','form')
                    ->join('organisationunit.organisationunitStructure','organisationunitStructure')
                    ->andWhere($queryBuilder->expr()->in('form.id',$form->getId()))
                    ->andWhere('organisationunit.id=:organisationunitId')
                    ->andWhere('organisationunit.active=True')
                    ->setParameters(array('organisationunitId'=>$this->rootNodeOrganisationunit->getId()))
                    ->getQuery()->getResult();
                $this->completenessMatrix[$this->rootNodeOrganisationunit->getId()][$form->getId()] = $this->array_value_recursive('employeeCount',$valuecount);
                $this->expectedCompleteness[$this->rootNodeOrganisationunit->getId()][$form->getId()] = $this->array_value_recursive('expectation',$expectations);
                // Summation of total completeness for selected forms
                if(isset($this->totalCompletenessMatrix[$form->getId()])) {
                    $this->totalCompletenessMatrix[$form->getId()] += $this->completenessMatrix[$this->rootNodeOrganisationunit->getId()][$form->getId()];
                }else {
                    $this->totalCompletenessMatrix[$form->getId()] = $this->completenessMatrix[$this->rootNodeOrganisationunit->getId()][$form->getId()];
                }
                // Summation of total expected completeness for sselected forms
                if(isset($this->totalExpectedCompleteness[$form->getId()])) {
                    $this->totalExpectedCompleteness[$form->getId()] += $this->expectedCompleteness[$this->rootNodeOrganisationunit->getId()][$form->getId()];
                }else {
                    $this->totalExpectedCompleteness[$form->getId()] = $this->expectedCompleteness[$this->rootNodeOrganisationunit->getId()][$form->getId()];
                }

                $childrenNames[$this->rootNodeOrganisationunit->getId()]= $this->rootNodeOrganisationunit->getLongname();
            }
            // Account for displaying of individual records
            if(empty($this->organisationunitChildren) || isset($this->sameLevel)) {
                // When organisationunit has no children display  records(Only root node found)

                $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
                // Gather Visible Fields from all passed Forms
                if(!empty($this->forms)) {
                    $visibleFieldIds = $queryBuilder->select('DISTINCT(field),formVisibleFields.sort')
                        ->from('HrisFormBundle:FormVisibleFields','formVisibleFields')
                        ->innerJoin('formVisibleFields.field', 'field')
                        ->innerJoin('formVisibleFields.form', 'form')
                        ->where($queryBuilder->expr()->in('form.id',$formIds))
                        ->orderBy('formVisibleFields.sort','ASC')->getQuery()->getResult();
                    foreach($visibleFieldIds as $visibleKey=>$visibleFieldId) {
                        $this->visibleFields[] = $this->getDoctrine()->getManager()->createQueryBuilder()->select('afield')->from('HrisFormBundle:Field','afield')->where($queryBuilder->expr()->in('afield.id',$visibleFieldId['id']))->getQuery()->getSingleResult();
                        $this->getDoctrine()->getManager()->flush();
                    }
                }else {
                    /*
                     * Make all fields visible
                    */
                    $this->visibleFields = $queryBuilder->select('field')->from('HrisFormBundle:Field','field')->orderBy('field.name','ASC')->getQuery()->getResult();
                }
                //Preparing array of Combination Categories
                $fieldOption = $this->getDoctrine()->getManager()->getRepository('HrisFormBundle:FieldOption')->findAll();

                $counter=0;
                // For Organisation Units without childrens
                $aliasParameters=$maskParameters;
                $aliasParameters['organisationunitId']=$this->organisationunit->getId();
                $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
                $records = $queryBuilder->select('record')->from('HrisRecordsBundle:Record','record')->join('record.organisationunit','organisationunit')->join('record.form','form')
                    ->andWhere($queryBuilder->expr()->in('form.id',$formIds))
                    ->andWhere('organisationunit.id=:organisationunitId')
                    ->andWhere('organisationunit.active=True')
                    ->andWhere($whereExpression) // Append in query, field options to exclude
                    ->setParameters($aliasParameters) // Set mask value for all parameters
                    ->getQuery()->getResult();
                foreach ($fieldOption as $key => $optionObject) {
                    // Translates to $optionObject->getUid()
                    // or $optionObject->getUid() depending on value of $recordKeyName
                    $recordFieldOptionKey = ucfirst(Record::getFieldOptionKey());
                    $fieldOptionKey = call_user_func_array(array($optionObject, "get${recordFieldOptionKey}"),array());
                    $option[$fieldOptionKey] = $optionObject->getValue();
                }
                foreach($records as $key=>$dataValueInstance) {
                    echo '<tr height="20">';
                    echo '	<td id="'.$dataValueInstance->getInstance().'">'.++$counter.'</td>';
                    foreach($this->visibleFields as $key=>$visibleField) {
                        /**
                         * Made dynamic, on which field column is used as key, i.e. uid, name or id.
                         */
                        // Translates to $field->getUid()
                        // or $visibleField->getUid() depending on value of $recordKeyName
                        $recordFieldKey = ucfirst(Record::getFieldKey());
                        $valueKey = call_user_func_array(array($visibleField, "get${recordFieldKey}"),array());


                        $dataValue = $dataValueInstance->getValue();
                        if ($visibleField->getInputType()->getName() == 'combo') {
                            if(isset($option[$dataValue[$valueKey]])) $displayValue = $option[$dataValue[$valueKey]];else $displayValue='';
                        }
                        else if ($visibleField->getInputType()->getName() == 'date') {
                            if(!empty($dataValue[$visibleField->getId()])) {
                                $dataValue[$valueKey] = new DateTime($dataValue[$visibleField->getId()]['date'],new DateTimeZone ($dataValue[$valueKey]['timezone']));
                                $displayValue = $dataValue[$valueKey];
                                $displayValue = $displayValue->format('d/m/Y');
                            }
                        }else {
                            $displayValue = $dataValue[$valueKey];
                        }
                        echo '<td>'.$displayValue .'</td>';
                    }
                    echo '<td>'.$dataValueInstance->getForm()->getName().'</td>';
                    echo '</tr>';
                }


            }
        }else {
            // Organsiationunit without children
            $counter=0;
            // For Organisation Units without childrens
            $aliasParameters=$maskParameters;
            $aliasParameters['organisationunitId']=$this->organisationunit->getId();
            $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
            $records = $queryBuilder->select('record')
                ->from('HrisRecordsBundle:Record','record')
                ->join('record.organisationunit','organisationunit')
                ->join('record.form','form')
                ->andWhere($queryBuilder->expr()->in('form.id',$formIds))
                ->andWhere('organisationunit.id=:organisationunitId')
                ->andWhere('organisationunit.active=True')
                ->andWhere($whereExpression) // Append in query, field options to exclude
                ->setParameters($aliasParameters) // Set mask value for all parameters
                ->getQuery()->getResult();
            //Preparing array of Combination Categories
            $fieldOption = $this->getDoctrine()->getManager()->getRepository('HrisFormBundle:FieldOption')->findAll();
            foreach ($fieldOption as $key => $optionObject) {
                // Translates to $optionObject->getUid()
                // or $optionObject->getUid() depending on value of $recordKeyName
                $recordFieldOptionKey = ucfirst(Record::getFieldOptionKey());
                $fieldOptionKey = call_user_func_array(array($optionObject, "get${recordFieldOptionKey}"),array());
                $option[$fieldOptionKey] = $optionObject->getValue();
            }
            // Gather Visible Fields from all passed Forms
            if(!empty($this->forms)) {
                $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
                $visibleFieldIds = $queryBuilder->select('DISTINCT(field),formVisibleFields.sort')
                    ->from('HrisFormBundle:FormVisibleFields','formVisibleFields')
                    ->innerJoin('formVisibleFields.field', 'field')
                    ->innerJoin('formVisibleFields.form', 'form')
                    ->where($queryBuilder->expr()->in('form.id',$formIds))
                    ->orderBy('formVisibleFields.sort','ASC')->getQuery()->getResult();
                foreach($visibleFieldIds as $visibleKey=>$visibleFieldId) {
                    $this->visibleFields[] = $this->getDoctrine()->getManager()->createQueryBuilder()
                        ->select('afield')->from('HrisFormBundle:Field','afield')
                        ->where($queryBuilder->expr()
                            ->in('afield.id',$visibleFieldId['id']))
                        ->getQuery()->getSingleResult();
                    $this->getDoctrine()->getManager()->flush();
                }
                if(empty($visibleFieldIds)) {
                    $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
                    $this->visibleFields = $queryBuilder->select('field')
                        ->from('HrisFormBundle:Field','field')
                        ->where('field.isCalculated=False')
                        ->orderBy('field.uid','ASC')->getQuery()->getResult();
                }
            }else {
                /*
                 * Make all fields visible
                */
                $this->visibleFields = $queryBuilder->select('field')->from('HrisFormBundle:Field','field')->orderBy('field.name','ASC')->getQuery()->getResult();
            }
            // Initiate recordsToDisplay array
            $this->recordsToDisplay=NULL;
            $this->recordInstances = NULL;
            foreach($records as $key=>$dataValueInstance) {
                $dataValue = $dataValueInstance->getValue();
                $this->recordInstances[] = $dataValueInstance->getInstance();
                foreach($this->visibleFields as $key=>$visibleField) {
                    // Translates to $field->getUid()
                    // or $visibleField->getUid() depending on value of $recordKeyName
                    $recordFieldKey = ucfirst(Record::getFieldKey());
                    $valueKey = call_user_func_array(array($visibleField, "get${recordFieldKey}"),array());

                    if ($visibleField->getInputType()->getName() == 'Select') {
                        if(isset($option[$dataValue[$valueKey]])) $displayValue = $option[$dataValue[$valueKey]];else $displayValue='&nbsp;';
                    }
                    else if ($visibleField->getInputType()->getName() == 'Date') {
                        if(!empty($dataValue[$valueKey])) {
                            $dataValue[$valueKey] = new \DateTime($dataValue[$valueKey]['date'],new \DateTimeZone ($dataValue[$valueKey]['timezone']));
                            $displayValue = $dataValue[$valueKey];
                            $displayValue = $displayValue->format('d/m/Y');
                        }
                    }else {
                        $displayValue = $dataValue[$valueKey];
                    }
                    $this->recordsToDisplay[$dataValueInstance->getInstance()][$visibleField->getUid()] = $displayValue;
                }
                $this->recordsToDisplay[$dataValueInstance->getInstance()]['form'] = $dataValueInstance->getForm()->getName();
            }
        }
        if(!isset($this->visibleFields)) $this->visibleFields = NULL;
        if(!isset($this->sameLevel)) $this->sameLevel = NULL;
        if(!isset($dataValue)) $dataValue = NULL;
        if(!isset($records)) $records = NULL;
        if(!isset($this->recordsToDisplay)) $this->recordsToDisplay = NULL;
        if(!isset($this->recordInstances)) $this->recordInstances = NULL;
        if(!isset($this->totalCompletenessMatrix)) $this->totalCompletenessMatrix = NULL;
        if(!isset($this->totalExpectedCompleteness)) $this->totalExpectedCompleteness = NULL;
        if(!isset($this->expectedCompleteness)) $this->expectedCompleteness = NULL;
    }



    /**
     * Get all values from specific key in a multidimensional array
     *
     * @param $key string
     * @param $arr array
     * @return null|string|array
     */
    public function array_value_recursive($key, array $arr){
        $val = array();
        array_walk_recursive($arr, function($v, $k) use($key, &$val){if($k == $key) array_push($val, $v);});
        return count($val) > 1 ? $val : array_pop($val);
    }

    /**
     * @var array
     */
    private $visibleFields;

    /**
     * @var string
     */
    private $title;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $organisationunitChildren;

    /**
     * @var Organisationunit
     */
    private $rootNodeOrganisationunit;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $forms;

    /**
     * @var boolean
     */
    private $sameLevel;

    /**
     * @var array
     */
    private $completenessMatrix;

    /**
     * @var array
     */
    private $expectedCompleteness;

    /**
     * @var array
     */
    private $totalCompletenessMatrix;

    /**
     * @var array
     */
    private $totalExpectedCompleteness;

    /**
     * @var array
     */
    private $recordsToDisplay;

    /**
     * @var array
     */
    private $recordInstances;

    /**
     * @var Organisationunit
     */
    private $parent;

    /**
     * @var Organisationunit
     */
    private $organisationunit;

    /**
     * @var OrganisationunitLevel
     */
    private $organisationunitLevel;

}
