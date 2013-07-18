<?php

namespace Hris\OrganisationunitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\OrganisationunitBundle\Entity\OrganisationunitGroup;
use Hris\OrganisationunitBundle\Form\OrganisationunitGroupType;

/**
 * OrganisationunitGroup controller.
 *
 * @Route("/organisationunitgroup")
 */
class OrganisationunitGroupController extends Controller
{

    /**
     * Lists all OrganisationunitGroup entities.
     *
     * @Route("/", name="organisationunitgroup")
     * @Route("/list", name="organisationunitgroup_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroup')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new OrganisationunitGroup entity.
     *
     * @Route("/", name="organisationunitgroup_create")
     * @Method("POST")
     * @Template("HrisOrganisationunitBundle:OrganisationunitGroup:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new OrganisationunitGroup();
        $form = $this->createForm(new OrganisationunitGroupType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('organisationunitgroup_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new OrganisationunitGroup entity.
     *
     * @Route("/new", name="organisationunitgroup_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new OrganisationunitGroup();
        $form   = $this->createForm(new OrganisationunitGroupType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a OrganisationunitGroup entity.
     *
     * @Route("/{id}", name="organisationunitgroup_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing OrganisationunitGroup entity.
     *
     * @Route("/{id}/edit", name="organisationunitgroup_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitGroup entity.');
        }

        $editForm = $this->createForm(new OrganisationunitGroupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing OrganisationunitGroup entity.
     *
     * @Route("/{id}", name="organisationunitgroup_update")
     * @Method("PUT")
     * @Template("HrisOrganisationunitBundle:OrganisationunitGroup:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new OrganisationunitGroupType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('organisationunitgroup_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a OrganisationunitGroup entity.
     *
     * @Route("/{id}", name="organisationunitgroup_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find OrganisationunitGroup entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('organisationunitgroup'));
    }

    /**
     * Creates a form to delete a OrganisationunitGroup entity by id.
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
