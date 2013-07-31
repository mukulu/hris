<?php

namespace Hris\RecordsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\RecordsBundle\Entity\Record;
use Hris\RecordsBundle\Form\RecordType;
use Hris\FormBundle\Entity\Form;
use Hris\FormBundle\Form\FormType;

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
    public function formlistAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisFormBundle:Form')->findAll();

        return array(
            'entities' => $entities,
        );
    
    	/*return array(
    			'entity' => $entity,
    			'form'   => $form->createView(),
    	);*/
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
     * @Route("/new", name="record_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Record();
        $form   = $this->createForm(new RecordType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
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
