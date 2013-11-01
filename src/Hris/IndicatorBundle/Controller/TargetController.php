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
 * @author Ismail Y. Koleleni <ismailkoleleni@gmail.com>
 *
 */
namespace Hris\IndicatorBundle\Controller;

use Hris\IndicatorBundle\Entity\TargetFieldOption;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Hris\IndicatorBundle\Entity\Target;
use Hris\IndicatorBundle\Form\TargetType;


/**
 * Target controller.
 *
 * @Route("/target")
 */
class TargetController extends Controller
{

    /**
     * Lists all Target entities.
     *
     * @Secure(roles="ROLE_TARGET_LIST")
     * @Route("/", name="target")
     * @Route("/list", name="target_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisIndicatorBundle:Target')->findAll();
        $delete_forms=NULL;
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
     * Creates a new Target entity.
     *
     * @Secure(roles="ROLE_TARGET_CREATE")
     * @Route("/", name="target_create")
     * @Method("POST")
     * @Template("HrisIndicatorBundle:Target:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Target();
        $form = $this->createForm(new TargetType(), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // Persist completeness figures too
            $targettypeform = $request->request->get('hris_indicatorbundle_targettype');
            $fieldOptions = $this->getDoctrine()->getManager()->getRepository('HrisFormBundle:FieldOption')->findBy(array('field'=>$targettypeform['fields']));
            $fieldOptionTargets = $request->request->get('hris_indicatorbundle_targettype_fieldoptiontarget');
            foreach($fieldOptions as $fieldOptionKey=>$fieldOption) {
                if(isset($fieldOptionTargets[$fieldOption->getId()]) && !empty($fieldOptionTargets[$fieldOption->getId()])) {
                    $fieldOptionTarget = new TargetFieldOption();
                    $fieldOptionTarget->setFieldOption($fieldOption);
                    $fieldOptionTarget->setTarget($entity);
                    $fieldOptionTarget->setValue((int)$fieldOptionTargets[$fieldOption->getId()]);
                    $entity->addTargetFieldOption($fieldOptionTarget);
                    unset($fieldOptionTarget);
                }
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('target_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Target entity.
     *
     * @Secure(roles="ROLE_TARGET_CREATE")
     * @Route("/new", name="target_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Target();
        $form   = $this->createForm(new TargetType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Target entity.
     *
     * @Secure(roles="ROLE_TARGET_SHOW")
     * @Route("/{id}", requirements={"id"="\d+"}, name="target_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisIndicatorBundle:Target')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Target entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Target entity.
     *
     * @Secure(roles="ROLE_TARGET_UPDATE")
     * @Route("/{id}/edit", name="target_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisIndicatorBundle:Target')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Target entity.');
        }

        $editForm = $this->createForm(new TargetType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Target entity.
     *
     * @Secure(roles="ROLE_TARGET_UPDATE")
     * @Route("/{id}", name="target_update")
     * @Method("PUT")
     * @Template("HrisIndicatorBundle:Target:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisIndicatorBundle:Target')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Target entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new TargetType(), $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            //Get rid of current expectations
            $em->createQueryBuilder('targetFieldOption')
                ->delete('HrisIndicatorBundle:TargetFieldOption','targetFieldOption')
                ->where('targetFieldOption.target= :targetId')
                ->setParameter('targetId',$entity->getId())
                ->getQuery()->getResult();
            $em->flush();

            // Persist completeness figures too
            $targettypeform = $request->request->get('hris_indicatorbundle_targettype');
            $fieldOptions = $this->getDoctrine()->getManager()->getRepository('HrisFormBundle:FieldOption')->findBy(array('field'=>$targettypeform['fields']));
            $fieldOptionTargets = $request->request->get('hris_indicatorbundle_targettype_fieldoptiontarget');
            foreach($fieldOptions as $fieldOptionKey=>$fieldOption) {
                if(isset($fieldOptionTargets[$fieldOption->getId()]) && !empty($fieldOptionTargets[$fieldOption->getId()])) {
                    $fieldOptionTarget = new TargetFieldOption();
                    $fieldOptionTarget->setFieldOption($fieldOption);
                    $fieldOptionTarget->setTarget($entity);
                    $fieldOptionTarget->setValue((int)$fieldOptionTargets[$fieldOption->getId()]);
                    $entity->addTargetFieldOption($fieldOptionTarget);
                    unset($fieldOptionTarget);
                }
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('target_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Returns TargetFieldOptions json.
     *
     * @Secure(roles="ROLE_TARGET_LISTFIELDOPTIONS")
     * @Route("/targetFieldOption.{_format}", requirements={"_format"="yml|xml|json"}, defaults={"_format"="json"}, name="target_targetfieldption")
     * @Method("POST")
     * @Template()
     */
    public function targetFieldOptionAction($_format)
    {
        $em = $this->getDoctrine()->getManager();
        $fieldid = $this->getRequest()->request->get('fieldid');
        $targetid = $this->getRequest()->request->get('targetid');
        $fieldOptionTargetNodes = NULL;

        // Fetch existing targets and field options belonging to target
        $fieldOptions = $em->getRepository('HrisFormBundle:FieldOption')->findBy(array('field'=>$fieldid));

        if(!empty($targetid) && !empty($fieldid)) {
            $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
            $targetFieldOptions = $queryBuilder->select('targetFieldOption')
                ->from('HrisIndicatorBundle:TargetFieldOption','targetFieldOption')
                ->join('targetFieldOption.fieldOption','fieldOption')
                ->join('fieldOption.field','field')
                ->where('targetFieldOption.target=:targetid')
                ->andWhere('field.id=:fieldid')
                ->setParameters(array('targetid'=>$targetid,'fieldid'=>$fieldid))
                ->getQuery()->getResult();
            if(!empty($targetFieldOptions)) {
                foreach($targetFieldOptions as $targetFieldOptionKey=>$targetFieldOption) {
                    $fieldOptionTargetNodes[$targetFieldOption->getFieldOption()->getId()] = Array(
                        'name' => $targetFieldOption->getFieldOption()->getValue(),
                        'id' => $targetFieldOption->getFieldOption()->getId(),
                        'value' => $targetFieldOption->getValue()
                    );
                }
            }
        }
        foreach($fieldOptions as $fieldOptionKey=>$fieldOption) {
            if(!isset($fieldOptionTargetNodes[$fieldOption->getId()])) {
                $fieldOptionTargetNodes[] = Array(
                    'name' => $fieldOption->getValue(),
                    'id' => $fieldOption->getId(),
                    'value' => ''
                );
            }
        }

        $serializer = $this->container->get('serializer');

        return array(
            'entities' => $serializer->serialize($fieldOptionTargetNodes,$_format)
        );
    }

    /**
     * Deletes a Target entity.
     *
     * @Secure(roles="ROLE_TARGET_DELETE")
     * @Route("/{id}", name="target_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisIndicatorBundle:Target')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Target entity.');
            }

            $em->createQueryBuilder('target')
                ->delete('HrisIndicatorBundle:Target','target')
                ->where('target.id= :targetId')
                ->setParameter('targetId',$id)
                ->getQuery()->getResult();
            $em->flush();
        }

        return $this->redirect($this->generateUrl('target'));
    }

    /**
     * Creates a form to delete a Target entity by id.
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
