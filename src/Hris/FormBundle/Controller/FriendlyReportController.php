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
use Hris\FormBundle\Entity\FriendlyReportCategory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\FormBundle\Entity\FriendlyReport;
use Hris\FormBundle\Form\FriendlyReportType;

/**
 * FriendlyReport controller.
 *
 * @Route("/friendlyreport")
 */
class FriendlyReportController extends Controller
{

    /**
     * Lists all FriendlyReport entities.
     *
     * @Route("/", name="friendlyreport")
     * @Route("/list", name="friendlyreport_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisFormBundle:FriendlyReport')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new FriendlyReport entity.
     *
     * @Route("/new", name="friendlyreport_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FriendlyReport();
        $form   = $this->createForm(new FriendlyReportType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new FriendlyReport entity.
     *
     * @Route("/", name="friendlyreport_create")
     * @Method("POST")
     * @Template("HrisFormBundle:FriendlyReport:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new FriendlyReport();
        $form = $this->createForm(new FriendlyReportType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $incr=1;
            $requestcontent = $request->request->get('hris_formbundle_friendlyreporttype');
            $fieldOptionGroupIds = $requestcontent['friendlyReportCategory'];
            foreach($fieldOptionGroupIds as $fieldOptionGroupIdKey=>$fieldOptionGroupId) {
                $fieldOptionGroup = $this->getDoctrine()->getRepository('HrisFormBundle:FieldOptionGroup')->findOneBy(array('id'=>$fieldOptionGroupId));
                $friendlyReportCategory = new FriendlyReportCategory();
                $friendlyReportCategory->setFriendlyReport($entity);
                $friendlyReportCategory->setFieldOptionGroup($fieldOptionGroup);
                $friendlyReportCategory->setSort($incr++);
                $entity->addFriendlyReportCategory($friendlyReportCategory);
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('friendlyreport_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a FriendlyReport entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="friendlyreport_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:FriendlyReport')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FriendlyReport entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FriendlyReport entity.
     *
     * @Route("/{id}/edit", name="friendlyreport_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:FriendlyReport')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FriendlyReport entity.');
        }

        $editForm = $this->createForm(new FriendlyReportType(), $entity);

        $friendlyReportCategories = $em->getRepository('HrisFormBundle:FriendlyReportCategory')->findBy(array('friendlyReport'=>$entity));
        $fieldOptionGroups = new ArrayCollection();
        foreach($friendlyReportCategories as $friendlyReportCategoryKey=>$friendlyReportCategory) {
            $fieldOptionGroups->add($friendlyReportCategory->getFieldOptionGroup());
        }
        $editForm->get('friendlyReportCategory')->setData($fieldOptionGroups);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing FriendlyReport entity.
     *
     * @Route("/{id}", name="friendlyreport_update")
     * @Method("PUT")
     * @Template("HrisFormBundle:FriendlyReport:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:FriendlyReport')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FriendlyReport entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FriendlyReportType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $incr=1;
            $requestcontent = $request->request->get('hris_formbundle_friendlyreporttype');
            $fieldOptionGroupIds = $requestcontent['friendlyReportCategory'];
            // Clear Report categories
            $entity->removeAllFriendlyReportCategory();
            foreach($fieldOptionGroupIds as $fieldOptionGroupIdKey=>$fieldOptionGroupId) {
                $fieldOptionGroup = $this->getDoctrine()->getRepository('HrisFormBundle:FieldOptionGroup')->findOneBy(array('id'=>$fieldOptionGroupId));
                $friendlyReportCategory = new FriendlyReportCategory();
                $friendlyReportCategory->setFriendlyReport($entity);
                $friendlyReportCategory->setFieldOptionGroup($fieldOptionGroup);
                $friendlyReportCategory->setSort($incr++);
                $entity->addFriendlyReportCategory($friendlyReportCategory);
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('friendlyreport_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a FriendlyReport entity.
     *
     * @Route("/{id}", name="friendlyreport_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisFormBundle:FriendlyReport')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FriendlyReport entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('friendlyreport'));
    }

    /**
     * Creates a form to delete a FriendlyReport entity by id.
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
