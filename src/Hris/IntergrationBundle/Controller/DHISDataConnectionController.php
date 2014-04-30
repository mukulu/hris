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
 * @author Wilfred Felix Senyoni <senyoni@gmail.com>
 *
 */
namespace Hris\IntergrationBundle\Controller;

use Hris\FormBundle\Entity\ResourceTable;
use Hris\IntergrationBundle\Entity\DataelementFieldOptionRelation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\IntergrationBundle\Entity\DHISDataConnection;
use Hris\IntergrationBundle\Form\DHISDataConnectionType;
use Symfony\Component\Filesystem\Filesystem;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use ZipArchive;

/**
 * DHISDataConnection controller.
 *
 * @Route("/dhisdataconnection")
 */
class DHISDataConnectionController extends Controller
{

    /**
     * Lists all DHISDataConnection entities.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_DHISDATACONNECTION_LIST")
     * @Route("/", name="dhisdataconnection")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisIntergrationBundle:DHISDataConnection')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new DHISDataConnection entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_DHISDATACONNECTION_CREATE")
     * @Route("/", name="dhisdataconnection_create")
     * @Method("POST")
     * @Template("HrisIntergrationBundle:DHISDataConnection:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new DHISDataConnection();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dhisdataconnection_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a DHISDataConnection entity.
    *
    * @param DHISDataConnection $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(DHISDataConnection $entity)
    {
        $form = $this->createForm(new DHISDataConnectionType(), $entity, array(
            'action' => $this->generateUrl('dhisdataconnection_create'),
            'method' => 'POST',
            'em'=>$this->getDoctrine()->getManager(),
        ));

        $form->add('submit', 'submit', array('attr' => array('class' => 'btn'),'label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new DHISDataConnection entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_DHISDATACONNECTION_CREATE")
     * @Route("/new", name="dhisdataconnection_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new DHISDataConnection();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a DHISDataConnection entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_DHISDATACONNECTION_SHOW")
     * @Route("/{id}", name="dhisdataconnection_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisIntergrationBundle:DHISDataConnection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DHISDataConnection entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing DHISDataConnection entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_DHISDATACONNECTION_UPDATE")
     * @Route("/{id}/edit", name="dhisdataconnection_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisIntergrationBundle:DHISDataConnection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DHISDataConnection entity.');
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
    * Creates a form to edit a DHISDataConnection entity.
    *
    * @param DHISDataConnection $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(DHISDataConnection $entity)
    {
        $form = $this->createForm(new DHISDataConnectionType(), $entity, array(
            'action' => $this->generateUrl('dhisdataconnection_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'em'=>$this->getDoctrine()->getManager(),
        ));

        $form->add('submit', 'submit', array('attr' => array('class' => 'btn'),'label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing DHISDataConnection entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_DHISDATACONNECTION_UPDATE")
     * @Route("/{id}", name="dhisdataconnection_update")
     * @Method("PUT")
     * @Template("HrisIntergrationBundle:DHISDataConnection:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisIntergrationBundle:DHISDataConnection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DHISDataConnection entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('dhisdataconnection_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Persists Dataelement Field Option relation and return json response on success.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_DHISDATACONNECTION_UPDATERELATION")
     * @Route("/updateRelation.{_format}", requirements={"_format"="yml|xml|json"}, defaults={"_format"="json"}, name="dhisdataconnection_updaterelation")
     * @Method("POST")
     * @Template()
     */
    public function updateRelationAction($_format)
    {
        $em = $this->getDoctrine()->getManager();
        $connectionId = $this->getRequest()->request->get('connectionId');
        $dhisDataelementUids = $this->getRequest()->request->get('dhisDataelementUids');
        $dhisDataelementNames = $this->getRequest()->request->get('dhisDataelementNames');
        $dhisComboUids = $this->getRequest()->request->get('dhisComboUids');
        $dhisComboNames = $this->getRequest()->request->get('dhisComboNames');
        $fieldOptionTargetNodes = NULL;

        // Fetch existing targets and field options belonging to target
        $entity = $em->getRepository('HrisIntergrationBundle:DHISDataConnection')->find($connectionId);

        //Get rid of current fields
        $em->createQueryBuilder('dataelementFieldOptionRelation')
            ->delete('HrisIntergrationBundle:DataelementFieldOptionRelation','dataelementFieldOptionRelation')
            ->where('dataelementFieldOptionRelation.dhisDataConnection= :dhisDataConnection')
            ->andWhere('dataelementFieldOptionRelation.dataelementUid= :dataelementUid')
            ->andWhere('dataelementFieldOptionRelation.dataelementname= :dataelementname')
            ->andWhere('dataelementFieldOptionRelation.categoryComboUid= :categoryComboUid')
            ->andWhere('dataelementFieldOptionRelation.categoryComboname= :categoryComboname')
            ->setParameters(array('dhisDataConnection'=>$entity,'dataelementUid'=>$dhisDataelementUids,'categoryComboUid'=>$dhisComboUids,'categoryComboname'=>$dhisComboNames,'dataelementname'=>$dhisDataelementNames))
            ->getQuery()->getResult();
        $em->flush();
        $columnFieldOptionGroup = $em->getRepository('HrisFormBundle:FieldOptionGroup')->findOneBy(array('name'=>$dhisDataelementNames));
        $rowFieldOptionGroup = $em->getRepository('HrisFormBundle:FieldOptionGroup')->findOneBy(array('name'=>$dhisComboNames));
        if(sizeof($columnFieldOptionGroup) && sizeof($rowFieldOptionGroup)) {
            //Insert relation
            $dataelementFieldOptionRelation = new DataelementFieldOptionRelation();
            $dataelementFieldOptionRelation->setDhisDataConnection($entity);
            $dataelementFieldOptionRelation->setCategoryComboname($dhisComboNames);
            $dataelementFieldOptionRelation->setCategoryComboUid($dhisComboUids);
            $dataelementFieldOptionRelation->setDataelementname($dhisDataelementNames);
            $dataelementFieldOptionRelation->setDataelementUid($dhisDataelementUids);
            $dataelementFieldOptionRelation->setColumnFieldOptionGroup($columnFieldOptionGroup);
            $dataelementFieldOptionRelation->setRowFieldOptionGroup($rowFieldOptionGroup);

            $result = NULL;

            try{
                $em->persist($dataelementFieldOptionRelation);
                $em->flush();
                $result= 'success';

            } catch(ORMException $e){
                $result= 'failed';
            }
        }else {
            $result= 'failed';
        }

        $serializer = $this->container->get('serializer');

        return array(
            'entities' => $serializer->serialize($result,$_format)
        );
    }

