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
namespace Hris\FormBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\FormBundle\Entity\FieldOptionGroup;
use Hris\FormBundle\Form\FieldOptionGroupType;

/**
 * FieldOptionGroup controller.
 *
 * @Route("/fieldoptiongroup")
 */
class FieldOptionGroupController extends Controller
{

    /**
     * Lists all FieldOptionGroup entities.
     *
     * @Route("/", name="fieldoptiongroup")
     * @Route("/list", name="fieldoptiongroup_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisFormBundle:FieldOptionGroup')->findAll();

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
     * Creates a new FieldOptionGroup entity.
     *
     * @Route("/", name="fieldoptiongroup_create")
     * @Method("POST")
     * @Template("HrisFormBundle:FieldOptionGroup:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new FieldOptionGroup();
        $form = $this->createForm(new FieldOptionGroupType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('fieldoptiongroup_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new FieldOptionGroup entity.
     *
     * @Route("/new", name="fieldoptiongroup_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FieldOptionGroup();
        $form   = $this->createForm(new FieldOptionGroupType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a FieldOptionGroup entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, requirements={"id"="\d+"}, name="fieldoptiongroup_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:FieldOptionGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FieldOptionGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FieldOptionGroup entity.
     *
     * @Route("/{id}/edit", requirements={"id"="\d+"}, name="fieldoptiongroup_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:FieldOptionGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FieldOptionGroup entity.');
        }

        $editForm = $this->createForm(new FieldOptionGroupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing FieldOptionGroup entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="fieldoptiongroup_update")
     * @Method("PUT")
     * @Template("HrisFormBundle:FieldOptionGroup:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:FieldOptionGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FieldOptionGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FieldOptionGroupType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('fieldoptiongroup_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a FieldOptionGroup entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="fieldoptiongroup_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisFormBundle:FieldOptionGroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FieldOptionGroup entity.');
            }

            $em->createQueryBuilder('fieldOptionGroup')
                ->delete('HrisFormBundle:FieldOptionGroup','fieldOptionGroup')
                ->where('fieldOptionGroup.id= :fieldOptionGroupId')
                ->setParameter('fieldOptionGroupId',$id)
                ->getQuery()->getResult();
            $em->flush();
        }

        return $this->redirect($this->generateUrl('fieldoptiongroup'));
    }

    /**
     * Creates a form to delete a FieldOptionGroup entity by id.
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
