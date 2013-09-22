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
namespace Hris\DashboardBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\DashboardBundle\Entity\Settings;
use Hris\DashboardBundle\Form\SettingsType;

/**
 * Settings controller.
 *
 * @Route("/settings")
 */
class SettingsController extends Controller
{

    /**
     * Lists all Settings entities.
     *
     * @Route("/", name="settings")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisDashboardBundle:Settings')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Settings entity.
     *
     * @Route("/", name="settings_create")
     * @Method("POST")
     * @Template("HrisDashboardBundle:Settings:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Settings();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('settings_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Settings entity.
    *
    * @param Settings $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Settings $entity)
    {
        $form = $this->createForm(new SettingsType(), $entity, array(
            'action' => $this->generateUrl('settings_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Settings entity.
     *
     * @Route("/{username}/new", requirements={"username"="\w+"}, name="settings_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Settings();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Settings entity.
     *
     * @Route("/{username}", requirements={"username"="\w+"}, name="settings_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($username)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->createQueryBuilder()->select('settings')
            ->from('HrisDashboardBundle:Settings', 'settings')
            ->innerJoin('settings.user','user')
            ->where('user.username=:username')
            ->setParameter('username',$username)
            ->getQuery()->getResult();

        if (!$entity) {
            return $this->redirect($this->generateUrl('settings_new', array('username' => $username)));
        }

        $deleteForm = $this->createDeleteForm($entity->getId());

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Settings entity.
     *
     * @Route("/{id}/edit", name="settings_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisDashboardBundle:Settings')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Settings entity.');
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
    * Creates a form to edit a Settings entity.
    *
    * @param Settings $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Settings $entity)
    {
        $form = $this->createForm(new SettingsType(), $entity, array(
            'action' => $this->generateUrl('settings_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Settings entity.
     *
     * @Route("/{id}", name="settings_update")
     * @Method("PUT")
     * @Template("HrisDashboardBundle:Settings:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisDashboardBundle:Settings')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Settings entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('settings_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Settings entity.
     *
     * @Route("/{id}", name="settings_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisDashboardBundle:Settings')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Settings entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('settings'));
    }

    /**
     * Creates a form to delete a Settings entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('settings_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
