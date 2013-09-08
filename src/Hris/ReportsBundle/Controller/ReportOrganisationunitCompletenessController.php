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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
     * @Route("/", name="report_organisationunit_completeness")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

        $organisationunitCompletenessForm = $this->createForm(new ReportOrganisationunitCompletenessType(),null,array('em'=>$this->getDoctrine()->getManager()));

        return array(
            'organisationunitCompletenessForm'=>$organisationunitCompletenessForm->createView(),
        );
    }

    /**
     * Generate Report for Organisationunit Completeness
     *
     * @Route("/", name="report_organisationunit_completeness_generate")
     * @Method("PUT")
     * @Template()
     */
    public function generateAction(Request $request)
    {
        $organisationunitCompletenessForm = $this->createForm(new ReportOrganisationunitCompletenessType(),null,array('em'=>$this->getDoctrine()->getManager()));
        $organisationunitCompletenessForm->bind($request);

        if ($organisationunitCompletenessForm->isValid()) {
            $organisationunitCompletenessFormData = $organisationunitCompletenessForm->getData();
            $organisationunit = $organisationunitCompletenessFormData['organisationunit'];
            $organisationunitLevel = $organisationunitCompletenessFormData['organisationunitLevel'];
            $forms = $organisationunitCompletenessFormData['forms'];
        }

        // Create FormIds
        $formIds = NULL;
        foreach($forms as $formKey=>$formObject) {
            if(empty($formIds)) $formIds=$formObject->getId();else $formIds.=','.$formObject->getId();
        }
        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
        // Determine lowest level in organisationunit structure
        $lowestOrganisationunitLevel = $this->array_value_recursive('maxLevel',$this->getDoctrine()->getManager()->createQuery('SELECT MAX(organisationunitLevel.level) as maxLevel FROM HrisOrganisationunitBundle:OrganisationunitLevel organisationunitLevel')->getResult());
        // Determine organisation unit(childrens) to display
        if(!empty($organisationunitLevel) && $organisationunitLevel->getLevel() > $organisationunit->getOrganisationunitStructure()->getLevel()->getLevel() ) {
            // Display children for the given level is passed, provided level is below the parent organisationunit
            if(!isset($sameLevel)) {
                $title = "Completeness Report for All " . $organisationunitLevel->getName() . " Under ". $organisationunit->getLongname(); // Create title
            }else{
                $title = "Completeness Report for Employees directly under ". $organisationunit->getLongname(); // Create title
            }
            $organisationunitChildren = $queryBuilder->select('organisationunit')
                ->from('HrisOrganisationunitBundle:Organisationunit', 'organisationunit')
                ->innerJoin('organisationunit.organisationunitStructure', 'organisationunitStructure')
                ->where('organisationunitStructure.level=:organisationunitLevel')
                ->andWhere('organisationunitStructure.level'.$organisationunit->getOrganisationunitStructure()->getLevel()->getLevel().'Organisationunit=:levelOrganisationunit')
                ->setParameters(array(
                    'organisationunitLevel'=>$organisationunitLevel,
                    'levelOrganisationunit'=>$organisationunit,
                ))
                ->getQuery()->getResult();
        }else {
            // Display children for lower level of the selected organisationunit
            if($organisationunit->getOrganisationunitStructure()->getLevel()->getLevel() !== $lowestOrganisationunitLevel ) {
                $organisationunitLevel = $this->getDoctrine()->getManager()->getRepository('HrisOrganisationunitBundle:OrganisationunitLevel')->findOneBy(array('level' => ($organisationunit->getOrganisationunitStructure()->getLevel()+1) ));
                $levelName=$organisationunitLevel->getName() ." Under ";
            }else {
                $levelName=NULL;
            }
            $organisationunitChildren = $this->getDoctrine()->getRepository('HrisOrganisationunitBundle:Organisationunit')->getImmediateChildren($organisationunit);
            if(!isset($sameLevel)) {
                $title = "Completeness Report for ". $levelName . $organisationunit->getLongname(); // Create title
            }else{
                $title = "Completeness Report for Employees directly under ". $organisationunit->getLongname(); // Create title
            }

        }
        $rootNodeOrganisationunit = $organisationunit; // Establish the root node
        $parent = NULL;
        //$userObject = $this->getDoctrine()->getManager()->getRepository('User')->findOneBy(array('username' => $user->getUsername()));

        //check to make sure you can not go beyond your assigned level
        //@todo implement checking user organisationunit
//        if ($organisationunit->getOrganisationunitStructure()->getLevel()->getLevel() >= $userObject->getOrganisationunit()->getOrganisationunitStructure()->getLevel()) {
//            $parent = $organisationunit->getParent();
//        }else {
//            $parent = $userObject->getOrganisationunit();
//        }
        $parent = $organisationunit->getParent();
        
        
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
        if (isset($organisationunitChildren)) {

            // user choose district and above show total records in the lower facilities
            $completenessMatrix=NULL;
            $expectedCompleteness=NULL;
            $totalExpectedCompleteness=NULL;
            $totalCompletenessMatrix=NULL;
            foreach ($organisationunitChildren as $key => $childOrganisationunit) {
                /*
                 * Construct completeness matrix
                 * @Note: $completenessMatrix[organisationunitId][formId] //Holds particular value
                 * @Note: $totalCompletenessMatrix[formId] // Holds total for all records of the organisationunits
                 * @Note: $childrenNames[organisationunitId] hold names of OrganisationUnits in completeness matrix
                 * @Note: $formNames[formid] holds names of forms in completeness matrix
                 * @Note: $expectedCompleteness[organisationunitId][formId]
                 */
                foreach($forms as $key=>$form ) {
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
                            ->andWhere('
					            			( 
						            			(
					            					organisationunitStructure.level >= :organisationunitLevel
						            				AND organisationunitStructure.level'.$childOrganisationunit->getParent()->getOrganisationunitStructure()->getLevel()->getLevel().'Organisationunit=:levelId
						            			)
					            				OR organisationunit.id=:organisationunitId
					            			)'
                            )
                            ->andWhere($queryBuilder->expr()->in('form.id',$form->getId()))
                            ->andWhere($whereExpression); // Append in query, field options to exclude
                        $aliasParameters['organisationunitLevel']=$childOrganisationunit->getOrganisationunitStructure()->getLevel();
                        $aliasParameters['organisationunitId']=$childOrganisationunit->getId();
                        $valuecount=$valuecount->setParameters($aliasParameters) // Set mask value for all parameters
                            ->getQuery()->getResult();

                        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
                        $expectations = $queryBuilder->select('SUM(organisationunitCompleteness.expectation) as expectation')
                            ->from('HrisOrganisationunitBundle:Organisationunit','organisationunit')
                            ->join('organisationunit.organisationunitCompleteness','organisationunitCompleteness')
                            ->join('organisationunitCompleteness.form','form')
                            ->join('organisationunit.organisationunitStructure','organisationunitStructure')
                            ->andWhere('(
					            			( organisationunitStructure.level >= :organisationunitLevel AND organisationunitStructure.level'.$childOrganisationunit->getParent()->getOrganisationunitStructure()->getLevel()->getLevel().'Organisationunit=:levelId )
					            			OR organisationunit.id=:organisationunitid
					            	    )'
                            )
                            ->andWhere($queryBuilder->expr()->in('form.id',$form->getId()))
                            ->setParameters(array(
                                'levelId'=>$childOrganisationunit->getId(),
                                'organisationunitLevel'=>$childOrganisationunit->getOrganisationunitStructure()->getLevel(),
                                'organisationunitid'=>$childOrganisationunit->getId(),
                            ))
                            ->getQuery()->getResult();
                        $completenessMatrix[$childOrganisationunit->getId()][$form->getId()] = $this->array_value_recursive('employeeCount',$valuecount);
                        $expectedCompleteness[$childOrganisationunit->getId()][$form->getId()] = $this->array_value_recursive('expectation',$expectations);
                        if(isset($totalCompletenessMatrix[$form->getId()])) {
                            $totalCompletenessMatrix[$form->getId()] += $completenessMatrix[$childOrganisationunit->getId()][$form->getId()];
                        }else {
                            $totalCompletenessMatrix[$form->getId()] = $completenessMatrix[$childOrganisationunit->getId()][$form->getId()];
                        }
                        if(isset($totalExpectedCompleteness[$form->getId()])) {
                            $totalExpectedCompleteness[$form->getId()] += $expectedCompleteness[$childOrganisationunit->getId()][$form->getId()];
                        }else {
                            $totalExpectedCompleteness[$form->getId()] = $expectedCompleteness[$childOrganisationunit->getId()][$form->getId()];
                        }
                        $childrenNames[$childOrganisationunit->getId()]= $childOrganisationunit->getLongname();
                        $formNames[$form->getId()] = $form->getName();
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
                            ->setParameters(array(
                                'organisationunitId'=>$childOrganisationunit->getId()
                            ))
                            ->getQuery()->getResult();
                        $completenessMatrix[$childOrganisationunit->getId()][$form->getId()] = $this->array_value_recursive('employeeCount',$valuecount);
                        $expectedCompleteness[$childOrganisationunit->getId()][$form->getId()] = $this->array_value_recursive('expectation',$expectations);
                        if(isset($totalCompletenessMatrix[$form->getId()])) {
                            $totalCompletenessMatrix[$form->getId()] += $completenessMatrix[$childOrganisationunit->getId()][$form->getId()];
                        }else {
                            $totalCompletenessMatrix[$form->getId()] = $completenessMatrix[$childOrganisationunit->getId()][$form->getId()];
                        }
                        if(isset($totalExpectedCompleteness[$form->getId()])) {
                            $totalExpectedCompleteness[$form->getId()] += $expectedCompleteness[$childOrganisationunit->getId()][$form->getId()];
                        }else {
                            $totalExpectedCompleteness[$form->getId()] = $expectedCompleteness[$childOrganisationunit->getId()][$form->getId()];
                        }
                        $childrenNames[$childOrganisationunit->getId()]= $childOrganisationunit->getLongname();
                        $formNames[$form->getId()] = $form->getName();
                    }
                }
            }
            // Completeness for the root node organisationunit
            foreach($forms as $key=>$form ) {
                $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
                $aliasParameters=$maskParameters;
                $aliasParameters['organisationunitId']=$rootNodeOrganisationunit->getId();
                $valuecount = $queryBuilder->select('COUNT(record.instance) as employeeCount ')
                    ->from('HrisRecordsBundle:Record','record')
                    ->join('record.organisationunit','organisationunit')
                    ->join('record.form','form')
                    ->andWhere($queryBuilder->expr()->in('form.id',$form->getId()))
                    ->andWhere('organisationunit.id=:organisationunitId')
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
                    ->setParameters(array('organisationunitId'=>$rootNodeOrganisationunit->getId()))
                    ->getQuery()->getResult();
                $completenessMatrix[$rootNodeOrganisationunit->getId()][$form->getId()] = $this->array_value_recursive('employeeCount',$valuecount);
                $expectedCompleteness[$rootNodeOrganisationunit->getId()][$form->getId()] = $this->array_value_recursive('expectation',$expectations);
                // Summation of total completeness for selected forms
                if(isset($totalCompletenessMatrix[$form->getId()])) {
                    $totalCompletenessMatrix[$form->getId()] += $completenessMatrix[$rootNodeOrganisationunit->getId()][$form->getId()];
                }else {
                    $totalCompletenessMatrix[$form->getId()] = $completenessMatrix[$rootNodeOrganisationunit->getId()][$form->getId()];
                }
                // Summation of total expected completeness for sselected forms
                if(isset($totalExpectedCompleteness[$form->getId()])) {
                    $totalExpectedCompleteness[$form->getId()] += $expectedCompleteness[$rootNodeOrganisationunit->getId()][$form->getId()];
                }else {
                    $totalExpectedCompleteness[$form->getId()] = $expectedCompleteness[$rootNodeOrganisationunit->getId()][$form->getId()];
                }

                $childrenNames[$rootNodeOrganisationunit->getId()]= $rootNodeOrganisationunit->getLongname();
                $formNames[$form->getId()] = $form->getName();
            }
            // Account for displaying of individual records
            if(empty($organisationunitChildren) || isset($sameLevel)) {
                // When organisationunit has no children display  records(Only root node found)

                $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
                // Gather Visible Fields from all passed Forms
                if(!empty($forms)) {
                    $visibleFieldIds = $queryBuilder->select('DISTINCT(field),formVisibleFields.sort')
                        ->from('HrisFormBundle:FormVisibleFields','formVisibleFields')
                        ->innerJoin('formVisibleFields.field', 'field')
                        ->innerJoin('formVisibleFields.form', 'form')
                        ->where($queryBuilder->expr()->in('form.id',$formIds))
                        ->orderBy('formVisibleFields.sort','ASC')->getQuery()->getResult();
                    foreach($visibleFieldIds as $visibleKey=>$visibleFieldId) {
                        $visibleFields[] = $this->getDoctrine()->getManager()->createQueryBuilder()->select('afield')->from('HrisFormBundle:Field','afield')->where($queryBuilder->expr()->in('afield.id',$visibleFieldId['id']))->getQuery()->getSingleResult();
                        $this->getDoctrine()->getManager()->flush();
                    }
                }else {
                    /*
                     * Make all fields visible
                    */
                    $visibleFields = $queryBuilder->select('field')->from('HrisFormBundle:Field','field')->orderBy('field.name','ASC')->getQuery()->getResult();
                }
                //Preparing array of Combination Categories
                $categoryOption = $this->getDoctrine()->getManager()->getRepository('HrisFormBundle:FieldOption')->findAll();

                $counter=0;
                // For Organisation Units without childrens
                $aliasParameters=$maskParameters;
                $aliasParameters['organisationunitId']=$organisationunit->getId();
                $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
                $records = $queryBuilder->select('record')->from('HrisRecordsBundle:Record','record')->join('record.organisationunit','organisationunit')->join('record.form','form')
                    ->andWhere($queryBuilder->expr()->in('form.id',$formIds))->andWhere('organisationunit.id=:organisationunitId')
                    ->andWhere($whereExpression) // Append in query, field options to exclude
                    ->setParameters($aliasParameters) // Set mask value for all parameters
                    ->getQuery()->getResult();
                foreach ($categoryOption as $key => $optionObject) {
                    //@todo implement dynamic record value name
                    // Translates to $optionObject->getUid()
                    // or $optionObject->getUid() depending on value of $recordKeyName
                    $recordFieldOptionKey = ucfirst(Record::getFieldOptionKey());
                    $fieldOptionKey = call_user_func_array(array($optionObject, "get${recordFieldOptionKey}"),array());
                    $option[$fieldOptionKey] = $optionObject->getValue();
                }
                foreach($records as $key=>$dataValueInstance) {
                    echo '<tr height="20">';
                    echo '	<td id="'.$dataValueInstance->getInstance().'">'.++$counter.'</td>';
                    foreach($visibleFields as $key=>$visibleField) {
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

                $option=NULL;
                $completenessMatrix=NULL;
            }
        }
        if(!isset($visibleFields)) $visibleFields = NULL;
        if(!isset($sameLevel)) $sameLevel = NULL;
        if(!isset($dataValue)) $dataValue = NULL;
        if(!isset($records)) $records = NULL;
        
        return array(
            'title' => $title,
            'organisationunitChildren'=>$organisationunitChildren,
            'rootNodeOrganisationunit'=>$rootNodeOrganisationunit,
            'visibleFields'=>$visibleFields,
            'forms'=>$forms,
            'sameLevel'=>$sameLevel,
            'formNames'=>$formNames,
            'completenessMatrix'=>$completenessMatrix,
            'expectedCompleteness'=>$expectedCompleteness,
            'totalCompletenessMatrix'=>$totalCompletenessMatrix,
            'totalExpectedCompleteness'=>$totalExpectedCompleteness,
            'records'=>$records,
            'dataValue'=>$dataValue,
            'parent'=>$parent,
        );
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

}
