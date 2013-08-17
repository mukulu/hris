<?php

namespace Hris\IndicatorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\IndicatorBundle\Entity\Indicator;
use Hris\IndicatorBundle\Form\IndicatorType;

/**
 * Indicator controller.
 *
 * @Route("/indicator")
 */
class IndicatorController extends Controller
{

    /**
     * Lists all Indicator entities.
     *
     * @Route("/", name="indicator")
     * @Route("/list", name="indicator_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisIndicatorBundle:Indicator')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Indicator entity.
     *
     * @Route("/", name="indicator_create")
     * @Method("POST")
     * @Template("HrisIndicatorBundle:Indicator:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Indicator();
        $form = $this->createForm(new IndicatorType(), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('indicator_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Indicator entity.
     *
     * @Route("/new", name="indicator_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Indicator();
        $form   = $this->createForm(new IndicatorType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Indicator entity.
     *
     * @Route("/{id}", name="indicator_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisIndicatorBundle:Indicator')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Indicator entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Indicator entity.
     *
     * @Route("/{id}/edit", name="indicator_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisIndicatorBundle:Indicator')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Indicator entity.');
        }

        $editForm = $this->createForm(new IndicatorType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Indicator entity.
     *
     * @Route("/{id}", name="indicator_update")
     * @Method("PUT")
     * @Template("HrisIndicatorBundle:Indicator:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisIndicatorBundle:Indicator')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Indicator entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new IndicatorType(), $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('indicator_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Indicator entity.
     *
     * @Route("/{id}", name="indicator_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisIndicatorBundle:Indicator')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Indicator entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('indicator'));
    }

    /**
     * Creates a form to delete a Indicator entity by id.
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
