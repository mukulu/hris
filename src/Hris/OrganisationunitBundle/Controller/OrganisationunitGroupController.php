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

use Hris\OrganisationunitBundle\Form\OrganisationunitGroupMemberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\OrganisationunitBundle\Entity\OrganisationunitGroup;
use Hris\OrganisationunitBundle\Form\OrganisationunitGroupType;

/**
 * OrganisationunitGroup controller.
 *
 * @Route("/organisationunitgroup")
 */
class OrganisationunitGroupController extends Controller
{

    /**
     * Lists all OrganisationunitGroup entities.
     *
     * @Route("/", name="organisationunitgroup")
     * @Route("/list", name="organisationunitgroup_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroup')->findAll();

        foreach($entities as $entity) {
            $delete_form= $this->createDeleteForm($entity->getId());
            $delete_forms[$entity->getId()] = $delete_form->createView();
        }

        return array(
            'entities' => $entities,
            'delete_forms' => $delete_forms,
        );
    }
    /**
     * Creates a new OrganisationunitGroup entity.
     *
     * @Route("/", name="organisationunitgroup_create")
     * @Method("POST")
     * @Template("HrisOrganisationunitBundle:OrganisationunitGroup:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new OrganisationunitGroup();
        $form = $this->createForm(new OrganisationunitGroupMemberType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $formData = $this->getRequest()->request->get('hris_organisationunitbundle_organisationunitgroupmembertype');
            $organisationunitGroupMemberIds = $formData['organisationunitGroupMembers'];
            if(!empty($organisationunitGroupMemberIds)) {
                $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
                $organisationunits = $queryBuilder->select('organisationunit')
                    ->from('HrisOrganisationunitBundle:Organisationunit','organisationunit')
                    ->where($queryBuilder->expr()->in('organisationunit.id',$organisationunitGroupMemberIds))
                    ->getQuery()->getResult();
                foreach($organisationunits as $key=>$organisationunit) {
                    $entity->addOrganisationunit($organisationunit);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('organisationunitgroup_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new OrganisationunitGroup entity.
     *
     * @Route("/new", name="organisationunitgroup_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new OrganisationunitGroup();
        $form   = $this->createForm(new OrganisationunitGroupMemberType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a OrganisationunitGroup entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="organisationunitgroup_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing OrganisationunitGroup entity.
     *
     * @Route("/{id}/edit", name="organisationunitgroup_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitGroup entity.');
        }

        $editForm = $this->createForm(new OrganisationunitGroupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing OrganisationunitGroup entity.
     *
     * @Route("/{id}", name="organisationunitgroup_update")
     * @Method("PUT")
     * @Template("HrisOrganisationunitBundle:OrganisationunitGroup:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new OrganisationunitGroupType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('organisationunitgroup_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Updates OrganisationunitGroup member.
     *
     * @Route("/{id}/member", requirements={"id"="\d+"}, name="organisationunitgroup_update_member")
     * @Method("POST")
     * @Template()
     */
    public function updateMemberAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitGroup entity.');
        }
        $organisationunitid = $this->getRequest()->request->get('organisationunitid');
        $selected = $this->getRequest()->request->get('selected');
        $organisationunit = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($organisationunitid);
        if($selected=="true") {
            $entity->addOrganisationunit($organisationunit);
        }else {
            $entity->removeOrganisationunit($organisationunit);
        }
        $em->persist($entity);
        $em->flush();

        return array(
            'success'      => 'success',
        );
    }

    /**
     * Deletes a OrganisationunitGroup entity.
     *
     * @Route("/{id}", name="organisationunitgroup_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find OrganisationunitGroup entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('organisationunitgroup'));
    }

    /**
     * Creates a form to delete a OrganisationunitGroup entity by id.
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
