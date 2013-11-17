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
namespace Hris\ImportExportBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Tests\Common\Annotations\Null;
use Doctrine\Tests\Common\Annotations\True;
use Hris\ImportExportBundle\Form\ImportType;
use Hris\RecordsBundle\Entity\History;
use Hris\RecordsBundle\Entity\Record;
use Hris\RecordsBundle\Entity\Training;
use Sonata\AdminBundle\Tests\Admin\FieldDescription;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\ImportExportBundle\Entity\Export;
use Hris\ImportExportBundle\Form\ExportType;
use Symfony\Component\HttpFoundation\Response;
use Hris\FormBundle\Entity\Field;
use Hris\FormBundle\Entity\FieldOption;
use Hris\FormBundle\Entity\DataType;
use Hris\FormBundle\Entity\InputType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Hris\OrganisationunitBundle\Entity\Organisationunit;
use ZipArchive;

/**
 * Import controller.
 *
 * @Route("/importexport/import")
 */
class ImportController extends Controller
{

    /**
     * Lists all Import entities.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_IMPORT_LIST")
     * @Route("/", name="importexport_import")
     * @Route("/list", name="importexport_import_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $importForm = $this->createForm(new ImportType(), null);

        return array(
            'importForm' => $importForm->createView(),
        );
    }

    /**
     * Creates a new Import entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_IMPORT_CREATE")
     * @Route("/{_format}", requirements={"_format"="json|"}, defaults={"_format"="json"}, name="importexport_import_create")
     * @Method("POST")
     * @Template("HrisImportExportBundle:Import:export.json.twig")
     */
    public function createAction(Request $request)
    {

        $importForm = $this->createForm(new ImportType(), null);
        $importForm->bind($request);

        if ($importForm->isValid()) {
            $importFormData = $importForm->getData();
            $file = $importFormData['file'];
        }

        $filename = NULL;
        $doctype = NULL;


        $file->move('../app/cache', 'export.zip');

        $filename = '../app/cache/export.zip';

        $zip = new ZipArchive();


        if (!empty($filename)) {

            if (true === $zip->open($filename)) {

                $zipFile = zip_open($filename);

                while ($entry = zip_read($zipFile)) {
                    zip_entry_open($zipFile, $entry);
                    $xmlFileName = zip_entry_name($entry);
                    $data = $fileStrem[$xmlFileName] = $entry;

                }

                /*
                 * Creating the variable to Check if this is legacy data
                 */

                if (array_key_exists('header.txt', $fileStrem)) {


                    /*
                     * Creating the variable to hold Fields from import file
                     */

                    if (array_key_exists('field.json', $fileStrem)) {
                        $entryValue = $fileStrem['field.json'];
                        $fields = zip_entry_read($entryValue, zip_entry_filesize($entryValue));

                        $this->LegacyUpdateFieldsAction($fields);

                    }

                    /*
                     * Creating the variable to hold Field Options from import file
                     */

                    if (array_key_exists('fieldOption.json', $fileStrem)) {
                        $entryValue = $fileStrem['fieldOption.json'];
                        $fieldOptions = zip_entry_read($entryValue, zip_entry_filesize($entryValue));

                        $this->LegacyUpdateFieldOptionsAction($fieldOptions);

                    }

                    /*
                     * Creating the variable to hold Field Options Association from import file
                     */

                    if (array_key_exists('fieldOptionAssociation.json', $fileStrem)) {
                        $entryValue = $fileStrem['fieldOptionAssociation.json'];
                        $fieldOptionsAssoc = zip_entry_read($entryValue, zip_entry_filesize($entryValue));

                        $this->LegacyUpdateFieldOptionsAssociationAction($fieldOptionsAssoc);

                    }

                    /*
                     * Creating the variable to hold Organisation units from import file
                     */

                    if (array_key_exists('organizationUnit.json', $fileStrem)) {
                        $entryValue = $fileStrem['organizationUnit.json'];
                        $organisationUnits = zip_entry_read($entryValue, zip_entry_filesize($entryValue));

                        $this->LegacyUpdateOrganisationUnitsAction($organisationUnits);

                    }

                    /*
                     * Creating the variable to hold Organisation units from import file
                     */

                    if (array_key_exists('orgUnitGroupMember.json', $fileStrem)) {
                        $entryValue = $fileStrem['orgUnitGroupMember.json'];
                        $organisationUnitGroup = zip_entry_read($entryValue, zip_entry_filesize($entryValue));

                        $this->LegacyUpdateOrganisationUnitsGroupsAction($organisationUnitGroup);


                    }


                    /*
                     * Creating the variable to hold Records from import file
                     */

                    if (array_key_exists('values.json', $fileStrem)) {
                        $entryValue = $fileStrem['values.json'];
                        $records = zip_entry_read($entryValue, zip_entry_filesize($entryValue));

                        $this->legacyUpdateRecordsAction($records);

                    }

                    /*
                     * Creating the variable to hold History from import file
                     */

                    if (array_key_exists('history.json', $fileStrem)) {
                        $entryValue = $fileStrem['history.json'];
                        $history = zip_entry_read($entryValue, zip_entry_filesize($entryValue));

                        $this->LegacyUpdateRecordsHistoryAction($history);

                    }

                    /*
                     * Creating the variable to hold Training from import file
                     */

                    if (array_key_exists('training.json', $fileStrem)) {
                        $entryValue = $fileStrem['training.json'];
                        $training = zip_entry_read($entryValue, zip_entry_filesize($entryValue));

                        $this->LegacyUpdateRecordsTrainingAction($training);

                    }

                }

                /*
                 * Creating the variable to hold Fields from import file
                 */

                if (array_key_exists('fields.json', $fileStrem)) {
                    $entryValue = $fileStrem['fields.json'];
                    $fields = zip_entry_read($entryValue, zip_entry_filesize($entryValue));

                }

                /*
                 * Creating the variable to hold Field Options from import file
                 */

                if (array_key_exists('fieldOptions.json', $fileStrem)) {
                    $entryValue = $fileStrem['fieldOptions.json'];
                    $fieldOptions = zip_entry_read($entryValue, zip_entry_filesize($entryValue));
                }

                /*
                 * Creating the variable to hold Organisation units from import file
                 */

                if (array_key_exists('organizationUnit.json', $fileStrem)) {
                    $entryValue = $fileStrem['organizationUnit.json'];
                    $organisationUnits = zip_entry_read($entryValue, zip_entry_filesize($entryValue));
                }

                /*
                 * Creating the variable to hold Records from import file
                 */

                if (array_key_exists('records.json', $fileStrem)) {
                    $entryValue = $fileStrem['records.json'];
                    $records = zip_entry_read($entryValue, zip_entry_filesize($entryValue));
                }

            }
        } else {
            var_dump("Nothing works");
            die();
        }

        return array(
            'records' => $records,
            'organisationUnit' => $organisationUnits,
            'fieldOptions' => $fieldOptions,
            'fields' => $fields,
        );
    }

