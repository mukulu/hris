<?php

namespace Hris\FormBundle\Controller;

use Hris\FormBundle\Form\FieldOptionMergeVisibleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\FormBundle\Entity\FieldOptionMerge;
use Hris\FormBundle\Form\FieldOptionMergeType;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * FieldOptionMerge controller.
 *
 * @Route("/fieldoption/merge")
 */
class FieldOptionMergeController extends Controller
{

    /**
     * Lists all FieldOptionMerge entities.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_FIELDOPTIONMERGE_LIST")
     * @Route("/", name="fieldoptionmerge")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisFormBundle:FieldOptionMerge')->findAll();

        $delete_forms = NULL;
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
     * Creates a new FieldOptionMerge entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_FIELDOPTIONMERGE_CREATE")
     * @Route("/", name="fieldoptionmerge_create")
     * @Method("POST")
     * @Template("HrisFormBundle:FieldOptionMerge:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new FieldOptionMerge();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('fieldoptionmerge_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a FieldOptionMerge entity.
    *
    * @param FieldOptionMerge $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(FieldOptionMerge $entity)
    {
        $form = $this->createForm(new FieldOptionMergeType(), $entity, array(
            'action' => $this->generateUrl('fieldoptionmerge_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new FieldOptionMerge entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_FIELDOPTIONMERGE_CREATE")
     * @Route("/new", name="fieldoptionmerge_new")
     * @Route("/new/{fieldid}/field", requirements={"fieldid"="\d+"}, name="fieldoptionmerge_new_byfield")
     * @Route("/new/{fieldid}/field/{fieldoptionid}/fieldoption", requirements={"fieldid"="\d+","fieldoptionid"="\d+"}, name="fieldoptionmerge_new_byfield_and_fieldoption")
     * @Method("GET")
     * @Template()
     */
    public function newAction($fieldid=NULL,$fieldoptionid=NULL)
    {
        $entity = new FieldOptionMerge();
        if(empty($fieldid) && empty($fieldoptionid)) {
            $form = $this->createForm(new FieldOptionMergeVisibleType(), $entity, array(
                'action' => $this->generateUrl('fieldoptionmerge_create'),
                'method' => 'POST',
            ));
        }else {
            $form   = $this->createCreateForm($entity);
        }

        // Set default field value
        if(!empty($fieldid)) {
            $em = $this->getDoctrine()->getManager();
            $field = $em->getRepository('HrisFormBundle:Field')->findOneBy(array('id'=>$fieldid));
            $form->get('field')->setData($field);
        }else {
            $field=NULL;
        }
        // Set default field value
        if(!empty($fieldoptionid)) {
            $em = $this->getDoctrine()->getManager();
            $fieldoption = $em->getRepository('HrisFormBundle:FieldOption')->findOneBy(array('id'=>$fieldoptionid));
            $form->get('mergedFieldOption')->setData($fieldoption);
        }else {
            $fieldoptionid=NULL;
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'field'=>$field,
            'fieldoptionid'=>$fieldoptionid,
        );
    }

    /**
     * Finds and displays a FieldOptionMerge entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_FIELDOPTIONMERGE_SHOW")
     * @Route("/{id}", name="fieldoptionmerge_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:FieldOptionMerge')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FieldOptionMerge entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FieldOptionMerge entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_FIELDOPTIONMERGE_UPDATE")
     * @Route("/{id}/edit", name="fieldoptionmerge_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:FieldOptionMerge')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FieldOptionMerge entity.');
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
    * Creates a form to edit a FieldOptionMerge entity.
    *
    * @param FieldOptionMerge $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(FieldOptionMerge $entity)
    {
        $form = $this->createForm(new FieldOptionMergeType(), $entity, array(
            'action' => $this->generateUrl('fieldoptionmerge_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing FieldOptionMerge entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_FIELDOPTIONMERGE_UPDATE")
     * @Route("/{id}", name="fieldoptionmerge_update")
     * @Method("PUT")
     * @Template("HrisFormBundle:FieldOptionMerge:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:FieldOptionMerge')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FieldOptionMerge entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('fieldoptionmerge_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a FieldOptionMerge entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_FIELDOPTIONMERGE_DELETE")
     * @Route("/{id}", name="fieldoptionmerge_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisFormBundle:FieldOptionMerge')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FieldOptionMerge entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('fieldoptionmerge'));
    }

    /**
     * Creates a form to delete a FieldOptionMerge entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('fieldoptionmerge_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
