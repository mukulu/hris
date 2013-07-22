<?php

namespace Hris\OrganisationunitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\OrganisationunitBundle\Entity\OrganisationunitStructure;
use Hris\OrganisationunitBundle\Form\OrganisationunitStructureType;

/**
 * OrganisationunitStructure controller.
 *
 * @Route("/organisationunitstructure")
 */
class OrganisationunitStructureController extends Controller
{

    /**
     * Lists all OrganisationunitStructure entities.
     *
     * @Route("/", name="organisationunitstructure")
     * @Route("/list", name="organisationunitstructure_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitStructure')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new OrganisationunitStructure entity.
     *
     * @Route("/", name="organisationunitstructure_create")
     * @Method("POST")
     * @Template("HrisOrganisationunitBundle:OrganisationunitStructure:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new OrganisationunitStructure();
        $form = $this->createForm(new OrganisationunitStructureType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('organisationunitstructure_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new OrganisationunitStructure entity.
     *
     * @Route("/new", name="organisationunitstructure_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new OrganisationunitStructure();
        $form   = $this->createForm(new OrganisationunitStructureType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a OrganisationunitStructure entity.
     *
     * @Route("/{id}", name="organisationunitstructure_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitStructure')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitStructure entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing OrganisationunitStructure entity.
     *
     * @Route("/{id}/edit", name="organisationunitstructure_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitStructure')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitStructure entity.');
        }

        $editForm = $this->createForm(new OrganisationunitStructureType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing OrganisationunitStructure entity.
     *
     * @Route("/{id}", name="organisationunitstructure_update")
     * @Method("PUT")
     * @Template("HrisOrganisationunitBundle:OrganisationunitStructure:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitStructure')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitStructure entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new OrganisationunitStructureType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('organisationunitstructure_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a OrganisationunitStructure entity.
     *
     * @Route("/{id}", name="organisationunitstructure_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitStructure')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find OrganisationunitStructure entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('organisationunitstructure'));
    }

    /**
     * Creates a form to delete a OrganisationunitStructure entity by id.
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
