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

use Hris\RecordsBundle\Entity\Record;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\ImportExportBundle\Form\ExportType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use JMS\SecurityExtraBundle\Annotation\Secure;
use ZipArchive;

ini_set('zlib.output_compression', 'Off');

/**
 * Export controller.
 *
 * @Route("/importexport/export")
 */
class ExportController extends Controller
{

    /**
     * Lists all Export entities.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_EXPORT_LIST")
     * @Route("/", name="importexport_export")
     * @Route("/list", name="importexport_export_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $exportForm = $this->createForm(new ExportType(),null,array('em'=>$this->getDoctrine()->getManager()));
        $exportForm->get('withLowerLevels')->setData(True);

        return array(
            'exportForm'=>$exportForm->createView(),
        );
    }
    /**
     * Creates a new Export entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_EXPORT_CREATE")
     * @Route("/{_format}", requirements={"_format"="json|"}, defaults={"_format"="zip"}, name="importexport_export_create")
     * @Method("POST")
     */
    public function createAction(Request $request,$_format="json")
    {

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();

        $exportForm = $this->createForm(new ExportType(),null,array('em'=>$this->getDoctrine()->getManager()));
        $exportForm->bind($request);

        if ($exportForm->isValid()) {
            $exportFormData = $exportForm->getData();
            $organisationunit = $exportFormData['organisationunit'];
            $forms = $exportFormData['forms'];
            $withLowerLevels = $exportFormData['withLowerLevels'];
        }

        /*
         * Generate Selected Form Ids.
         */
        $formIds = Array();
        foreach($forms as $formKey=>$form) {
            $formIds[] = $form->getId();
        }

        /*
       * Getting the Field Metadata and Values
       */
        $fields = $em->getRepository( 'HrisFormBundle:Field' )
            ->createQueryBuilder('f')
            ->select('f', 'd.uid as datatype_uid', 'i.uid as inputtype_uid')
            ->join('f.dataType', 'd')
            ->join('f.inputType', 'i')
            ->getQuery()
            ->getArrayResult();


        /*
        * Getting the Field Options Metadata and Values
        */
        $fieldOptions = $em->getRepository( 'HrisFormBundle:FieldOption' )
            ->createQueryBuilder('o')
            ->select('o', 'f.name as field_name')
            ->join('o.field', 'f')
            ->getQuery()
            ->getArrayResult();

        /*
        * Getting the Organisation Unit Metadata
        */

        $user = $this->container->get('security.context')->getToken()->getUser();

        if ($withLowerLevels){

            $orgUnitObj = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($organisationunit);

            $orgUnit = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->getAllChildren($orgUnitObj);

        }else{
            $orgUnit = $em->getRepository( 'HrisOrganisationunitBundle:Organisationunit' )
                ->createQueryBuilder('o')
                ->select('o', 'p.shortname')
                ->join('o.parent', 'p')
                ->where('o.id = :uid')
                ->setParameters(array('uid' => $organisationunit ))
                ->getQuery()
                ->getArrayResult();
        }

        /*
        * getting Organisation Units Ids.
        */

        foreach ($orgUnit as $key => $unit){
            $unitId[] = $unit[0]['uid'];
        }

        /*
        * Getting Records depending on the form and organisation unit
        */

        $records = $em->getRepository( 'HrisRecordsBundle:Record' )
            ->createQueryBuilder('r')
            ->select('r', 'f.id as form_id', 'o.uid as orgunit_uid')
            ->join('r.form', 'f')
            ->join('r.organisationunit', 'o')
            ->Where($queryBuilder->expr()->in('f.id', $formIds))
            ->andWhere($queryBuilder->expr()->in('o.uid', $unitId))
            ->getQuery()
            ->getArrayResult();


       $fs = new Filesystem();

       // $fs->chmod('/temp/export', 0777, 0000, true);

        $filename = "export_".date("Y_m_d_His").".zip";

        //$fs->chmod('records.json', 0666);


        $archive = new ZipArchive();
        $archive->open($filename, ZipArchive::CREATE);
        $archive->addFromString('organizationUnit.json', json_encode($orgUnit));
        $archive->addFromString('fields.json', json_encode($fields));
        $archive->addFromString('fieldOptions.json', json_encode($fieldOptions));
        $archive->addFromString('records.json', json_encode($records));
        $archive->close();

        $fs->chmod($filename, 0666);

        $response = new Response(file_get_contents($filename));

        $d = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
        $response->headers->set('Content-Disposition', $d);

        unlink($filename);

        return $response;

    }

    /**
     * Displays a form to create a new Export entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_EXPORT_CREATE")
     * @Route("/new", name="importexport_export_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $form   = $this->createForm(new ExportType(), null);

        return array(
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Export entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_EXPORT_SHOW")
     * @Route("/{id}", requirements={"id"="\d+"}, name="importexport_export_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisImportExportBundle:Export')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Export entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Export entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_EXPORT_UPDATE")
     * @Route("/{id}/edit", name="importexport_export_edit")
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
     * Edits an existing Export entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_EXPORT_UPDATE")
     * @Route("/{id}", name="importexport_export_update")
     * @Method("PUT")
     * @Template("HrisImportExportBundle:Export:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ExportType(), null);
        $editForm->bind($request);

        if ($editForm->isValid()) {

            return $this->redirect($this->generateUrl('importexport_export'));
        }

        return array(
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Export entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_EXPORT_DELETE")
     * @Route("/{id}", name="importexport_export_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        return $this->redirect($this->generateUrl('importexport_export'));
    }

    /**
     * Creates a form to delete a Export entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
