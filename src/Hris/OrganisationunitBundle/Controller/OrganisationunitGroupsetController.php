<?php

namespace Hris\OrganisationunitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\OrganisationunitBundle\Entity\OrganisationunitGroupset;
use Hris\OrganisationunitBundle\Form\OrganisationunitGroupsetType;

/**
 * OrganisationunitGroupset controller.
 *
 * @Route("/organisationunitgroupset")
 */
class OrganisationunitGroupsetController extends Controller
{

    /**
     * Lists all OrganisationunitGroupset entities.
     *
     * @Route("/", name="organisationunitgroupset")
     * @Route("/list", name="organisationunitgroupset_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroupset')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new OrganisationunitGroupset entity.
     *
     * @Route("/", name="organisationunitgroupset_create")
     * @Method("POST")
     * @Template("HrisOrganisationunitBundle:OrganisationunitGroupset:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new OrganisationunitGroupset();
        $form = $this->createForm(new OrganisationunitGroupsetType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('organisationunitgroupset_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new OrganisationunitGroupset entity.
     *
     * @Route("/new", name="organisationunitgroupset_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new OrganisationunitGroupset();
        $form   = $this->createForm(new OrganisationunitGroupsetType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a OrganisationunitGroupset entity.
     *
     * @Route("/{id}", name="organisationunitgroupset_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroupset')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitGroupset entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing OrganisationunitGroupset entity.
     *
     * @Route("/{id}/edit", name="organisationunitgroupset_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroupset')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitGroupset entity.');
        }

        $editForm = $this->createForm(new OrganisationunitGroupsetType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing OrganisationunitGroupset entity.
     *
     * @Route("/{id}", name="organisationunitgroupset_update")
     * @Method("PUT")
     * @Template("HrisOrganisationunitBundle:OrganisationunitGroupset:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroupset')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitGroupset entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new OrganisationunitGroupsetType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('organisationunitgroupset_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a OrganisationunitGroupset entity.
     *
     * @Route("/{id}", name="organisationunitgroupset_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroupset')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find OrganisationunitGroupset entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('organisationunitgroupset'));
    }

    /**
     * Creates a form to delete a OrganisationunitGroupset entity by id.
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
