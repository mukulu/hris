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
namespace Hris\DataQualityBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\DataQualityBundle\Entity\Validation;
use Hris\DataQualityBundle\Form\ValidationType;
//use Hris\FormBundle\Entity\Field;

/**
 * Validation controller.
 *
 * @Route("/validation")
 */
class ValidationController extends Controller
{

    /**
     * Lists all Validation entities.
     *
     * @Route("/", name="validation")
     * @Route("/list", name="validation_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisDataQualityBundle:Validation')->findAll();
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
     * Creates a new Validation entity.
     *
     * @Route("/", name="validation_create")
     * @Method("POST")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $entity  = new Validation();
        $form = $this->createForm(new ValidationType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('validation_show', array('id' => $entity->getId())));
        }else {
            print_r($form->getErrors());
            die();
        }

        $leftExpressionFields = $this->getDoctrine()->getRepository('HrisFormBundle:Field')->findAll();
        $rightExpressionFields = $this->getDoctrine()->getRepository(('HrisFormBundle:Field'))->findAll();

        return array(
            'entity' => $entity,
            'leftExpressionFields'=>$leftExpressionFields,
            'rightExpressionFields'=>$rightExpressionFields,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Validation entity.
     *
     * @Route("/new", name="validation_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Validation();
        $form   = $this->createForm(new ValidationType(), $entity);

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT n from Hris\FormBundle\Entity\Field n WHERE (n.name = :name1 OR n.name = :name2 OR n.name=:name3 OR n.name=:name4 OR n.name=:name5 OR n.name=:name6 OR n.name=:name7)');
        $query->setParameters(array(
            'name1' => 'MonthlyBasicSalary',
            'name2' => 'Birthdate',
            'name3' => 'DateofLastPromotion',
            'name4' => 'DateofConfirmation',
            'name5' => 'DateofFirstAppointment',
            'name6' => 'NumberofChildrenDependants',
            'name7' => 'Now',

                    ));
        $leftExpressionFields = $query->getResult();
        $rightExpressionFields = $query->getResult();




       // $leftExpressionFields = $this->getDoctrine()->getRepository('HrisFormBundle:Field')->findByName(array('name'=>'Birthdate','name'=>'MonthlyBasicSalary'));

        //$rightExpressionFields = $this->getDoctrine()->getRepository(('HrisFormBundle:Field'))->findAll();

        return array(
            'leftExpressionFields'=>$leftExpressionFields,
            'rightExpressionFields'=>$rightExpressionFields,
            'form'   => $form->createView(),
        );
    }



    /**
     * Finds and displays a Validation entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="validation_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisDataQualityBundle:Validation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Validation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Validation entity.
     *
     * @Route("/{id}/edit", requirements={"id"="\d+"}, name="validation_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisDataQualityBundle:Validation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Validation entity.');
        }

        $editForm = $this->createForm(new ValidationType(), $entity);
        $deleteForm = $this->createDeleteForm($id);


        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT n from Hris\FormBundle\Entity\Field n WHERE (n.name = :name1 OR n.name = :name2 OR n.name=:name3 OR n.name=:name4 OR n.name=:name5 OR n.name=:name6 OR n.name=:name7)');
        $query->setParameters(array(
            'name1' => 'MonthlyBasicSalary',
            'name2' => 'Birthdate',
            'name3' => 'DateofLastPromotion',
            'name4' => 'DateofConfirmation',
            'name5' => 'DateofFirstAppointment',
            'name6' => 'NumberofChildrenDependants',
            'name7' => 'Now',

        ));
        $leftExpressionFields = $query->getResult();
        $rightExpressionFields = $query->getResult();

       // $leftExpressionFields = $this->getDoctrine()->getRepository('HrisFormBundle:Field')->findAll();

        //$rightExpressionFields = $this->getDoctrine()->getRepository(('HrisFormBundle:Field'))->findAll();

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'leftExpressionFields'=>$leftExpressionFields,
            'rightExpressionFields'=>$rightExpressionFields,
        );
    }

    /**
     * Edits an existing Validation entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="validation_update")
     * @Method("POST")
     * @Template()
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisDataQualityBundle:Validation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Validation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ValidationType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('validation_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Validation entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="validation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisDataQualityBundle:Validation')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Validation entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('validation'));
    }

    /**
     * Creates a form to delete a Validation entity by id.
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
