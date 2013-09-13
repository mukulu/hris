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
                $visibleFields[] = $visibleField->getField();
                if(!in_array($visibleField->getField(),$visibleFields)) $visibleFields[] =$visibleField->getField();
            }
            // Accrue form fields
            foreach($form->getFormFieldMember() as $formFieldKey=>$formField) {
                if(!in_array($formField->getField(),$formFields)) $formFields[] =$formField->getField();
            }
        }
        $title = "Records Report for ".$organisationUnit->getLongname();
        if($withLowerLevels) {
            $title .=" with Lower Levels";
        }
        $title .= " for ".$formNames;
        $individualSearchClause= NULL;
        $visibleFieldsCounter=1;
        if(!empty($visibleFields)) {
            foreach ($visibleFields as $key => $fieldObject) {
                $postVisibleFields[$visibleFieldsCounter] = $fieldObject->getField()->getUid();
                $individualSearchClause .= '{type:"text"},';
                $visibleFieldsCounter++;
            }
        }else {
            foreach ($form->getFormFieldMember() as $key => $fieldObject) {
                $postVisibleFields[$visibleFieldsCounter] = $fieldObject->getField()->getUid();
                $individualSearchClause .= '{type:"text"},';
                $visibleFieldsCounter++;
            }
        }
        // Construct ajax url
        $urlExtension = trim('&visibleFields='
                    .urlencode(serialize(json_encode($postVisibleFields)))
                    .'&forms='.urlencode(json_encode($formIds))
                    .'&organisationunit='.urlencode(json_encode($organisationUnit->getId())));
        if($withLowerLevels) $urlExtension .='&withLowerLevels=True';
        // Create dataTable ajax placeholders
        $dataTableAjaxColumns = "[null,".$individualSearchClause."null,null]";


        return array(
            'visibleFields'=>$visibleFields,
            'formFields'=>$formFields,
            'title'=>$title,
            'urlExtension'=>$urlExtension,
            'dataTableAjaxColumns'=>$dataTableAjaxColumns
        );
    }
}