    /**
     * Displays a form to create a new Import entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_IMPORT_CREATE")
     * @Route("/new", name="importexport_import_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $form = $this->createForm(new ImportType(), null);

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Import entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_IMPORT_SHOW")
     * @Route("/{id}", requirements={"id"="\d+"}, name="importexport_import_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisImportExportBundle:Export')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Import entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to delete a Import entity by id.
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

    /**
     * Displays a form to edit an existing Import entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_IMPORT_UPDATE")
     * @Route("/{id}/edit", name="importexport_import_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Import entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_IMPORT_UPDATE")
     * @Route("/{id}", name="importexport_import_update")
     * @Method("PUT")
     * @Template("HrisImportExportBundle:Export:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ImportType(), null);
        $editForm->bind($request);

        if ($editForm->isValid()) {

            return $this->redirect($this->generateUrl('importexport_import'));
        }

        return array(
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Import entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_IMPORT_DELETE")
     * @Route("/{id}", name="importexport_import_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        return $this->redirect($this->generateUrl('importexport_import'));
    }

    /**
     * Importing fields.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_IMPORT_IMPORTFIELDS")
     * @Route("/importFields", name="importexport_import_importFields")
     * @Method("POST")
     */

    public function updateFieldsAction(Request $request)
    {
        global $refField;

        $em = $this->getDoctrine()->getManager();

        $fields = json_decode($this->get('request')->request->get('fields'), True);

        foreach ($fields as $key => $field) {

            //getting the Object if Exist from the Database
            $fieldObject = $em->getRepository('HrisFormBundle:Field')->findOneby(array('name' => $field[0]['name']));

            if (!empty($fieldObject)) {

                $refField[$field[0]['uid']] = $fieldObject->getUid();

                print $field[0]['uid'];
                print '<br>';

            } else {

                $dataType = $em->getRepository('HrisFormBundle:DataType')->findOneby(array('uid' => $field['datatype_uid']));
                $inputType = $em->getRepository('HrisFormBundle:InputType')->findOneby(array('uid' => $field['inputtype_uid']));

                $fieldObject = new Field();
                $fieldObject->setUid($field[0]['uid']);
                $fieldObject->setName($field[0]['name']);
                $fieldObject->setCaption($field[0]['caption']);
                $fieldObject->setCompulsory($field[0]['compulsory']);
                $fieldObject->setIsUnique($field[0]['isUnique']);
                $fieldObject->setDescription($field[0]['description']);
                $fieldObject->setHashistory($field[0]['hashistory']);
                $fieldObject->setHastarget($field[0]['hastarget']);
                $fieldObject->setDataType($dataType);
                $fieldObject->setInputType($inputType);
                $fieldObject->setIsCalculated($field[0]['isCalculated']);
               // $fieldObject->setDatecreated($field[0]['datecreated']);
                $em->persist($fieldObject);

                $refField[$field['uid']] = $field[0]['uid'];

            }
        }
        $em->flush();

        return new Response('success');

    }

