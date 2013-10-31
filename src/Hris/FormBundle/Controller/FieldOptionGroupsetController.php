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
use Hris\FormBundle\Entity\FieldOptionGroupset;
use Hris\FormBundle\Form\FieldOptionGroupsetType;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * FieldOptionGroupset controller.
 *
 * @Route("/fieldoptiongroupset")
 */
class FieldOptionGroupsetController extends Controller
{

    /**
     * Lists all FieldOptionGroupset entities.
     *
     * @Secure(roles="ROLE_FIELDOPTIONGROUPSET_LIST,ROLE_USER")
     * @Route("/", name="fieldoptiongroupset")
     * @Route("/list", name="fieldoptiongroupset_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisFormBundle:FieldOptionGroupset')->findAll();

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
     * Creates a new FieldOptionGroupset entity.
     *
     * @Secure(roles="ROLE_FIELDOPTIONGROUPSET_CREATE,ROLE_USER")
     * @Route("/", name="fieldoptiongroupset_create")
     * @Method("POST")
     * @Template("HrisFormBundle:FieldOptionGroupset:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new FieldOptionGroupset();
        $form = $this->createForm(new FieldOptionGroupsetType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('fieldoptiongroupset_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new FieldOptionGroupset entity.
     *
     * @Secure(roles="ROLE_FIELDOPTIONGROUPSET_CREATE,ROLE_USER")
     * @Route("/new", name="fieldoptiongroupset_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FieldOptionGroupset();
        $form   = $this->createForm(new FieldOptionGroupsetType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a FieldOptionGroupset entity.
     *
     * @Secure(roles="ROLE_FIELDOPTIONGROUPSET_SHOW,ROLE_USER")
     * @Route("/{id}", requirements={"id"="\d+"}, requirements={"id"="\d+"}, name="fieldoptiongroupset_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:FieldOptionGroupset')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FieldOptionGroupset entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FieldOptionGroupset entity.
     *
     * @Secure(roles="ROLE_FIELDOPTIONGROUPSET_UPDATE,ROLE_USER")
     * @Route("/{id}/edit", requirements={"id"="\d+"}, name="fieldoptiongroupset_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:FieldOptionGroupset')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FieldOptionGroupset entity.');
        }

        $editForm = $this->createForm(new FieldOptionGroupsetType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing FieldOptionGroupset entity.
     *
     * @Secure(roles="ROLE_FIELDOPTIONGROUPSET_UPDATE,ROLE_USER")
     * @Route("/{id}", requirements={"id"="\d+"}, name="fieldoptiongroupset_update")
     * @Method("PUT")
     * @Template("HrisFormBundle:FieldOptionGroupset:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:FieldOptionGroupset')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FieldOptionGroupset entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FieldOptionGroupsetType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('fieldoptiongroupset_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a FieldOptionGroupset entity.
     *
     * @Secure(roles="ROLE_FIELDOPTIONGROUPSET_DELETE,ROLE_USER")
     * @Route("/{id}", requirements={"id"="\d+"}, name="fieldoptiongroupset_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisFormBundle:FieldOptionGroupset')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FieldOptionGroupset entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('fieldoptiongroupset'));
    }

    /**
     * Creates a form to delete a FieldOptionGroupset entity by id.
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
