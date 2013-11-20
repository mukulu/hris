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
 * @author Ismail Yusuf Koleleni <ismailkoleleni@gmail.com>
 *
 */
namespace Hris\RecordsBundle\Controller;

use Hris\FormBundle\Entity\Field;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\RecordsBundle\Entity\History;
use Hris\RecordsBundle\Form\HistoryType;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * History controller.
 *
 * @Route("/history")
 */
class HistoryController extends Controller
{

    /**
     * Lists all History entities.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_RECORDHISTORY_LIST")
     * @Route("/", name="history")
     * @Route("/list", name="history_list")
     * @Route("/list/{recordid}/", requirements={"recordid"="\d+"}, name="history_list_byrecord")
     * @Method("GET")
     * @Template()
     */
    public function indexAction( $recordid=NULL )
    {
        $em = $this->getDoctrine()->getManager();

        if(!empty($recordid)){
            $entities = $em->getRepository('HrisRecordsBundle:History')->findBy(array('record'=>$recordid));
            $record = $em->getRepository('HrisRecordsBundle:Record')->findOneBy(array('id'=>$recordid));
        }

        //$entities = $em->getRepository('HrisRecordsBundle:History')->findAll();
        foreach($entities as $entity) {
            $delete_form= $this->createDeleteForm($entity->getId());
            $delete_forms[$entity->getId()] = $delete_form->createView();
        }

        return array(
            'entities' => $entities,
            'delete_forms' => $delete_forms,
            'recordid' => $recordid,
            'record' => $record,
            'employeeName' => $this->getEmployeeName($recordid),
        );
    }
    /**
     * Creates a new History entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_RECORDHISTORY_CREATE")
     * @Route("/{recordid}/recordid", requirements={"recordid"="\d+"}, name="history_create")
     * @Method("POST")
     * @Template("HrisRecordsBundle:History:new.html.twig")
     */
    public function createAction(Request $request, $recordid = NULL)
    {
        $entity  = new History();
        $form = $this->createForm(new HistoryType(), $entity);
        $form->bind($request);
        $user = $this->container->get('security.context')->getToken()->getUser();

        if ($form->isValid()) {
            //echo "Atleast im here";exit;
            $em = $this->getDoctrine()->getManager();
            $historyValue = $request->request->get('hris_recordsbundle_history');
            $historyFormData = $request->request->get('hris_recordsbundle_historytype');

            //Check if history is orgunit transfer or not
            if( $historyFormData['hris_recordsbundle_historytype_field'] == 0){

                //echo "Im tryn to transfer";exit;
                $orgunit = $this->getDoctrine()->getManager()->getRepository('OrganisationunitBundle:Organisationunit')->findOneBy(array('uid' =>$historyValue['history'] ));
                if(!empty($recordid)) {
                    $record = $this->getDoctrine()->getManager()->getRepository('HrisRecordsBundle:Record')->findOneBy(array('id'=>$recordid));
                    //$field = $this->getDoctrine()->getManager()->getRepository('HrisFormBundle:Field')->findOneBy(array('id'=>$historyFormData['field']));
                    $field = new Field();

                    //If History Set to update record
                    if($historyFormData['updaterecord']){

                        //Get Previous value before updating
                        $recordOrgUnit = $record->getOrganisationunit();

                        //Assign Record with the new orgunit
                        $record->setOrganisationunit($orgunit);

                        //Assign old orgunit from records to history table
                        $entity->setHistory($recordOrgUnit->getLongname());
                        $entity->setReason($historyFormData['reason']." Note: This is previous Organisation Unit held before changed to ".$orgunit->getLongname().".");
                    }
                    else{
                        //Set History value
                        $entity->setHistory($orgunit->getLongname());
                    }
                    $entity->setRecord($record);
                }
                else {
                    $record = NULL;
                    $entity->setRecord($record);
                }
            }
            else{

                //If history is not orgunit transfer
                $fieldOption = $this->getDoctrine()->getManager()->getRepository('HrisFormBundle:FieldOption')->findOneBy(array('uid'=>$historyValue['history']));
                if(!empty($recordid)) {
                    $record = $this->getDoctrine()->getManager()->getRepository('HrisRecordsBundle:Record')->findOneBy(array('id'=>$recordid));
                    $field = $this->getDoctrine()->getManager()->getRepository('HrisFormBundle:Field')->findOneBy(array('id'=>$historyFormData['field']));

                    //If History Set to update record
                    if($historyFormData['updaterecord']){

                        $recordValue = $record->getValue();
                        //Get Previous value before updating
                        $previousValue = $recordValue[$field->getUid()];
                        //Assign Record with the new update uid
                        $recordValue[$field->getUid()] = $historyValue['history'];

                        //Assign old value from records to history table
                        $previousOption = $this->getDoctrine()->getManager()->getRepository('HrisFormBundle:FieldOption')->findOneBy(array('uid'=>$previousValue));
                        $entity->setHistory($previousOption->getValue());
                        $entity->setReason($historyFormData['reason']." Note: This is previous ".$field->getCaption()." held before changed to ".$fieldOption->getValue().".");

                        //Update new record value
                        $record->setValue($recordValue);
                    }
                    else{
                        //Set History value
                            $entity->setHistory($fieldOption->getValue());
                    }
                    $entity->setRecord($record);
                }
                else {
                    $record = NULL;
                    $entity->setRecord($record);
                }
            }


            $entity->setUsername($user->getUsername());

            //Update Record Table hasHistory column
            $record->setHashistory(true);

            $em->persist($entity);
            $em->persist($record);
            $em->flush();

            return $this->redirect($this->generateUrl('history_list_byrecord', array( 'recordid' => $recordid )));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'recordid' => $recordid,
            'employeeName' => $this->getEmployeeName($recordid),
            'removeFields' => null,
        );
    }

