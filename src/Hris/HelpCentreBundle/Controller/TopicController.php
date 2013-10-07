<?php

namespace Hris\HelpCentreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\HelpCentreBundle\Entity\Topic;
use Hris\HelpCentreBundle\Form\TopicType;

/**
 * Topic controller.
 *
 * @Route("/help/topic")
 */
class TopicController extends Controller
{

    /**
     * Lists all Topic entities.
     *
     * @Route("/", name="topic")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisHelpCentreBundle:Topic')->findAll();

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
     * Creates a new Topic entity.
     *
     * @Route("/", name="topic_create")
     * @Method("POST")
     * @Template("HrisHelpCentreBundle:Topic:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Topic();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('topic_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Topic entity.
    *
    * @param Topic $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Topic $entity)
    {
        $form = $this->createForm(new TopicType(), $entity, array(
            'action' => $this->generateUrl('topic_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Topic entity.
     *
     * @Route("/new", name="topic_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Topic();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Topic entity.
     *
     * @Route("/{id}", name="topic_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisHelpCentreBundle:Topic')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Topic entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Topic entity.
     *
     * @Route("/{id}/edit", name="topic_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisHelpCentreBundle:Topic')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Topic entity.');
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
    * Creates a form to edit a Topic entity.
    *
    * @param Topic $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Topic $entity)
    {
        $form = $this->createForm(new TopicType(), $entity, array(
            'action' => $this->generateUrl('topic_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Topic entity.
     *
     * @Route("/{id}", name="topic_update")
     * @Method("PUT")
     * @Template("HrisHelpCentreBundle:Topic:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisHelpCentreBundle:Topic')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Topic entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('topic_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Topic entity.
     *
     * @Route("/{id}", name="topic_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisHelpCentreBundle:Topic')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Topic entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('topic'));
    }

    /**
     * Creates a form to delete a Topic entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('topic_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
