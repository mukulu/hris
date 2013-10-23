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

use Doctrine\Common\Collections\ArrayCollection;
use Hris\FormBundle\Entity\FormFieldMember;
use Hris\FormBundle\Entity\FormVisibleFields;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\FormBundle\Entity\Form;
use Hris\FormBundle\Form\FormType;

/**
 * Form controller.
 *
 * @Route("/form")
 */
class FormController extends Controller
{

    /**
     * Lists all Form entities.
     *
     * @Route("/", name="form")
     * @Route("/list", name="form_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisFormBundle:Form')->findAll();

        $delete_forms = NULL;
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
     * Creates a new Form entity.
     *
     * @Route("/", name="form_create")
     * @Method("POST")
     * @Template("HrisFormBundle:Form:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Form();
        $form = $this->createForm(new FormType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $requestcontent = $request->request->get('hris_formbundle_formtype');

            // Add Form Field Members
            $formFieldMemberIds = $requestcontent['formFieldMembers'];
            $incr=1;
            foreach($formFieldMemberIds as $formFieldMemberIdKey=>$formFieldMemberId) {
                $field = $this->getDoctrine()->getRepository('HrisFormBundle:Field')->findOneBy(array('id'=>$formFieldMemberId));
                $formFieldMember = new FormFieldMember();
                $formFieldMember->setForm($entity);
                $formFieldMember->setField($field);
                $formFieldMember->setSort($incr++);
                $entity->addFormFieldMember($formFieldMember);
            }
            // Add Form Visible Fields
            $formVisibleFieldIds = $requestcontent['formVisibleFields'];
            $incr=1;
            foreach($formVisibleFieldIds as $formVisibleFieldIdKey=>$formVisibleFieldId) {
                $field = $this->getDoctrine()->getRepository('HrisFormBundle:Field')->findOneBy(array('id'=>$formVisibleFieldId));
                $formVisibleField = new FormVisibleFields();
                $formVisibleField->setForm($entity);
                $formVisibleField->setField($field);
                $formVisibleField->setSort($incr++);
                $entity->addFormVisibleField($formVisibleField);
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('form_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Form entity.
     *
     * @Route("/new", name="form_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Form();
        $form   = $this->createForm(new FormType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Form entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="form_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:Form')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Form entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Form entity.
     *
     * @Route("/{id}/edit", requirements={"id"="\d+"}, name="form_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:Form')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Form entity.');
        }

        $editForm = $this->createForm(new FormType(), $entity);

        // Populate selected Form Field Members
        $formFields = new ArrayCollection();
        foreach($entity->getFormFieldMember() as $formFieldMemberKey=>$formFieldMember) {
            $formFields->add($formFieldMember->getField());
        }
        $editForm->get('formFieldMembers')->setData($formFields);
        // Populate selected Visible Fields
        $visibleFields = new ArrayCollection();
        foreach($entity->getFormVisibleFields() as $formVisibleFieldKey=>$formVisibleField) {
            $visibleFields->add($formVisibleField->getField());
        }
        $editForm->get('formVisibleFields')->setData($visibleFields);

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Form entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="form_update")
     * @Method("PUT")
     * @Template("HrisFormBundle:Form:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:Form')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Form entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FormType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $requestcontent = $request->request->get('hris_formbundle_formtype');
            $formFieldMemberIds = $requestcontent['formFieldMembers'];
            // Clear Form Field Members
            //$entity->removeAllFormFieldMember();
            $em->createQueryBuilder('formFieldMember')
                ->delete('HrisFormBundle:FormFieldMember','formFieldMember')
                ->where('formFieldMember.form= :form')
                ->setParameter('form',$entity)
                ->getQuery()->getResult();
            $em->flush();
            $incr=1;
            foreach($formFieldMemberIds as $formFieldMemberIdKey=>$formFieldMemberId) {
                $field = $this->getDoctrine()->getRepository('HrisFormBundle:Field')->findOneBy(array('id'=>$formFieldMemberId));
                $formFieldMember = new FormFieldMember();
                $formFieldMember->setForm($entity);
                $formFieldMember->setField($field);
                $formFieldMember->setSort($incr++);
                $entity->addFormFieldMember($formFieldMember);
            }
            $visibleFormFieldIds = $requestcontent['formVisibleFields'];
            $em->persist($entity);
            // Clear Visible Form Field
            //$entity->removeAllFormVisibleFields();
            $em->createQueryBuilder('formVisibleFields')
                ->delete('HrisFormBundle:FormVisibleFields','formVisibleFields')
                ->where('formVisibleFields.form= :form')
                ->setParameter('form',$entity)
                ->getQuery()->getResult();
            $em->flush();
            $incr=1;
            foreach($visibleFormFieldIds as $visibleFormFieldIdKey=>$visibleFormFieldId) {
                $field = $this->getDoctrine()->getRepository('HrisFormBundle:Field')->findOneBy(array('id'=>$visibleFormFieldId));
                $visibleFormField = new FormVisibleFields();
                $visibleFormField->setForm($entity);
                $visibleFormField->setField($field);
                $visibleFormField->setSort($incr++);
                $entity->addFormVisibleField($visibleFormField);
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('form_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Form entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="form_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisFormBundle:Form')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Form entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('form'));
    }

    /**
     * Creates a form to delete a Form entity by id.
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