    /**
     * Displays a form to create a new History entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_RECORDHISTORY_CREATE")
     * @Route("/new/{recordid}/recordid", requirements={"recordid"="\d+"}, name="history_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction( $recordid=NULL )
    {
        $entity = new History();
        $form   = $this->createForm(new HistoryType(), $entity);

        if(!empty($recordid)) {
            $record = $this->getDoctrine()->getManager()->getRepository('HrisRecordsBundle:Record')->findOneBy(array('id'=>$recordid));
        }else {
            $record = NULL;
        }

        //Get all fields not associated with approptiate form
        $entityManager = $this->getDoctrine()->getManager();
        $subQuery = "SELECT field_id ";
        $subQuery .= "FROM hris_form_fieldmembers ";
        $subQuery .= "WHERE form_id = ".$record->getForm()->getId();
        $query = "SELECT Field.id ";
        $query .= "FROM hris_field Field ";
        $query .= "FULL OUTER JOIN hris_form_fieldmembers as member on member.field_id = Field.id ";
        $query .= "FULL OUTER JOIN hris_form as Form on Form.id = member.form_id ";
        $query .= "WHERE ( Field.hashistory = true ";
        $query .= "AND Field.id NOT IN ( ".$subQuery." )) ";
        $query .= "OR Field.iscalculated = true";
        $results = $entityManager -> getConnection() -> executeQuery($query) -> fetchAll();
        $removeFields = $this->array_value_recursive('id' , $results);


        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'recordid' => $recordid,
            'record' => $record,
            'employeeName' => $this->getEmployeeName($recordid),
            'removeFields' => $removeFields,
        );
    }

    /**
     * Returns Employee's Full Name
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_RECORDHISTORY_SHOWEMPLOYEENAME")
     * @Method("GET")
     * @Template()
     */
    private function getEmployeeName($recordid)
    {
        $entityManager = $this->getDoctrine()->getManager();

        if(!empty($recordid)) {
            $record = $this->getDoctrine()->getManager()->getRepository('HrisRecordsBundle:Record')->findOneBy(array('id'=>$recordid));
        }else {
            $record = NULL;
        }
        $resourceTableName = "_resource_all_fields";
        $query = "SELECT firstname, middlename, surname FROM ".$resourceTableName;
        $query .= " WHERE instance = '".$record->getInstance()."' ";

        $result = $entityManager -> getConnection() -> executeQuery($query) -> fetchAll();
        if(!empty($result)){
            return $result[0]['firstname']." ".$result[0]['middlename']." ".$result[0]['surname'];
        }else{
            return "Employee";
        }

    }


