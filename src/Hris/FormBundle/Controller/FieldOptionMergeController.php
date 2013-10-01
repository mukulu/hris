<?php

namespace Hris\FormBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\FormBundle\Entity\FieldOptionMerge;
use Hris\FormBundle\Form\FieldOptionMergeType;

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
     * @Route("/", name="fieldoptionmerge")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisFormBundle:FieldOptionMerge')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new FieldOptionMerge entity.
     *
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

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new FieldOptionMerge entity.
     *
     * @Route("/new", name="fieldoptionmerge_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FieldOptionMerge();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a FieldOptionMerge entity.
     *
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

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing FieldOptionMerge entity.
     *
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
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