    /**
     * Importing fields.
     */

    public function LegacyUpdateFieldsAction($fields)
    {
        global $refField;

        $em = $this->getDoctrine()->getManager();

        $fields = json_decode($fields, True);

        foreach ($fields as $key => $field) {

            //getting the Object if Exist from the Database
            $fieldObject = $em->getRepository('HrisFormBundle:Field')->findOneby(array('name' => $field['name']));

            if (!empty($fieldObject)) {

                $refField[$field['id']] = $fieldObject->getUid();

                //print $field['id'];
                //print '<br>';

            } else {

                switch($field['dataType']['name']){
                    case 'String': $dataType = 'String';
                        break;
                    case 'integer': $dataType = 'Integer';
                        break;
                    case 'double': $dataType = 'Double';
                        break;
                    case 'date': $dataType = 'Date';
                        break;
                    default: $dataType = $field['dataType']['name'];
                        break;
                }

                switch($field['inputType']['name']){
                    case 'combo': $inputType = 'Select';
                        break;
                    case 'text': $inputType = 'Text';
                        break;
                    case 'date': $inputType = 'Date';
                        break;
                    case 'textArea': $inputType = 'TextArea';
                        break;
                    default: $inputType = $field['inputType']['name'];
                        break;
                }

                $dataTypeObj = $em->getRepository('HrisFormBundle:DataType')->findOneby(array('name' => $dataType));
                $inputTypeObj = $em->getRepository('HrisFormBundle:InputType')->findOneby(array('name' => $inputType));

                $fieldObject = new Field();
                $fieldObject->setUid(uniqid());
                $fieldObject->setName($field['name']);
                $fieldObject->setCaption($field['caption']);
                $fieldObject->setCompulsory($field['compulsory']);
                $fieldObject->setDescription($field['description']);
                $fieldObject->setDataType($dataTypeObj);
                $fieldObject->setInputType($inputTypeObj);
                // $fieldObject->setDatecreated($field[0]['datecreated']);
                $em->persist($fieldObject);

                $refField[$field['id']] = $fieldObject->getUid();

            }
        }
        $em->flush();

        return new Response('success');

    }

