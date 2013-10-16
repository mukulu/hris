<?php
/**
 * Created by JetBrains PhpStorm.
 * User: benny
 * Date: 9/24/13
 * Time: 9:41 PM
 * To change this template use File | Settings | File Templates.
 */


namespace Hris\RecordsBundle\Controller;

use Symfony\Component\Form\Tests\Extension\Core\DataTransformer\BooleanToStringTransformerTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\RecordsBundle\Entity\Training;
use Hris\RecordsBundle\Form\ValidationRunType;
use Hris\RecordsBundle\Entity\Record;
use Hris\OrganisationunitBundle\Entity\OrganisationunitLevel;
use Hris\FormBundle\Entity\Field;
use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Doctrine\ORM\EntityManager;
use Hris\DataQualityBundle\Entity\Validation;
use Hris\RecordsBundle\Controller\mathematicalCalculator;

/**
 * Validation controller.
 *
 * @Route("/validation")
 */
class ValidationRunController extends Controller
{
    /**
     * Lists all Validation entities.

     * @Route("/run", name="validation_run")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $validationRunForm = $this->createForm(new ValidationRunType(),null,array('em'=>$this->getDoctrine()->getManager()));

        return array(
            'validationRunForm'=>$validationRunForm->createView(),
        );

    }


    /**
     * Displays the validation results.
     *
     * @Route("/result/",name="validation_result")
     * @Method("POST")
     * @Template("HrisRecordsBundle:ValidationRun:validationResult.html.twig")
     */
    public function validateAction(Request $request)
    {
        $validationRunForm = $this->createForm(new ValidationRunType(),null,array('em'=>$this->getDoctrine()->getManager()));
        if($request->getMethod()=='POST'){

            $validationRunForm->bind($request);
            $validationValues=$validationRunForm->getData();

            //get selected values
            $organisationunit=$validationValues['organisationunit'];
            $forms = $validationValues['forms'];
            $withLowerLevels = $validationValues['withLowerLevels'];
            $selectedValidation=$validationValues['validations'];
            $organisationunitLevel=$validationValues['organisationunitLevel'];

            if(empty($organisationunit) && empty($forms) && empty($validations)) {
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Your changes were saved!'
                );
                return $this->redirect($this->generateUrl('validation_run'));
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
            if(!empty($organisationunitLevel) ) {

                // Display children for the given level is passed, provided level is below the parent organisationunit
                if(!isset($sameLevel)) {
                    $title = "Data Validation Report for All " . $organisationunitLevel->getName() . " Under ". $organisationunit->getLongname(); // Create title
                }else{
                    $title = "Data Validation Report for Employees directly under ". $organisationunit->getLongname(); // Create title
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
                $lowerOrganisationunitCount = $this->getDoctrine()->getManager()->createQuery("SELECT COUNT(lowerOrganisationunit.id)
                                                            FROM HrisOrganisationunitBundle:Organisationunit lowerOrganisationunit
                                                            INNER JOIN lowerOrganisationunit.parent parentOrganisationunit
                                                            WHERE parentOrganisationunit.id=".$organisationunit->getId())->getSingleScalarResult();
                if($organisationunit->getOrganisationunitStructure()->getLevel()->getLevel() !== $lowestOrganisationunitLevel  && !empty($lowerOrganisationunitCount)) {
                    // Deal with organisationunit with children
                    $organisationunitLevel = $this->getDoctrine()->getManager()->getRepository('HrisOrganisationunitBundle:OrganisationunitLevel')->findOneBy(array('level' => ($organisationunit->getOrganisationunitStructure()->getLevel()+1) ));
                    $levelName=$organisationunitLevel->getName() ." Under ";
                }else {
                    $levelName=NULL;
                }
                $organisationunitChildren = $this->getDoctrine()->getRepository('HrisOrganisationunitBundle:Organisationunit')->getImmediateChildren($organisationunit);
                if(!isset($sameLevel)) {
                    $title = "Data Validation Report for ". $levelName . $organisationunit->getLongname(); // Create title
                }else{
                    $title = "Data Validation Report for Employees directly under ". $organisationunit->getLongname(); // Create title
                }

            }

            $rootNodeOrganisationunit = $organisationunit; // Establish the root node
            $parent = NULL;
            //$userObject = $this->getDoctrine()->getManager()->getRepository('User')->findOneBy(array('username' => $user->getUsername()));

            //@check orgUNit
//
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


                   foreach ($organisationunitChildren as $key => $childOrganisationunit) {
                    /*
                     * Construct completeness matrix
                     * @Note: $completenessMatrix[organisationunitId][formId] //Holds particular value
                     * @Note: $totalCompletenessMatrix[formId] // Holds total for all records of the organisationunits
                     * @Note: $childrenNames[organisationunitId] hold names of OrganisationUnits in completeness matrix
                     * @Note: $expectedCompleteness[organisationunitId][formId]
                     */
                    foreach($forms as $key=>$form ) {
                        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
                        if($childOrganisationunit->getOrganisationunitStructure()->getLevel()->getLevel() !== $lowestOrganisationunitLevel ) {
                            // Calculation for levels above the lowest ( Sum for only records below the selectedLevel)
                            $aliasParameters=$maskParameters;
                            $aliasParameters['levelId']=$childOrganisationunit->getId();
                            $hrhisValues = $queryBuilder->select('record')
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
                                ->andWhere($whereExpression); // Append in query, field options to exclude
                            $aliasParameters['organisationunitLevel']=$childOrganisationunit->getOrganisationunitStructure()->getLevel()->getLevel();
                            $aliasParameters['organisationunitId']=$childOrganisationunit->getId();
                            $hrhisValues=$hrhisValues->setParameters($aliasParameters) // Set mask value for all parameters
                                ->getQuery()->getResult();



                            $postData = $request->request->get('hris_recordsbundle_validationtype');
                            $validationUnit=$postData['validations'];


                            $entityManager = $this->getDoctrine()->getManager();
                            //Retrive all validations
                            if ($selectedValidation == "Array") {

                                $getValidation = $entityManager->getRepository('HrisDataQualityBundle:Validation')->findAll();
                            } else {

                                $getValidation = $entityManager->getRepository('HrisDataQualityBundle:Validation')->findBy(array('id' => $validationUnit));
                            }

                            /*
                             * getting all fields for use with the title:
                             */
                            $fieldObjcts = $entityManager->getRepository('HrisFormBundle:Field')->findAll();

                            /*
                             * getting all fields for use with the title:
                              */
                            foreach ($getValidation as $keyValue => $validation) {

                                $getLeftExpression = $validation->getLeftExpression();
                                $getRightExpression = $validation->getRightExpression();


                                foreach ($fieldObjcts as $key => $fieldObj) {

                                    $param = "#{".$fieldObj->getName()."}";                                                                                                           /*

                                                                       /*
                                     * Extracting the first, last name and date of birth Ids
                                     */
                                    if($fieldObj->getName() == 'Firstname'){
                                        $firstNameUid = $fieldObj->getUid();
                                    }
                                    if($fieldObj->getName() == 'Surname'){
                                        $lastNameUid = $fieldObj->getUid();
                                    }
                                    if($fieldObj->getName() == 'Birthdate'){
                                        $birthDateUid = $fieldObj->getUid();
                                    }
                                    if($fieldObj->getName() == 'DateofFirstAppointment'){
                                        $firstAppointmentUid = $fieldObj->getUid();
                                    }
                                    if($fieldObj->getName() == 'DateofConfirmation'){
                                        $confirmationDatetUid = $fieldObj->getUid();
                                    }
                                    if($fieldObj->getName() == 'DateofLastPromotion'){
                                        $lastpromotionDatetUid = $fieldObj->getUid();
                                    }

                                }


                             }

                            /*
                            * Getting Fields with Compulsory Elements
                            */
                            $compulsoryFields = $entityManager->getRepository('HrisFormBundle:Field')->findBy(array('compulsory' => 'TRUE'));

                            if(!empty($compulsoryFields)){
                                foreach ($compulsoryFields as $key => $fieldObj) {
                                    $compulsory[$fieldObj->getId()] = $fieldObj->getName();
                                }
                            }


                            $count = 0;
                            $emptyFields = '';


                            foreach ($hrhisValues as $key => $dataValue) {
                                $count++;
                                $values = $dataValue->getValue();
                                foreach ($getValidation as $keyValue => $validation) {
                                    /*
                                     * getting title of the validation
                                     */

                                    $specificValidationTitle=$validation->getName();

                                    /*
                                     * Getting the expressions;
                                     */
                                    $getLeftExpression = $validation->getLeftExpression();
                                    $getRightExpression = $validation->getRightExpression();

                                    if(is_array($values))
                                        foreach ($values as $field => $value) {

                                            /*
                                             * Setting the Parameters and getting the name of the employee
                                             */
                                            $param = $field;

                                            if($field == $firstNameUid){
                                                $firstname = $value;
                                                                                                                                           }
                                            if($field == $lastNameUid){
                                                $lastname = $value;
                                            }
                                            if($field == $birthDateUid){
                                                if(is_array($value)){
                                                    $bdate = $value['date'];
                                                }
                                            }

                                            /*
                                             * Getting the Compulsory fields whichare empty
                                             */

                                            if(isset($compulsory[$field])){
                                                if($value == '' || $value == NULL || empty($value)){
                                                    $emptyFields[$dataValue->getInstance()][$compulsory[$field]] = $compulsory[$field];

                                                }
                                            }


                                            /*
                                             * getting and replacing the left hand expression column
                                             */

                                             if (strstr($getLeftExpression, $param)){
                                                if (is_array($value)){
                                                    $validationDateFormatLeft = round(((strtotime(date("Y-m-d")) - strtotime($value['date']))/(365*60*60*24)),1);
                                                    $getLeftExpression = str_replace($param, $validationDateFormatLeft, $getLeftExpression);
                                                    $getLeftExpressionValue = new \DateTime($value['date']);
                                                    $getLeftExpressionValue = $getLeftExpressionValue -> format('d/m/Y');

                                                }

                                            }


                                            /*
                                             * getting and replacing the right hand expression column
                                             */


                                            if (strstr($getRightExpression, $param)){
                                                if (is_array($value)){
                                                    $validationDateFormatRight = round(((strtotime(date("Y-m-d")) - strtotime($value['date']))/(365*60*60*24)),1);
                                                    $getRightExpression = str_replace($param, $validationDateFormatRight, $getRightExpression);
                                                    $getRightExpressionValue = new \DateTime($value['date']);
                                                    $getRightExpressionValue = $getRightExpressionValue -> format('d/m/Y');
                                                }
                                            }

                                        }


                                    /*
                                     * Calculating the values of each side
                                     */

                                    $leftHandValue = $this->calculator($getLeftExpression);
                                    $rightHandValue = $this->calculator($getRightExpression);


                                    /*
                                     * Doing comparison of the sides
                                     */
                                    $operator = $validation->getOperator();
                                   if($leftHandValue==$rightHandValue)
                                   {
                                       $validationFault= array ('instance' => $dataValue->getInstance(), 'name' => $firstname.' '.$lastname, 'leftSide' => $getLeftExpressionValue, 'rightSide' => $getRightExpressionValue, 'orgunit' => $dataValue->getOrganisationunit()->getLongname(), 'orgunitId' => $dataValue->getOrganisationunit()->getId(), 'form'=>$dataValue->getForm()->getName() );

                                   }
                                    elseif($leftHandValue!=$rightHandValue)
                                    {
                                        $validationFault= array ('instance' => $dataValue->getInstance(), 'name' => $firstname.' '.$lastname, 'leftSide' => $getLeftExpressionValue, 'rightSide' => $getRightExpressionValue, 'orgunit' => $dataValue->getOrganisationunit()->getLongname(), 'orgunitId' => $dataValue->getOrganisationunit()->getId(), 'form'=>$dataValue->getForm()->getName() );

                                    }
                                   elseif($leftHandValue>$rightHandValue)
                                   {
                                       $validationFault= array ('instance' => $dataValue->getInstance(), 'name' => $firstname.' '.$lastname, 'leftSide' => $getLeftExpressionValue, 'rightSide' => $getRightExpressionValue, 'orgunit' => $dataValue->getOrganisationunit()->getLongname(), 'orgunitId' => $dataValue->getOrganisationunit()->getId(), 'form'=>$dataValue->getForm()->getName() );

                                   }
                                   elseif($leftHandValue<$rightHandValue)
                                   {
                                       $validationFault= array ('instance' => $dataValue->getInstance(), 'name' => $firstname.' '.$lastname, 'leftSide' => $getLeftExpressionValue, 'rightSide' => $getRightExpressionValue, 'orgunit' => $dataValue->getOrganisationunit()->getLongname(), 'orgunitId' => $dataValue->getOrganisationunit()->getId(), 'form'=>$dataValue->getForm()->getName() );

                                   }
                                   elseif($leftHandValue>=$rightHandValue)
                                   {
                                       $validationFault= array ('instance' => $dataValue->getInstance(), 'name' => $firstname.' '.$lastname, 'leftSide' => $getLeftExpressionValue, 'rightSide' => $getRightExpressionValue, 'orgunit' => $dataValue->getOrganisationunit()->getLongname(), 'orgunitId' => $dataValue->getOrganisationunit()->getId(), 'form'=>$dataValue->getForm()->getName() );

                                   }
                                   elseif($leftHandValue<=$rightHandValue)
                                   {
                                       $validationFault= array ('instance' => $dataValue->getInstance(), 'name' => $firstname.' '.$lastname, 'leftSide' => $getLeftExpressionValue, 'rightSide' => $getRightExpressionValue, 'orgunit' => $dataValue->getOrganisationunit()->getLongname(), 'orgunitId' => $dataValue->getOrganisationunit()->getId(), 'form'=>$dataValue->getForm()->getName() );


                                   }
                                    else
                                    {
                                        echo "No operator found";
                                    }
                                    //print_r($validationFault);die();


                                    $getLeftExpressionValue = '';
                                    $getRightExpressionValue = '';

                                      //@@$validationReport = $validationFault[$value->geId()]['instance'];

                                 }

                                /*
                                 * Constructing an array with Person details for Duplicates Validation
                                 */
                                $personInfo[]= array ('instance' => $dataValue->getInstance(), 'name' => $firstname.' '.$lastname, 'dBirth' => $bdate, 'orgunit' => $dataValue->getOrganisationunit()->getLongname(), 'orgunitId' => $dataValue->getOrganisationunit()->getId(), 'form'=>$dataValue->getForm()->getName(), 'formId'=>$dataValue->getForm()->getId() );

                                }

                                /*
                                 * Combining the empty compulsory with names
                                 */
                                $person_name[$dataValue->getInstance()] =  array ('name' => $firstname.' '.$lastname, 'orgunit' => $dataValue->getOrganisationunit()->getLongname(), 'orgunitId' => $dataValue->getOrganisationunit()->getId());



                                $firstname = '';
                                $lastname = '';
                                $dob = '';

                            }

                            /*
         * Sorting Duplicates values from the List of Employees.
         */
                            $foundIds = array();
                        $dupArray = NULL;
                            foreach ( $personInfo as $index => $person )
                            {
                                if ( isset( $foundIds[$person['name']][$person['dBirth']] ) )
                                {
                                    $duplicateRef = $dupArray[$foundIds[$person['name']][$person['dBirth']]];
                                    $duplicate[] = array('ref' => $person, 'dup' => $duplicateRef);
                                }
                                $foundIds[$person['name']][$person['dBirth']] = $index;
                                $dupArray[$index] = $person;

                            }

                        unset($personInfo);
                        }
                    }
                   }
            }


        return array(
            'title'=>$title,
            'dupArray'=>$dupArray,
            'duplicate'=>$duplicate,
            'form'=>$form,
            'emptyFields'=>$emptyFields,
            'person_name'=>$person_name,
            'compulsory'=>$compulsory,
            'specificTitle'=>$specificValidationTitle,
            'validationFault'=>$validationFault,


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

    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */
    private function rectify($exp, $mod = "+") {

        $res = recCalc($exp);
        $this->debug("Pre rectify", $res);
        if ($mod == '-') {
            $res *= - 1;
        }
        $this->debug("Post rectify", $res);
        return $res;
    }

    private function do_error($str) {
        die($str);
        return false;
    }

    private function recCalc($inp) {
        $this->debug("RecCalc input", $inp);

        $p = str_split($inp);
        $level = 0;

        foreach ($p as $num) {
            if ($num == '(' && ++$level == 1) {
                $num = 'BABRAX';
            } elseif ($num == ')' && --$level == 0) {
                $num = 'DEBRAX';
            }
            $res[] = $num;
        }

        if ($level != 0) {
            return $this->do_error('Chyba: špatný počet závorek');
        }

        $res = implode('', $res);

        $res = preg_replace('#([\+\-]?)BABRAX(.+?)DEBRAX#e', "rectify('\\2', '\\1')", $res);

        $this->debug("After parenthesis proccessing", $res);
        preg_match_all('#[+-]?([^+-]+)#', $res, $ar, PREG_PATTERN_ORDER);

        for ($i = 0; $i < count($ar[0]); $i++) {
            $last = substr($ar[0][$i], -1, 1);
            if ($last == '/' || $last == '*' || $last == '^' || $last == 'E') {
                $ar[0][$i] = $ar[0][$i] . $ar[0][$i + 1];
                unset($ar[0][$i + 1]);
            }
        }

        $result = 0;
        foreach ($ar[0] as $num) {
            $result += $this->multi($num);
        }
        $this->debug("RecCalc output", $result);
        return $result;
    }

    private function multi($inp) {
        $this->debug("Multi input", $inp);

        $inp = explode(' ', preg_replace('/([\*\/\^])/', ' \\1 ', $inp));

        foreach ($inp as $va) {
            if ($va != '*' && $va != '/' && $va != '^') {
                $v[] = (float) $va;
            } else {
                $v[] = $va;
            }
        }
        $inp = $v;
        $res = $inp[0];
        for ($i = 1; $i < count($inp); $i++) {

            if ($inp[$i] == '*') {
                $res *= $inp[$i + 1];
            } elseif ($inp[$i] == '/') {
                if ($inp[$i + 1] == 0)
                    $this->do_error('mathematical error');

                $res /= $inp[$i + 1];
            } elseif ($inp[$i] == '^') {
                $res = pow($res, $inp[$i + 1]);
            }
        }
        $this->debug("Multi output", $res);
        return $res;
    }

    private function debug($msg, $var) {
        if (isset($_POST['out']) && $_POST['out'] == '1') {
            echo "\n" . $msg . ": " . $var;
        }
    }

    private function calculator($input){

        $inp = preg_replace(array('/\s+/', '/Pi/', '/e/', '/T/', '/G/', '/M/', '/k/', '/m/', '/u/', '/n/', '/p/', '/f/'),
            array('', M_PI, exp(1), '*' . 1e12, '*' . 1e9, '*' . 1e6, '*' . 1e3, '*' . 1e-3, '*' . 1e-6, '*' . 1e-9, '*' . 1e-12, '*' . 1e-15),
            $input);

        /*
        if (preg_replace('/(^[\*\/\+\^])|[a-dg-z \?<>;:"\'\\|\}\{_]|([\*\/\+\-\^]$)/i', $inp)) {
            $this->do_error('Nalezen neplatný či nesmyslný znak. Překontorlujte si prosím syntax.');
        }
         *
         */

        $result = $this->recCalc($inp);
        return $result;
    }

}




