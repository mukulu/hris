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
use Hris\FormBundle\Entity\ResourceTableFieldMember;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\FormBundle\Entity\ResourceTable;
use Hris\FormBundle\Form\ResourceTableType;

/**
 * ResourceTable controller.
 *
 * @Route("/resourcetable")
 */
class ResourceTableController extends Controller
{

    /**
     * Lists all ResourceTable entities.
     *
     * @Route("/", name="resourcetable")
     * @Route("/list", name="resourcetable_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $delete_forms = NULL;
        $entities = $em->getRepository('HrisFormBundle:ResourceTable')->findAll();
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
     * Creates a new ResourceTable entity.
     *
     * @Route("/", name="resourcetable_create")
     * @Method("POST")
     * @Template("HrisFormBundle:ResourceTable:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new ResourceTable();
        $form = $this->createForm(new ResourceTableType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $incr=1;
            $requestcontent = $request->request->get('hris_formbundle_resourcetabletype');
            $fieldIds = $requestcontent['fields'];
            foreach($fieldIds as $fieldIdKey=>$fieldId) {
                $field = $this->getDoctrine()->getRepository('HrisFormBundle:Field')->findOneBy(array('id'=>$fieldId));
                $resourceTableFieldMember = new ResourceTableFieldMember();
                $resourceTableFieldMember->setField($field);
                $resourceTableFieldMember->setResourceTable($entity);
                $resourceTableFieldMember->setSort($incr++);
                $entity->addResourceTableFieldMember($resourceTableFieldMember);
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('resourcetable_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new ResourceTable entity.
     *
     * @Route("/new", name="resourcetable_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ResourceTable();
        $form   = $this->createForm(new ResourceTableType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a ResourceTable entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="resourcetable_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:ResourceTable')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ResourceTable entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Finds and generates a ResourceTable entity.
     *
     * @Route("/{id}/generate/{context}", requirements={"id"="\d+","context"="graceful|forced"}, defaults={"context"="graceful"}, name="resourcetable_generate")
     * @Method("GET")
     * @Template()
     */
    public function generateAction($id,$context)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:ResourceTable')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ResourceTable entity.');
        }
        if($context=="forced") {
            $entity->setIsgenerating(false);
            $em->persist($entity);
            $em->flush();
        }

        $deleteForm = $this->createDeleteForm($id);

        $success = $entity->generateResourceTable($em);
        $messageLog = rtrim($entity->getMessageLog(),"\n");
        $messageLogArray = explode("\n",$messageLog);
        $messages = NULL;
        foreach($messageLogArray as $key=>$logLine) {
            $logLineArray = explode(":",$logLine);
            if(!empty($logLine)) $messages[] = $logLineArray;
        }

        return array(
            'success'=> $success,
            'messages'=>$messages,
            'entity'=>$entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ResourceTable entity.
     *
     * @Route("/{id}/edit", requirements={"id"="\d+"}, name="resourcetable_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:ResourceTable')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ResourceTable entity.');
        }

        $editForm = $this->createForm(new ResourceTableType(), $entity);

        $resourceTAbleFieldMembers = $em->getRepository('HrisFormBundle:ResourceTableFieldMember')->findBy(array('resourceTable'=>$entity));
        $fields = new ArrayCollection();
        foreach($resourceTAbleFieldMembers as $resourceTAbleFieldMemberKey=>$resourceTAbleFieldMember) {
            $fields->add($resourceTAbleFieldMember->getField());
        }
        $editForm->get('fields')->setData($fields);

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing ResourceTable entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="resourcetable_update")
     * @Method("PUT")
     * @Template("HrisFormBundle:ResourceTable:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:ResourceTable')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ResourceTable entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ResourceTableType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $incr=1;
            $requestcontent = $request->request->get('hris_formbundle_resourcetabletype');
            $fieldIds = $requestcontent['fields'];
            // Clear ResourceTableFieldMembers
            //Get rid of current fields
            $em->createQueryBuilder('resourceTableFieldMember')
                ->delete('HrisFormBundle:ResourceTableFieldMember','resourceTableFieldMember')
                ->where('resourceTableFieldMember.resourceTable= :resourceTable')
                ->setParameter('resourceTable',$entity)
                ->getQuery()->getResult();
            $em->flush();
            foreach($fieldIds as $fieldIdKey=>$fieldId) {
                $field = $this->getDoctrine()->getRepository('HrisFormBundle:Field')->findOneBy(array('id'=>$fieldId));
                $resourceTableFieldMember = new ResourceTableFieldMember();
                $resourceTableFieldMember->setField($field);
                $resourceTableFieldMember->setResourceTable($entity);
                $resourceTableFieldMember->setSort($incr++);
                $entity->addResourceTableFieldMember($resourceTableFieldMember);
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('resourcetable_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a ResourceTable entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="resourcetable_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisFormBundle:ResourceTable')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ResourceTable entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('resourcetable'));
    }

    /**
     * Creates a form to delete a ResourceTable entity by id.
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