    /**
     * Finds and displays a History entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_RECORDHISTORY_SHOW")
     * @Route("/{id}", requirements={"id"="\d+"}, requirements={"id"="\d+"}, name="history_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisRecordsBundle:History')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find History entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing History entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_RECORDHISTORY_UPDATE")
     * @Route("/{id}/edit", requirements={"id"="\d+"}, name="history_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisRecordsBundle:History')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find History entity.');
        }

        $editForm = $this->createForm(new HistoryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'employeeName' => $this->getEmployeeName($entity->getRecord()->getId()),
        );
    }

    /**
     * Edits an existing History entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_RECORDHISTORY_UPDATE")
     * @Route("/{id}", requirements={"id"="\d+"}, name="history_update")
     * @Method("PUT")
     * @Template("HrisRecordsBundle:History:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisRecordsBundle:History')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find History entity.');
        }
        //Get Field before bind since field is disabled
        $field = $entity->getField();

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new HistoryType(), $entity);
        $editForm->bind($request);
        $user = $this->container->get('security.context')->getToken()->getUser();
        $recordid = $entity->getRecord()->getId();
        $record = $entity->getRecord();


        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $historyValue = $request->request->get('hris_recordsbundle_history');
            $historyFormData = $request->request->get('hris_recordsbundle_historytype');
            $fieldOption = $this->getDoctrine()->getManager()->getRepository('HrisFormBundle:FieldOption')->findOneBy(array('uid'=>$historyValue['history']));

                //If History Set to update record
                if($historyFormData['updaterecord']){
                    $recordValue = $record->getValue();
                    //Get Previous value before updating
                    $previousValue = $recordValue[$field->getUid()];
                    //Assign Record with the new update uid
                    $recordValue[$field->getUid()] = $historyValue['history'];

                    //Assign old value from records to history table
                    $previousOption = $this->getDoctrine()->getManager()->getRepository('HrisFormBundle:FieldOption')->findOneBy(array('uid'=>$previousValue));
                    $entity->setHistory($previousOption->getValue());
                    $entity->setReason($historyFormData['reason']." Note: This is previous ".$field->getCaption()." held before changed to ".$fieldOption->getValue().".");

                    //Update new record value
                    $record->setValue($recordValue);
                }
                else{
                    //Set entity value assigned from the history form
                    $entity->setHistory($fieldOption->getValue());
                }
                $entity->setRecord($record);

            $entity->setField($field);
            $entity->setUsername($user->getUsername());
            $em->persist($entity);
            $em->persist($record);
            $em->flush();

            return $this->redirect($this->generateUrl('history_list_byrecord', array( 'recordid' => $recordid )));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'recordid' => $recordid,
            'record' => $record,
            'employeeName' => $this->getEmployeeName($recordid),
        );
    }
    /**
     * Deletes a History entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_RECORDHISTORY_DELETE")
     * @Route("/{id}", requirements={"id"="\d+"}, name="history_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisRecordsBundle:History')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find History entity.');
            }
            $record = $entity->getRecord();

            //check if this deleted entity is the last for this record
            $query = "SELECT count (id) as total ";
            $query .= " FROM hris_record_history H ";
            $query .= " WHERE record_id = ". $record->getId();
            $query .= " AND id <> ". $id;

            $result = $em -> getConnection() -> executeQuery($query) -> fetchAll();

            //Update records hasTraining column to false when no trainings will be left after delete
            if ( $result[0]['total'] == 0 ){
                $record->setHasHistory(false);
                $em->persist($record);
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('history_list_byrecord', array( 'recordid' => $record->getId()) ));
    }


    /**
     * Returns FieldOptions json.
     *
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_RECORDHISTORY_SHOWFIELDOPTION")
     * @Route("/historyFieldOption.{_format}", requirements={"_format"="yml|xml|json"}, defaults={"_format"="json"}, name="history_historyfieldption")
     * @Method("POST")
     * @Template()
     */
    public function historyFieldOptionAction($_format)
    {
        $em = $this->getDoctrine()->getManager();
        $fieldid = $this->getRequest()->request->get('fieldid');
        $fieldOptionTargetNodes = NULL;

        if ($fieldid == 0){
            $user = $this->container->get('security.context')->getToken()->getUser();
            $userOrgunitId = $user -> getOrganisationunit()->getId();
            $userOrgunit = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($userOrgunitId);
            $children = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->getAllChildren($userOrgunit);

            foreach($children as $ch => $child){

                $fieldOptionTargetNodes[] = Array(
                    'name' => $child[0]['longname'],
                    'uid' => $child[0]['uid'],
                );
            }
        }
        else{
            // Fetch existing targets and field options belonging to target
            $fieldOptions = $em->getRepository('HrisFormBundle:FieldOption')->findBy(array('field'=>$fieldid));

            foreach($fieldOptions as $fieldOptionKey => $fieldOption) {
                if(!isset($fieldOptionTargetNodes[$fieldOption->getId()])) {
                    $fieldOptionTargetNodes[] = Array(
                        'name' => $fieldOption->getValue(),
                        'uid' => $fieldOption->getUid()
                    );
                }
            }
        }

        $serializer = $this->container->get('serializer');

        return array(
            'entities' => $serializer->serialize($fieldOptionTargetNodes,$_format)
        );
    }


    /**
     * Creates a form to delete a History entity by id.
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
     * Get all values from specific key in a multidimensional array
     *
     * @param $key string
     * @param $arr array
     * @return null|string|array
     */
    public function array_value_recursive($key, array $arr){
        $val = array();
        array_walk_recursive($arr, function($v, $k) use($key, &$val){if($k == $key) array_push($val, $v);});
        return count($val) > 1 ? $val : array_pop($val);
    }
}
