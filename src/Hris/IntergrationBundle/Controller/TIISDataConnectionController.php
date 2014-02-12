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
 * @author Wilfred Felix Senyoni <senyoni@gmail.com>
 *
 */
namespace Hris\IntergrationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\IntergrationBundle\Entity\TIISDataConnection;
use Hris\IntergrationBundle\Form\TIISDataConnectionType;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * TIISDataConnection controller.
 *
 * @Route("/tiisdataconnection")
 */
class TIISDataConnectionController extends Controller
{

    /**
     * Lists all TIISDataConnection entities.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_TIISDATACONNECTION_LIST")
     * @Route("/", name="tiisdataconnection")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisIntergrationBundle:TIISDataConnection')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new TIISDataConnection entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_TIISDATACONNECTION_CREATE")
     * @Route("/", name="tiisdataconnection_create")
     * @Method("POST")
     * @Template("HrisIntergrationBundle:TIISDataConnection:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new TIISDataConnection();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tiisdataconnection_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a TIISDataConnection entity.
    *
    * @param TIISDataConnection $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(TIISDataConnection $entity)
    {
        $form = $this->createForm(new TIISDataConnectionType(), $entity, array(
            'action' => $this->generateUrl('tiisdataconnection_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new TIISDataConnection entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_TIISDATACONNECTION_CREATE")
     * @Route("/new", name="tiisdataconnection_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TIISDataConnection();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a TIISDataConnection entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_TIISDATACONNECTION_SHOW")
     * @Route("/{id}", name="tiisdataconnection_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisIntergrationBundle:TIISDataConnection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TIISDataConnection entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing TIISDataConnection entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_TIISDATACONNECTION_UPDATE")
     * @Route("/{id}/edit", name="tiisdataconnection_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisIntergrationBundle:TIISDataConnection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TIISDataConnection entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a TIISDataConnection entity.
    *
    * @param TIISDataConnection $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TIISDataConnection $entity)
    {
        $form = $this->createForm(new TIISDataConnectionType(), $entity, array(
            'action' => $this->generateUrl('tiisdataconnection_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing TIISDataConnection entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_TIISDATACONNECTION_UPDATE")
     * @Route("/{id}", name="tiisdataconnection_update")
     * @Method("PUT")
     * @Template("HrisIntergrationBundle:TIISDataConnection:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisIntergrationBundle:TIISDataConnection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TIISDataConnection entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tiisdataconnection_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a TIISDataConnection entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_TIISDATACONNECTION_DELETE")
     * @Route("/{id}", name="tiisdataconnection_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisIntergrationBundle:TIISDataConnection')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TIISDataConnection entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tiisdataconnection'));
    }

    /**
     * Creates a form to delete a TIISDataConnection entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tiisdataconnection_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
