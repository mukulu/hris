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

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\QueryBuilder as QueryBuilder;
use FOS\UserBundle\Doctrine;
use Doctrine\ORM\Internal\Hydration\ObjectHydrator  as DoctrineHydrator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\RecordsBundle\Entity\Record;
use Hris\RecordsBundle\Form\RecordType;
use Doctrine\Common\Collections\ArrayCollection;
use Hris\FormBundle\Entity\Field;
use Hris\FormBundle\Form\FormType;
use Hris\FormBundle\Form\DesignFormType;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * Record controller.
 *
 * @Route("/record")
 */
class RecordController extends Controller
{

    /**
     * Lists all Record entities.
     *
     * @Route("/", name="record")
     * @Route("/list", name="record_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisRecordsBundle:Record')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    
    /**
     * List Forms Available for Record entry.
     *
     * @Route("/formlist", name="record_form_list")
     * @Method("GET")
     * @Template()
     */
    public function formlistAction()
    {
        $em = $this->getDoctrine()->getManager();

        /*
         * Getting the Form Metadata and Values
         */
        $entities = $em->getRepository( 'HrisFormBundle:Form' )->createQueryBuilder('p')->getQuery()->getArrayResult();

        $form_Column_Names = json_encode($em->getClassMetadata('HrisFormBundle:Form')->getFieldNames());
        $form_Table_Name = json_encode($em->getClassMetadata('HrisFormBundle:Form')->getTableName());
        $dataValues = json_encode($entities);

        $tables = $em->getMetadataFactory()->getAllMetadata();
        foreach($tables as $classMetadata) {
            $tableArray[] = $classMetadata->table['name'];
        }

        /*
        * Getting the Field Metadata and Values
        */
        $field_entities = $em->getRepository( 'HrisFormBundle:Field' )
            ->createQueryBuilder('f')
            ->select('f', 'd', 'i')
            ->join('f.dataType', 'd')
            ->join('f.inputType', 'i')
            ->getQuery()
            ->getArrayResult();

        $field_Column_Names = json_encode($em->getClassMetadata('HrisFormBundle:Field')->getFieldNames());
        $filed_Table_Name = json_encode($em->getClassMetadata('HrisFormBundle:Field')->getTableName());
        $filed_Values = json_encode($field_entities);

        /*
        * Getting the Field Options Metadata and Values
        */
        $field_Option_entities = $em->getRepository( 'HrisFormBundle:FieldOption' )
            ->createQueryBuilder('o')
            ->select('o', 'f')
            ->join('o.field', 'f')
            ->getQuery()
            ->getArrayResult();

        $field_Option_Values = json_encode($field_Option_entities);
        $filed_Option_Table_Name = json_encode($em->getClassMetadata('HrisFormBundle:FieldOption')->getTableName());

        return array(
            'entities' => $entities,
            'column_names' => $form_Column_Names,
            'table_names' => json_encode($tableArray),
            'table_name' => $form_Table_Name,
            'data_values' => $dataValues,
            'field_column_names' => $field_Column_Names,
            'field_table_name' => $filed_Table_Name,
            'field_values' => $filed_Values,
            'field_option_values' => $field_Option_Values,
            'field_option_table_name' => $filed_Option_Table_Name,
        );
    }
    
    /**
     * Creates a new Record entity.
     *
     * @Route("/", name="record_create")
     * @Method("POST")
     * @Template("HrisRecordsBundle:Record:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Record();
        $form = $this->createForm(new RecordType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('record_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Record entity.
     *
     * @Route("/new/{id}", name="record_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction( $id )
    {
        $em = $this->getDoctrine()->getManager();

        $formEntity = $em->getRepository('HrisFormBundle:Form')->find($id);
        $tableName = json_encode($em->getClassMetadata('HrisFormBundle:Form')->getTableName());

        $fields = $formEntity->getSimpleField();

        $selectFields = array();
        $key = NULL;

        foreach ($fields as $key => $field){
            if($field->getInputType()->getName() == 'Select'){
                $selectFields[] = $field->getUid();
            }
        }

        return array(

            'uid' => $formEntity->getUid(),
            'title' => $formEntity->getTitle(),
            'id' => $formEntity->getId(),
            'table_name' => $tableName,
            'fields' => json_encode($selectFields),
        );
    }

    /**
     * Finds and displays a Record entity.
     *
     * @Route("/{id}", name="record_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisRecordsBundle:Record')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Record entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Record entity.
     *
     * @Route("/{id}/edit", name="record_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisRecordsBundle:Record')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Record entity.');
        }

        $editForm = $this->createForm(new RecordType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Record entity.
     *
     * @Route("/{id}", name="record_update")
     * @Method("PUT")
     * @Template("HrisRecordsBundle:Record:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisRecordsBundle:Record')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Record entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new RecordType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('record_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Record entity.
     *
     * @Route("/{id}", name="record_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisRecordsBundle:Record')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Record entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('record'));
    }

    /**
     * Creates a form to delete a Record entity by id.
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
