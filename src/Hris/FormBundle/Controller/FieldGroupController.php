<?php

namespace Hris\FormBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\FormBundle\Entity\FieldGroup;
use Hris\FormBundle\Form\FieldGroupType;

/**
 * FieldGroup controller.
 *
 * @Route("/fieldgroup")
 */
class FieldGroupController extends Controller
{

    /**
     * Lists all FieldGroup entities.
     *
     * @Route("/", name="fieldgroup")
     * @Route("/list", name="fieldgroup_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisFormBundle:FieldGroup')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new FieldGroup entity.
     *
     * @Route("/", name="fieldgroup_create")
     * @Method("POST")
     * @Template("HrisFormBundle:FieldGroup:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new FieldGroup();
        $form = $this->createForm(new FieldGroupType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('fieldgroup_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new FieldGroup entity.
     *
     * @Route("/new", name="fieldgroup_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FieldGroup();
        $form   = $this->createForm(new FieldGroupType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a FieldGroup entity.
     *
     * @Route("/{id}", name="fieldgroup_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:FieldGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FieldGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FieldGroup entity.
     *
     * @Route("/{id}/edit", name="fieldgroup_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:FieldGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FieldGroup entity.');
        }

        $editForm = $this->createForm(new FieldGroupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing FieldGroup entity.
     *
     * @Route("/{id}", name="fieldgroup_update")
     * @Method("PUT")
     * @Template("HrisFormBundle:FieldGroup:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:FieldGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FieldGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FieldGroupType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('fieldgroup_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a FieldGroup entity.
     *
     * @Route("/{id}", name="fieldgroup_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisFormBundle:FieldGroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FieldGroup entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('fieldgroup'));
    }

    /**
     * Creates a form to delete a FieldGroup entity by id.
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
