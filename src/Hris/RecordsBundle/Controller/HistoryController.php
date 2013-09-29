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
namespace Hris\RecordsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\RecordsBundle\Entity\History;
use Hris\RecordsBundle\Form\HistoryType;

/**
 * History controller.
 *
 * @Route("/history")
 */
class HistoryController extends Controller
{

    /**
     * Lists all History entities.
     *
     * @Route("/", name="history")
     * @Route("/list", name="history_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisRecordsBundle:History')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new History entity.
     *
     * @Route("/", name="history_create")
     * @Method("POST")
     * @Template("HrisRecordsBundle:History:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new History();
        $form = $this->createForm(new HistoryType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('history_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new History entity.
     *
     * @Route("/new", name="history_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new History();
        $form   = $this->createForm(new HistoryType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a History entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, requirements={"id"="\d+"}, name="history_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisRecordsBundle:History')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find History entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing History entity.
     *
     * @Route("/{id}/edit", requirements={"id"="\d+"}, name="history_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisRecordsBundle:History')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find History entity.');
        }

        $editForm = $this->createForm(new HistoryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing History entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="history_update")
     * @Method("PUT")
     * @Template("HrisRecordsBundle:History:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisRecordsBundle:History')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find History entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new HistoryType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('history_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a History entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="history_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisRecordsBundle:History')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find History entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('history'));
    }

    /**
     * Creates a form to delete a History entity by id.
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
