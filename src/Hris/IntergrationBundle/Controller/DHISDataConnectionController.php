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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\IntergrationBundle\Entity\DHISDataConnection;
use Hris\IntergrationBundle\Form\DHISDataConnectionType;
use JMS\SecurityExtraBundle\Annotation\Secure;

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
            ->andWhere('dataelementFieldOptionRelation.categoryComboUid= :categoryComboUid')
            ->setParameters(array('dhisDataConnection'=>$entity,'dataelementUid'=>$dhisDataelementUids,'categoryComboUid'=>$dhisComboUids))
            ->getQuery()->getResult();
        $em->flush();

//        if(!empty($connectionId) && !empty($dhisDataelementUids)) {
//            $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
//            $targetFieldOptions = $queryBuilder->select('targetFieldOption')
//                ->from('HrisIndicatorBundle:TargetFieldOption','targetFieldOption')
//                ->join('targetFieldOption.fieldOption','fieldOption')
//                ->join('fieldOption.field','field')
//                ->where('targetFieldOption.target=:targetid')
//                ->andWhere('field.id=:fieldid')
//                ->setParameters(array('targetid'=>$targetid,'fieldid'=>$fieldid))
//                ->getQuery()->getResult();
//            if(!empty($targetFieldOptions)) {
//                foreach($targetFieldOptions as $targetFieldOptionKey=>$targetFieldOption) {
//                    $fieldOptionTargetNodes[$targetFieldOption->getFieldOption()->getId()] = Array(
//                        'name' => $targetFieldOption->getFieldOption()->getValue(),
//                        'id' => $targetFieldOption->getFieldOption()->getId(),
//                        'value' => $targetFieldOption->getValue()
//                    );
//                }
//            }
//        }
//        foreach($fieldOptions as $fieldOptionKey=>$fieldOption) {
//            if(!isset($fieldOptionTargetNodes[$fieldOption->getId()])) {
//                $fieldOptionTargetNodes[] = Array(
//                    'name' => $fieldOption->getValue(),
//                    'id' => $fieldOption->getId(),
//                    'value' => ''
//                );
//            }
//        }

        $serializer = $this->container->get('serializer');

        return array(
            'entities' => $serializer->serialize($fieldOptionTargetNodes,$_format)
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
}
