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
namespace Hris\OrganisationunitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\OrganisationunitBundle\Entity\OrganisationunitCompleteness;
use Hris\OrganisationunitBundle\Form\OrganisationunitCompletenessType;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * OrganisationunitCompleteness controller.
 *
 * @Route("/organisationunitcompleteness")
 */
class OrganisationunitCompletenessController extends Controller
{

    /**
     * Lists all OrganisationunitCompleteness entities.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNITCOMPLETENESS_LIST,ROLE_USER")
     * @Route("/", name="organisationunitcompleteness")
     * @Route("/list", name="organisationunitcompleteness_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitCompleteness')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new OrganisationunitCompleteness entity.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNITCOMPLETENESS_CREATE,ROLE_USER")
     * @Route("/", name="organisationunitcompleteness_create")
     * @Method("POST")
     * @Template("HrisOrganisationunitBundle:OrganisationunitCompleteness:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new OrganisationunitCompleteness();
        $form = $this->createForm(new OrganisationunitCompletenessType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('organisationunitcompleteness_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new OrganisationunitCompleteness entity.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNITCOMPLETENESS_CREATE,ROLE_USER")
     * @Route("/new", name="organisationunitcompleteness_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new OrganisationunitCompleteness();
        $form   = $this->createForm(new OrganisationunitCompletenessType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a OrganisationunitCompleteness entity.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNITCOMPLETENESS_SHOW,ROLE_USER")
     * @Route("/{id}", requirements={"id"="\d+"}, requirements={"id"="\d+"}, name="organisationunitcompleteness_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitCompleteness')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitCompleteness entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing OrganisationunitCompleteness entity.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNITCOMPLETENESS_UPDATE,ROLE_USER")
     * @Route("/{id}/edit", requirements={"id"="\d+"}, name="organisationunitcompleteness_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitCompleteness')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitCompleteness entity.');
        }

        $editForm = $this->createForm(new OrganisationunitCompletenessType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing OrganisationunitCompleteness entity.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNITCOMPLETENESS_UPDATE,ROLE_USER")
     * @Route("/{id}", requirements={"id"="\d+"}, name="organisationunitcompleteness_update")
     * @Method("PUT")
     * @Template("HrisOrganisationunitBundle:OrganisationunitCompleteness:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitCompleteness')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitCompleteness entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new OrganisationunitCompletenessType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('organisationunitcompleteness_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a OrganisationunitCompleteness entity.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNITCOMPLETENESS_DELETE,ROLE_USER")
     * @Route("/{id}", requirements={"id"="\d+"}, name="organisationunitcompleteness_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitCompleteness')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find OrganisationunitCompleteness entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('organisationunitcompleteness'));
    }

    /**
     * Creates a form to delete a OrganisationunitCompleteness entity by id.
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
