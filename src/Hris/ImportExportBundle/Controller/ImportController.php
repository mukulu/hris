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

use Doctrine\Tests\Common\Annotations\True;
use Hris\ImportExportBundle\Form\ImportType;
use Hris\RecordsBundle\Entity\Record;
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
use JMS\SecurityExtraBundle\Annotation\Secure;
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
     * @Secure(roles="ROLE_IMPORT_LIST")
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
     * @Secure(roles="ROLE_IMPORT_CREATE")
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
     * @Secure(roles="ROLE_IMPORT_CREATE")
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
     * @Secure(roles="ROLE_IMPORT_SHOW")
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
     * @Secure(roles="ROLE_IMPORT_UPDATE")
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
     * @Secure(roles="ROLE_IMPORT_UPDATE")
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
     * @Secure(roles="ROLE_IMPORT_DELETE")
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
     * @Secure(roles="ROLE_IMPORT_IMPORTFIELDS")
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
     * Importing fields Options.
     *
     * @Secure(roles="ROLE_IMPORT_IMPORTFIELDOPTIONS")
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

                print $fieldOption[0]['uid'];
                print '<br>';

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
     * Importing Organisation Units.
     *
     * @Secure(roles="ROLE_IMPORT_ORGANISATIONUNITS")
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
     * Importing Records.
     *
     * @Secure(roles="ROLE_IMPORT_IMPORTRECORDS")
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
                //$recordObject->setDatecreated($record[0]['datecreated']);

            }
        }

        $em->flush();

        return new Response('success');

    }
}
