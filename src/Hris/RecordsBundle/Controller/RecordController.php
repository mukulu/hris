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
use Doctrine\Tests\Common\Annotations\True;
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
use Hris\OrganisationunitBundle\Entity\Organisationunit;
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
        //var_dump($field_Option_entities);
        $field_Option_Values = json_encode($field_Option_entities);
        $filed_Option_Table_Name = json_encode($em->getClassMetadata('HrisFormBundle:FieldOption')->getTableName());

        /*
        * Getting the Organisation Unit Metadata and Values
        */

        $user = $this->container->get('security.context')->getToken()->getUser();

        if ($user->getOrganisationunit()->getOrganisationunitStructure()->getLevel()->getDataentrylevel()){
        $orgUnit = $em->getRepository( 'HrisOrganisationunitBundle:Organisationunit' )
            ->createQueryBuilder('o')
            ->select('o', 'p')
            ->join('o.parent', 'p')
            ->where('o.uid = :uid')
            ->orWhere('o.parent = :parent')
            ->setParameters(array('uid' => $user->getOrganisationunit()->getUid(), 'parent' => $user->getOrganisationunit()))
            ->getQuery()
            ->getArrayResult();

        }else{
            $orgUnit = $em->getRepository( 'HrisOrganisationunitBundle:Organisationunit' )
                ->createQueryBuilder('o')
                ->select('o')
                ->where('o.uid = :uid')
                ->setParameters(array('uid' => $user->getOrganisationunit()->getUid()))
                ->getQuery()
                ->getArrayResult();
        }
        $orgunit_Values = json_encode($orgUnit);
        $orgunit_table = json_encode($em->getClassMetadata('HrisOrganisationunitBundle:Organisationunit')->getTableName());

        /*
         * Field Options Associations
         */

        $field_Options = $em->getRepository( 'HrisFormBundle:FieldOption' )
        ->createQueryBuilder('o')
        ->select('o', 'f')
        ->join('o.field', 'f')
        ->getQuery()
        ->getResult();

        $id = 1;
        $fieldOptionAssocitiontablename = "field_option_association";
        foreach($field_Options as $key => $fieldOption){
            $option_associations =  $fieldOption->getChildFieldOption();
            if (!empty($option_associations) ){
                foreach($option_associations as $keyoption => $option){
                    //print "<br><br>the reference Key ". $fieldOption->getValue()." the referenced Field ".$option->getValue()." the reference Field ". $fieldOption->getField()->getName(). " the associate field ".$option->getField()->getName();
                    $fieldOptions[] = array( 'id' => $id++,'fieldoption'=>$fieldOption->getUid(), 'fielduid' => $fieldOption->getField()->getUid(), 'fieldoptionref' => $option->getValue(), 'fieldoptionrefuid' => $option->getUid(), 'fieldref'=>$option->getField()->getUid() );
                }
            }
        }

        //var_dump($fieldOptions);

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
            'option_associations_values' => json_encode($fieldOptions),
            'option_associations_table' => $fieldOptionAssocitiontablename,
            'organisation_Values' => $orgunit_Values,
            'organisation_unit_table' => $orgunit_table,
        );
    }

    /**
     * List Forms Available for Update Record.
     *
     * @Route("/formlistupdate", name="record_form_list_update")
     * @Method("GET")
     * @Template("HrisRecordsBundle:Record:formlistupdate.html.twig")
     */
    public function formlistupdateAction()
    {
        $em = $this->getDoctrine()->getManager();

        /*
         * Getting the Form Metadata and Values
         */

        $entities = $em->getRepository( 'HrisFormBundle:Form' )->createQueryBuilder('p')->getQuery()->getArrayResult();

        return array(
            'entities' => $entities,
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
        $em = $this->getDoctrine()->getManager();

        $entity  = new Record();
        //$record = $this->createForm(new RecordType(), $entity);
        //$record->bind($request);

        $formId = $this->get('request')->request->get('formId');

        $orgunit = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->find(1);

        $form = $em->getRepository('HrisFormBundle:Form')->find($formId);
        $uniqueFields = $form->getUniqueRecordFields();
        $fields = $form->getSimpleField();

        $instance = '';
        foreach($uniqueFields as $key => $field_unique){
            $instance .= $this->get('request')->request->get($field_unique->getName());
        }


        foreach ($fields as $key => $field){
            $recordValue = $this->get('request')->request->get($field->getName());

            /**
             * Made dynamic, on which field column is used as key, i.e. uid, name or id.
             */
            // Translates to $field->getUid()
            // or $field->getUid() depending on value of $recordKeyName
            $recordFieldKey = ucfirst(Record::getFieldKey());
            $valueKey = call_user_func_array(array($field, "get${recordFieldKey}"),array());

            $recordArray[$valueKey] = $recordValue;
        }

        $user = $this->container->get('security.context')->getToken()->getUser();

        $entity->setValue($recordArray);
        $entity->setForm($form);
        $entity->setInstance(md5(uniqid()));
        $entity->setOrganisationunit($orgunit);
        $entity->setUsername($user->getUsername());
        $entity->setComplete(True);
        $entity->setCorrect(True);
        $entity->setHashistory(False);
        $entity->setHastraining(False);



        //if ($entity->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        var_dump("this is done without any doubt");

        return $this->redirect($this->generateUrl('record_new', array('id' => $form->getId())));
        //}

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }


    /**
     * Displays a form to create a new Record entity.
     *
     * @Route("/new/{id}", requirements={"id"="\d+"}, name="record_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction( $id )
    {
        $em = $this->getDoctrine()->getManager();

        $formEntity = $em->getRepository('HrisFormBundle:Form')->find($id);
        $tableName = json_encode($em->getClassMetadata('HrisFormBundle:Form')->getTableName());

        $fields = $formEntity->getSimpleField();

        $user = $this->container->get('security.context')->getToken()->getUser();

        $orgUnit = $em->getRepository( 'HrisOrganisationunitBundle:Organisationunit' )
            ->createQueryBuilder('o')
            ->select('o')
            ->where('o.uid = :uid')
            ->setParameters(array('uid' => $user->getOrganisationunit()->getUid()))
            ->getQuery()
            ->getArrayResult();

        $isEntryLevel = $user->getOrganisationunit()->getOrganisationunitStructure()->getLevel()->getDataentrylevel();
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
            'entryLevel' => $isEntryLevel,
            'organisation_unit' => array_shift($orgUnit),
        );
    }

    /**
     * Finds and displays a Record entity.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="record_show")
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

    /**
     * Displays a form to edit an existing Record entity.
     *
     * @Route("/{id}/edit", requirements={"id"="\d+"}, name="record_edit")
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
     * @Route("/{id}", requirements={"id"="\d+"}, name="record_update")
     * @Method("PUT")
     * @Template("HrisRecordsBundle:Record:new.html.twig")
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
     * @Route("/{id}", requirements={"id"="\d+"}, name="record_delete")
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


}