    /**
     * Importing fields Options.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_IMPORT_IMPORTFIELDOPTIONS")
     * @Route("/importFieldOptions", name="importexport_import_importFieldOptions")
     * @Method("POST")
     */

    public function updateFieldOptionsAction(Request $request)
    {
        global $refFieldOptions;

        $em = $this->getDoctrine()->getManager();

        $fieldOptions = json_decode($this->get('request')->request->get('fieldOptions'), True);

        foreach ($fieldOptions as $key => $fieldOption) {

            //getting the Object if Exist from the Database
            $field = $em->getRepository('HrisFormBundle:Field')->findOneby(array('name' => $fieldOption['field_name']));
            $fieldOptionObject = $em->getRepository('HrisFormBundle:FieldOption')->findOneby(array('value' => $fieldOption[0]['value'], 'field' => $field ));

            if (!empty($fieldOptionObject)) {

                $refFieldOptions[$fieldOption[0]['uid']] = $fieldOptionObject->getUid();

                //print $fieldOption[0]['uid'];
                //print '<br>';

            } else {

                $fieldOptionObject = new FieldOption();
                $fieldOptionObject->setUid($fieldOption[0]['uid']);
                $fieldOptionObject->setField($field);
                $fieldOptionObject->setValue($fieldOption[0]['value']);
                $fieldOptionObject->setDescription($fieldOption[0]['description']);
                $fieldOptionObject->setSort($fieldOption[0]['sort']);
                $fieldOptionObject->setSkipInReport($fieldOption[0]['skipInReport']);
                //$fieldOptionObject->setDatecreated($fieldOption[0]['datecreated']);
                $em->persist($fieldOptionObject);

                $refFieldOptions[$fieldOption[0]['uid']] = $fieldOption[0]['uid'];

            }
        }
        $em->flush();

        return new Response('success');

    }

    /**
     * Importing Legacy fields Options.
     */

    public function LegacyUpdateFieldOptionsAction($fieldOptions)
    {
        global $refFieldOptions;

        $em = $this->getDoctrine()->getManager();

        $fieldOptions = json_decode($fieldOptions, True);

        foreach ($fieldOptions as $key => $fieldOption) {

            //getting the Object if Exist from the Database
            $field = $em->getRepository('HrisFormBundle:Field')->findOneby(array('name' => $fieldOption['field_name']));
            $fieldOptionObject = $em->getRepository('HrisFormBundle:FieldOption')->findOneby(array('value' => $fieldOption[0]['value'], 'field' => $field ));

            if (!empty($fieldOptionObject)) {

                $refFieldOptions[$fieldOption[0]['id']] = $fieldOptionObject->getUid();

            } else {

                $fieldOptionObject = new FieldOption();
                $fieldOptionObject->setUid(uniqid());
                $fieldOptionObject->setField($field);
                $fieldOptionObject->setValue($fieldOption[0]['value']);
                //$fieldOptionObject->setDatecreated($fieldOption[0]['datecreated']);
                $em->persist($fieldOptionObject);

                $refFieldOptions[$fieldOption[0]['id']] = $fieldOptionObject->getUid();

            }
        }
        $em->flush();

        return new Response('success');

    }

    /**
     * Importing Legacy fields Options.
     */

