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

use Hris\ImportExportBundle\Form\ImportType;
use Hris\RecordsBundle\Entity\Record;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\ImportExportBundle\Entity\Export;
use Hris\ImportExportBundle\Form\ExportType;

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
     * @Route("/", name="importexport_import")
     * @Route("/list", name="importexport_import_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $importForm = $this->createForm(new ImportType(),null);

        return array(
            'importForm'=>$importForm->createView(),
        );
    }
    /**
     * Creates a new Import entity.
     *
     * @Route("/{_format}", requirements={"_format"="json|"}, defaults={"_format"="json"}, name="importexport_import_create")
     * @Method("POST")
     * @Template("HrisImportExportBundle:Export:export.json.twig")
     */
    public function createAction(Request $request,$_format="json")
    {
        $serializer = $this->container->get('serializer');

        $importForm = $this->createForm(new ImportType(),null);
        $importForm->bind($request);

        if ($importForm->isValid()) {
            $importFormData = $importForm->getData();
            $file = $importFormData['file'];
        }

        $serializer = $this->container->get('serializer');
        return array(
        );
    }

    /**
     * Displays a form to create a new Import entity.
     *
     * @Route("/new", name="importexport_import_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $form   = $this->createForm(new ImportType(), null);

        return array(
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Import entity.
     *
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
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Import entity.
     *
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
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Import entity.
     *
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
            ->getForm()
        ;
    }
}
