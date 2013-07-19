<?php

namespace Hris\FormBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\FormBundle\Entity\FriendlyReport;
use Hris\FormBundle\Form\FriendlyReportType;

/**
 * FriendlyReport controller.
 *
 * @Route("/friendlyreport")
 */
class FriendlyReportController extends Controller
{

    /**
     * Lists all FriendlyReport entities.
     *
     * @Route("/", name="friendlyreport")
     * @Route("/list", name="friendlyreport_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisFormBundle:FriendlyReport')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new FriendlyReport entity.
     *
     * @Route("/", name="friendlyreport_create")
     * @Method("POST")
     * @Template("HrisFormBundle:FriendlyReport:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new FriendlyReport();
        $form = $this->createForm(new FriendlyReportType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('friendlyreport_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new FriendlyReport entity.
     *
     * @Route("/new", name="friendlyreport_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FriendlyReport();
        $form   = $this->createForm(new FriendlyReportType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a FriendlyReport entity.
     *
     * @Route("/{id}", name="friendlyreport_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:FriendlyReport')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FriendlyReport entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FriendlyReport entity.
     *
     * @Route("/{id}/edit", name="friendlyreport_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:FriendlyReport')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FriendlyReport entity.');
        }

        $editForm = $this->createForm(new FriendlyReportType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing FriendlyReport entity.
     *
     * @Route("/{id}", name="friendlyreport_update")
     * @Method("PUT")
     * @Template("HrisFormBundle:FriendlyReport:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:FriendlyReport')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FriendlyReport entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FriendlyReportType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('friendlyreport_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a FriendlyReport entity.
     *
     * @Route("/{id}", name="friendlyreport_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisFormBundle:FriendlyReport')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FriendlyReport entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('friendlyreport'));
    }

    /**
     * Creates a form to delete a FriendlyReport entity by id.
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