    public function LegacyUpdateFieldOptionsAssociationAction($fieldOptionsAssoc)
    {
        global $refFieldOptions;

        $em = $this->getDoctrine()->getManager();

        $fieldOptionAssoc = json_decode($fieldOptionsAssoc, True);

        foreach ($fieldOptionAssoc as $key => $fieldOption) {

            //getting the Object if Exist from the Database
            $fieldOptionParent = $em->getRepository('HrisFormBundle:FieldOption')->findOneby(array('uid' => $refFieldOptions[$fieldOption['parent']]));
            $fieldOptionChild = $em->getRepository('HrisFormBundle:FieldOption')->findOneby(array('uid' => $refFieldOptions[$fieldOption['child']]));

            $optionRef = $fieldOptionParent->getChildFieldOption();

            if ($optionRef->contains($fieldOptionChild)) {

                continue;

            } else {

                $fieldOptionParent->addChildFieldOption($fieldOptionChild);
                $em->persist($fieldOptionParent);

            }
        }
        $em->flush();

        return new Response('success');

    }

    /**
     * Importing Organisation Units.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_IMPORT_ORGANISATIONUNITS")
     * @Route("/importOrganisationUnits", name="importexport_import_importOrganisationUnits")
     * @Method("POST")
     */

    public function updateOrganisationUnitsAction(Request $request)
    {
        global $refOrganisationUnit;

        $em = $this->getDoctrine()->getManager();

        $organisationUnits = json_decode($this->get('request')->request->get('organisationUnits'), True);

        foreach ($organisationUnits as $key => $organisationUnit) {

            //getting the Object if Exist from the Database

            $parent = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->findOneby(array('shortname' => $organisationUnit['shortname']));
            $orgunitObject = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->findOneby(array('shortname' => $organisationUnit[0]['shortname'], 'parent' => $parent));

            if (!empty($orgunitObject)) {

                $refOrganisationUnit[$organisationUnit[0]['uid']] = $orgunitObject->getUid();

                print $organisationUnit[0]['uid'];
                print '<br>';

            } else {

                $orgunitObject = new Organisationunit();
                $orgunitObject->setUid($organisationUnit[0]['uid']);
                $orgunitObject->setShortname($organisationUnit[0]['shortname']);
                $orgunitObject->setLongname($organisationUnit[0]['longname']);
                $orgunitObject->setParent($organisationUnit[0]['sort']);
                $orgunitObject->setCode($organisationUnit[0]['code']);
                $orgunitObject->setDescription($organisationUnit[0]['description']);
                //$orgunitObject->setDatecreated($organisationUnit[0]['datecreated']);

                $em->persist($orgunitObject);

                $refOrganisationUnit[$organisationUnit[0]['uid']] = $organisationUnit[0]['uid'];

            }
        }
        $em->flush();

        return new Response('success');

    }

    /**
     * Importing Legacy Organisation Units.
     *
     */

    public function LegacyUpdateOrganisationUnitsAction($organisationUnits)
    {
        global $refOrganisationUnit;

        $em = $this->getDoctrine()->getManager();

        $organisationUnits = json_decode($organisationUnits, true);

        foreach ($organisationUnits as $key => $organisationUnit) {

            $parent = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->findOneby(array('longname' => $organisationUnit['longname']));
            //print $organisationUnit[0]['longname']." Parent: ".$organisationUnit['longname'].'<br>';

            $orgunitObjectCheck = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->findOneby(array('longname' => $organisationUnit[0]['longname'], 'parent' => $parent));

            if ($orgunitObjectCheck != NULL) {

                $refOrganisationUnit[$organisationUnit[0]['id']] = $orgunitObjectCheck->getUid();

                //print 'this record Exists '.$organisationUnit[0]['longname']." Parent: ".$organisationUnit['longname'].'<br>';

            } else {
                $orgunitObject = new Organisationunit();
                $orgunitObject->setUid(uniqid());
                if ($parent != NULL){
                    $orgunitObject->setParent($parent);
                }else{
                    $parent = NULL;
                    $orgunitObject->setParent($parent);
                }
                $orgunitObject->setShortname($organisationUnit[0]['shortname']);
                $orgunitObject->setLongname($organisationUnit[0]['longname']);
                $orgunitObject->setCode($organisationUnit[0]['code']);

                $em->persist($orgunitObject);


                $refOrganisationUnit[$organisationUnit[0]['id']] = $orgunitObject->getUid();

            }
        }
        $em->flush();

        return new Response('success');

    }

