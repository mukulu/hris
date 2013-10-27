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
namespace Hris\RecordsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\RecordsBundle\Entity\Training;
use Hris\RecordsBundle\Entity\Record;
use Hris\RecordsBundle\Form\TrainingType;

/**
 * Training controller.
 *
 * @Route("/training")
 */
class TrainingController extends Controller
{

    /**
     * Lists all Training entities.
     *
     * @Route("/", name="training")
     * @Route("/list", name="training_list")
     * @Route("/{recordid}/training", requirements={"recordid"="\d+"}, name="training_byrecord")
     * @Route("/list/{recordid}/", requirements={"recordid"="\d+"}, name="training_list_byrecord")
     * @Method("GET")
     * @Template()
     */
    public function indexAction( $recordid=NULL )
    {
        $em = $this->getDoctrine()->getManager();

        if(!empty($recordid)){
            $entities = $em->getRepository('HrisRecordsBundle:Training')->findBy(array('record'=>$recordid));
            $record = $em->getRepository('HrisRecordsBundle:Record')->findOneBy(array('id'=>$recordid));
        }

        foreach($entities as $entity) {
            $delete_form= $this->createDeleteForm($entity->getId());
            $delete_forms[$entity->getId()] = $delete_form->createView();
        }
        //print_r($record->getValue());exit;

        return array(
            'entities' => $entities,
            'delete_forms' => $delete_forms,
            'recordid' => $recordid,
            'record' => $record,
        );
    }
    /**
     * Creates a new Training entity.
     *
     * @Route("/{recordid}/recordid", requirements={"recordid"="\d+"}, name="training_create")
     * @Method("POST")
     * @Template("HrisRecordsBundle:Training:new.html.twig")
     */
    public function createAction(Request $request, $recordid = NULL)
    {
        $entity  = new Training();
        $form = $this->createForm(new TrainingType(), $entity);
        $form->bind($request);
        $user = $this->container->get('security.context')->getToken()->getUser();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!empty($recordid)) {
                $record = $this->getDoctrine()->getManager()->getRepository('HrisRecordsBundle:Record')->findOneBy(array('id'=>$recordid));
                $entity->setRecord($record);
            }else {
                $record = NULL;
                $entity->setRecord($record);
            }
            $entity->setUsername($user->getUsername());

            //Update Record Table hasTraining column
            $record->setHastraining(true);

            $em->persist($entity);
            $em->persist($record);
            $em->flush();

            return $this->redirect($this->generateUrl('training_list_byrecord', array( 'recordid' => $recordid )));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Training entity.
     *
     * @Route("/new/{recordid}/recordid", requirements={"recordid"="\d+"}, name="training_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction( $recordid=NULL )
    {
        $entity = new Training();
        $form   = $this->createForm(new TrainingType(), $entity);
        if(!empty($recordid)) {
            $record = $this->getDoctrine()->getManager()->getRepository('HrisRecordsBundle:Record')->findOneBy(array('id'=>$recordid));
        }else {
            $record = NULL;
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'recordid' => $recordid,
            'record' => $record,
        );
    }

    /**
     * Finds and displays a Training entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, requirements={"id"="\d+"}, name="training_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisRecordsBundle:Training')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Training entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Training entity.
     *
     * @Route("/{id}/edit", requirements={"id"="\d+"}, name="training_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisRecordsBundle:Training')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Training entity.');
        }

        $editForm = $this->createForm(new TrainingType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Training entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="training_update")
     * @Method("PUT")
     * @Template("HrisRecordsBundle:Training:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();

        $entity = $em->getRepository('HrisRecordsBundle:Training')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Training entity.');
        }
        $entity->setUsername($user->getUsername() );

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new TrainingType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('training_list_byrecord', array( 'recordid' => $entity->getRecord()->getId() )));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Training entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="training_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisRecordsBundle:Training')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Training entity.');
            }
            $record = $entity->getRecord();

            //check if this deleted entity is the last for this record
            $query = "SELECT count (id) as total ";
            $query .= " FROM hris_record_training T ";
            $query .= " WHERE record_id = ". $record->getId();
            $query .= " AND id <> ". $id;

            $result = $em -> getConnection() -> executeQuery($query) -> fetchAll();

            //Update records hasTraining column to false when no trainings will be left after delete
            if ( $result[0]['total'] == 0 ){
                $record->setHasTraining(false);
                $em->persist($record);
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('training_list_byrecord', array( 'recordid' => $record->getId()) ));
    }

    /**
     * Creates a form to delete a Training entity by id.
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