    /**
     * Deletes a DHISDataConnection entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_DHISDATACONNECTION_DELETE")
     * @Route("/{id}", name="dhisdataconnection_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisIntergrationBundle:DHISDataConnection')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DHISDataConnection entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('dhisdataconnection'));
    }

    /**
     * Displays dataset html forms for mapping hrhis fieldoption groups with dhis
     * dataelement and combooption.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_DHISDATACONNECTION_SHOWMAPPING")
     * @Route("/mapping/{id}", name="dhisdataconnection_showmapping")
     * @Method("GET")
     * @Template()
     */
    public function showMappingAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisIntergrationBundle:DHISDataConnection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DHISDataConnection entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays interface for Syncing HRH data with DHIS2 Dataset aggregated values.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_DHISDATACONNECTION_SYNCDATA")
     * @Route("/syncdata/{id}", name="dhisdataconnection_syncdata")
     * @Method("GET")
     * @Template()
     */
    public function syncDataAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisIntergrationBundle:DHISDataConnection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DHISDataConnection entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Generates export file with DHIS2 Dataset aggregated values.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_DHISDATACONNECTION_SYNCDATA")
     * @Route("/syncdata/{id}/aggregation.{_format}", requirements={"_format"="yml|xml|json"}, defaults={"_format"="xml"}, name="dhisdataconnection_aggregation")
     * @Method("POST")
     * @Template()
     */
    public function aggregationAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisIntergrationBundle:DHISDataConnection')->find($id);



        /*
         * Initializing query for dhis dataset calculation
         */
        // Get Standard Resource table name
        $resourceTableName = str_replace(' ','_',trim(strtolower( ResourceTable::getStandardResourceTableName())));
        $resourceTableAlias="ResourceTable";
        $organisationUnitJoinClause=" INNER JOIN hris_organisationunit as Organisationunit ON Organisationunit.id = $resourceTableAlias.organisationunit_id
                                            INNER JOIN hris_organisationunitstructure AS Structure ON Structure.organisationunit_id = $resourceTableAlias.organisationunit_id ";

        $joinClause = $organisationUnitJoinClause;
        $fromClause=" FROM $resourceTableName $resourceTableAlias ";

        // Clause for filtering target organisationunits
        $organisationunitId = $entity->getParentOrganisationunit()->getId();
        // With Lower Levels
        $organisationunit = $this->getDoctrine()->getManager()->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($organisationunitId);
        $organisationunitLevelsWhereClause = " Structure.level".$organisationunit->getOrganisationunitStructure()->getLevel()->getLevel()."_id=$organisationunitId AND Structure.level_id >= ( SELECT hris_organisationunitlevel.level FROM hris_organisationunitstructure INNER JOIN hris_organisationunitlevel ON hris_organisationunitstructure.level_id=hris_organisationunitlevel.id  WHERE hris_organisationunitstructure.organisationunit_id=$organisationunitId ) ";

        // Query for Options to exclude from reports
        $fieldOptionsToSkip = $this->getDoctrine()->getManager()->getRepository('HrisFormBundle:FieldOption')->findBy (array('skipInReport' =>True));
        //filter the records with exclude report tag
        foreach($fieldOptionsToSkip as $key => $fieldOptionToSkip){
            if(empty($fieldOptionsToSkipQuery)) {
                $fieldOptionsToSkipQuery = "$resourceTableAlias.".$fieldOptionToSkip->getField()->getName()." !='".$fieldOptionToSkip->getValue()."'";
            }else {
                $fieldOptionsToSkipQuery .= " AND $resourceTableAlias.".$fieldOptionToSkip->getField()->getName()." !='".$fieldOptionToSkip->getValue()."'";
            }
        }
        $xmlContents = "<?xml version='1.0' encoding='UTF-8'?>
<dataValueSet xmlns=\"http://dhis2.org/schema/dxf/2.0\">";