    /**
     * Importing Legacy Organisation Units Groups.
     *
     */

    public function LegacyUpdateOrganisationUnitsGroupsAction($organisationUnitGroup)
    {
        global $refOrganisationUnit;

        $em = $this->getDoctrine()->getManager();

        $organisationUnitGroups = json_decode($organisationUnitGroup, true);

        foreach ($organisationUnitGroups as $key => $organisationUnit) {

            foreach($organisationUnit as $group => $facility){

                $orgUnitObj = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->findOneby(array('longname' => $facility));

                $orgUnitGroupObj = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroup')->findOneby(array('name' => $group));

                $orgunitObjectCheck = $orgUnitGroupObj->getOrganisationunit();

                if ($orgunitObjectCheck->contains($orgUnitObj) || empty($orgUnitObj)) {

                    continue;

                } else {
                    $orgUnitGroupObj->addOrganisationunit($orgUnitObj);

                    $em->persist($orgUnitGroupObj);
                }
            }
        }
        $em->flush();

        return new Response('success');

    }

    /**
     * Importing Records.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_IMPORT_IMPORTRECORDS")
     * @Route("/importRecords", name="importexport_import_importRecords")
     * @Method("POST")
     */

    public function updateRecordsAction(Request $request)
    {
        global $refOrganisationUnit, $refFieldOptions, $refField;

        $em = $this->getDoctrine()->getManager();

        $records = json_decode($this->get('request')->request->get('records'), True);

        foreach ($records as $key => $record) {

            //getting the Object if Exist from the Database

            $form = $em->getRepository('HrisFormBundle:Form')->find($record['form_id']);
            $orgunit = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->findOneby(array('uid' => $record['orgunit_uid']));

            $recordObject = $em->getRepository('HrisRecordsBundle:Record')->findOneby(array('instance' => $record[0]['instance']));

            if (empty($recordObject)) {

                $recordObject = new Record();
                $recordObject->setForm($form);
                $recordObject->setOrganisationunit($orgunit);
                $recordObject->setInstance($record[0]['instance']);
                $recordObject->setUid($record[0]['uid']);
                $recordObject->setValue($record[0]['value']);
                $em->persist($recordObject);

            }
        }

        $em->flush();

        return new Response('success');

    }

    /**
     * Importing Legacy Records.
     *
     */

    public function legacyUpdateRecordsAction($records)
    {
        global $refOrganisationUnit, $refFieldOptions, $refField;

        $em = $this->getDoctrine()->getManager();

        $records = json_decode($records, True);

        foreach ($records as $keys => $record) {

            $value = $record[0]['value'];

            foreach($value as $key => $valueInstance){
                //checking if the Key Exists and substitute with the DB reference Key
                if(isset($refField[$key])){
                    unset ($value[$key]);

                    //checking if the field is Select field for reference checking
                    $field = $em->getRepository('HrisFormBundle:Field')->findOneby(array('uid' => $refField[$key]));

                    if($field->getInputType()->getName() == 'Select'){
                        if(isset($refFieldOptions[$valueInstance])){
                            $valueInstance = $refFieldOptions[$valueInstance];
                        }
                    }

                    $value[$refField[$key]] = $valueInstance;
                }
            }

            //getting the Object if Exist from the Database

            $form = $em->getRepository('HrisFormBundle:Form')->findOneby(array('name' => $record['form_name']));
            $orgunit = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->findOneby(array('longname' => $record['orgunit_name']));

            $recordObject = $em->getRepository('HrisRecordsBundle:Record')->findOneby(array('instance' => $record[0]['instance']));

            $createdDate = new \DateTime($record[0]['inputDate']['date']);

            if (empty($recordObject)) {

                $recordObject = new Record();
                $recordObject->setForm($form);
                $recordObject->setOrganisationunit($orgunit);
                $recordObject->setInstance($record[0]['instance']);
                $recordObject->setUid(uniqid());
                $recordObject->setValue($value);
                $recordObject->setDatecreated($createdDate);
                $recordObject->setUsername($record[0]['username']);

                $em->persist($recordObject);

            }
        }

        $em->flush();

        return new Response('success');

    }

