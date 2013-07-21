<?php

namespace Hris\OrganisationunitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\OrganisationunitBundle\Entity\OrganisationunitCompleteness;
use Hris\OrganisationunitBundle\Form\OrganisationunitCompletenessType;

/**
 * OrganisationunitCompleteness controller.
 *
 * @Route("/organisationunitcompleteness")
 */
class OrganisationunitCompletenessController extends Controller
{

    /**
     * Lists all OrganisationunitCompleteness entities.
     *
     * @Route("/", name="organisationunitcompleteness")
     * @Route("/list", name="organisationunitcompleteness_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitCompleteness')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new OrganisationunitCompleteness entity.
     *
     * @Route("/", name="organisationunitcompleteness_create")
     * @Method("POST")
     * @Template("HrisOrganisationunitBundle:OrganisationunitCompleteness:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new OrganisationunitCompleteness();
        $form = $this->createForm(new OrganisationunitCompletenessType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('organisationunitcompleteness_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new OrganisationunitCompleteness entity.
     *
     * @Route("/new", name="organisationunitcompleteness_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new OrganisationunitCompleteness();
        $form   = $this->createForm(new OrganisationunitCompletenessType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a OrganisationunitCompleteness entity.
     *
     * @Route("/{id}", name="organisationunitcompleteness_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitCompleteness')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitCompleteness entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing OrganisationunitCompleteness entity.
     *
     * @Route("/{id}/edit", name="organisationunitcompleteness_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitCompleteness')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitCompleteness entity.');
        }

        $editForm = $this->createForm(new OrganisationunitCompletenessType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing OrganisationunitCompleteness entity.
     *
     * @Route("/{id}", name="organisationunitcompleteness_update")
     * @Method("PUT")
     * @Template("HrisOrganisationunitBundle:OrganisationunitCompleteness:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitCompleteness')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitCompleteness entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new OrganisationunitCompletenessType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('organisationunitcompleteness_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a OrganisationunitCompleteness entity.
     *
     * @Route("/{id}", name="organisationunitcompleteness_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitCompleteness')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find OrganisationunitCompleteness entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('organisationunitcompleteness'));
    }

    /**
     * Creates a form to delete a OrganisationunitCompleteness entity by id.
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