        // Dataelement field option relation
        $dataelementFieldOptionRelation = $entity->getDataelementFieldOptionRelation();
        foreach($dataelementFieldOptionRelation as $dataelementFieldOptionKey=>$dataelementFieldOptionValue) {
            // Formulate Query for calculating field option
            $columnFieldOptionGroup = $dataelementFieldOptionValue->getColumnFieldOptionGroup();
            $rowFieldOptionGroup = $dataelementFieldOptionValue->getRowFieldOptionGroup();

            $seriesFieldName=$rowFieldOptionGroup->getName();

            //Column Query construction
            $queryColumnNames[] = str_replace('-','_',str_replace(' ','',$columnFieldOptionGroup->getName()));
            $categoryColumnFieldNames[] = $columnFieldOptionGroup->getField()->getName();
            $categoryRowFieldName = $columnFieldOptionGroup->getField()->getName();
            $columnWhereClause = NULL;

            foreach($columnFieldOptionGroup->getFieldOption() as $columnFieldOptionKey=>$columnFieldOption) {
                $operator = $columnFieldOptionGroup->getOperator();
                if(empty($operator)) $operator = "OR";
                $categoryColumnFieldOptionValue=str_replace('-','_',$columnFieldOption->getValue());
                $categoryColumnFieldName=$columnFieldOption->getField()->getName();
                $categoryColumnResourceTableName=$resourceTableAlias;
                if(!empty($columnWhereClause)) {
                    $columnWhereClause = $columnWhereClause." ".strtoupper($operator)." $categoryColumnResourceTableName.$categoryColumnFieldName='".$categoryColumnFieldOptionValue."'";
                }else {
                    $columnWhereClause = "$categoryColumnResourceTableName.$categoryColumnFieldName='".$categoryColumnFieldOptionValue."'";
                }

            }
            $rowWhereClause = NULL;
            foreach($rowFieldOptionGroup->getFieldOption() as $rowFieldOptionKey=>$rowFieldOption) {
                $operator = $rowFieldOptionGroup->getOperator();
                if(empty($operator)) $operator = "OR";
                $categoryRowFieldOptionValue=str_replace('-','_',$rowFieldOption->getValue());
                $categoryRowFieldName=$rowFieldOption->getField()->getName();
                $categoryRowResourceTableName=$resourceTableAlias;
                if(!empty($rowWhereClause)) {
                    $rowWhereClause = $rowWhereClause." ".strtoupper($operator)." $categoryRowResourceTableName.$categoryRowFieldName='".$categoryRowFieldOptionValue."'";
                }else {
                    $rowWhereClause = "$categoryRowResourceTableName.$categoryRowFieldName='".$categoryRowFieldOptionValue."'";
                }
            }

            $selectQuery="SELECT COUNT(DISTINCT(instance)) $fromClause $joinClause WHERE ($rowWhereClause) AND ($columnWhereClause) AND $organisationunitLevelsWhereClause".( !empty($fieldOptionsToSkipQuery) ? " AND ( $fieldOptionsToSkipQuery )" : "" );
            $instanceCount = $this->array_value_recursive('count',$this->getDoctrine()->getManager()->getConnection()->fetchAll($selectQuery));
            $xmlContents = $xmlContents.'<dataValue dataElement="'.$dataelementFieldOptionValue->getDataelementUid().'" period="'.date("Y").'" orgUnit="'.$organisationunit->getDhisUid().'" categoryOptionCombo="'.$dataelementFieldOptionValue->getCategoryComboUid().'" value="'.$instanceCount.'" storedBy="hrhis" lastUpdated="'.date("c").'" followUp="false" />';
        }
        $xmlContents = $xmlContents.'</dataValueSet>';

        // Initializing export file
        $fileSystem = new Filesystem();
        $exportFileName = "Export_".date("Y_m_d_His").".zip";
        $exportArchive = new ZipArchive();
        $exportArchive->open("/tmp/".$exportFileName,ZipArchive::CREATE);
        $exportArchive->addFromString("Export_".date("Y_m_d_His").'xml',$xmlContents);
        $exportArchive->close();
        $fileSystem->chmod("/tmp/".$exportFileName,0666);
        $response = new Response(file_get_contents("/tmp/".$exportFileName));
        $d = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $exportFileName);
        $response->headers->set('Content-Disposition', $d);

        unlink("/tmp/".$exportFileName);

        $result = $xmlContents;
        return array(
            'result'      => $result,
        );
    }

    /**
     * Creates a form to delete a DHISDataConnection entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('dhisdataconnection_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('attr' => array('class' => 'btn'),'label' => 'Delete'))
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