    /**
     * Importing Legacy Record History.
     */

    public function LegacyUpdateRecordsHistoryAction($history)
    {

        $em = $this->getDoctrine()->getManager();

        $recordHistories = json_decode($history, True);

        foreach ($recordHistories as $key => $historyValue) {

            //getting the Object if Exist from the Database
            $field = $em->getRepository('HrisFormBundle:Field')->findOneby(array('name' => $historyValue['field_name']));
            $recordObject = $em->getRepository('HrisRecordsBundle:Record')->findOneby(array('instance' => $historyValue[0]['instance']));

            if (!empty($recordObject)){

                $startDate = new \DateTime($historyValue[0]['startdate']['date']);
                $historyObjectInstance = $em->getRepository('HrisRecordsBundle:History')->findOneby(array('record' => $recordObject, 'history' => $historyValue[0]['history'], 'startdate' => $startDate));

                if (empty($historyObjectInstance)) {

                    $historyObject = new History();
                    $historyObject->setUid(uniqid());
                    $historyObject->setField($field);
                    $historyObject->setRecord($recordObject);
                    $historyObject->setUsername($historyValue[0]['username']);
                    $historyObject->setReason($historyValue[0]['reason']);
                    $historyObject->setHistory($historyValue[0]['history']);
                    $historyObject->setStartdate($startDate);
                    $em->persist($historyObject);

                }
            }
        }
        $em->flush();

        return new Response('success');

    }

    /**
     * Importing Legacy Record Training.
     */

    public function LegacyUpdateRecordsTrainingAction($training)
    {

        $em = $this->getDoctrine()->getManager();

        $recordTrainings = json_decode($training, True);

        foreach ($recordTrainings as $key => $trainingValue) {

            //getting the Object if Exist from the Database
            $recordObject = $em->getRepository('HrisRecordsBundle:Record')->findOneby(array('instance' => $trainingValue['instance']));

            if (!empty($recordObject)){

                $startDate = new \DateTime($trainingValue['startdate']['date']);
                $endDate = new \DateTime($trainingValue['enddate']['date']);
                $coursename = rtrim($trainingValue['coursename']);
                $courseLocation = rtrim($trainingValue['courselocation']);

                $trainingObjectInstance = $em->getRepository('HrisRecordsBundle:Training')->findOneby(array('record' => $recordObject, 'coursename' => $coursename, 'courselocation' => $courseLocation, 'startdate' => $startDate, 'enddate' => $endDate));

                if ($trainingObjectInstance == NULL) {

                    $trainingObject = new Training();
                    $trainingObject->setUid(uniqid());
                    $trainingObject->setRecord($recordObject);
                    $trainingObject->setUsername($trainingValue['username']);
                    $trainingObject->setStartdate($startDate);
                    $trainingObject->setEnddate($endDate);
                    $trainingObject->setCoursename($coursename);
                    $trainingObject->setCourselocation($courseLocation);
                    $trainingObject->setSponsor($trainingValue['sponser']);

                    $em->persist($trainingObject);

                }
            }
        }
        $em->flush();

        return new Response('success');

    }
}
