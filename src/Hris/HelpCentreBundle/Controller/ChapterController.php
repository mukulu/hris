<?php

namespace Hris\HelpCentreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\HelpCentreBundle\Entity\Chapter;
use Hris\HelpCentreBundle\Form\ChapterType;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Chapter controller.
 *
 * @Route("/help/chapter")
 */
class ChapterController extends Controller
{

    /**
     * Lists all Chapter entities.
     *
     * @Secure(roles="ROLE_HELPCHAPTER_LIST,ROLE_USER")
     * @Route("/", name="help_chapter")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisHelpCentreBundle:Chapter')->findAll();

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
     * Creates a new Chapter entity.
     *
     * @Secure(roles="ROLE_HELPCHAPTER_CREATE,ROLE_USER")
     * @Route("/", name="help_chapter_create")
     * @Method("POST")
     * @Template("HrisHelpCentreBundle:Chapter:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Chapter();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('help_chapter_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Chapter entity.
    *
    * @param Chapter $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Chapter $entity)
    {
        $form = $this->createForm(new ChapterType(), $entity, array(
            'action' => $this->generateUrl('help_chapter_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Chapter entity.
     *
     * @Secure(roles="ROLE_HELPCHAPTER_CREATE,ROLE_USER")
     * @Route("/new", name="help_chapter_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Chapter();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Chapter entity.
     *
     * @Secure(roles="ROLE_HELPCHAPTER_SHOW,ROLE_USER")
     * @Route("/{id}", name="help_chapter_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisHelpCentreBundle:Chapter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chapter entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Chapter entity.
     *
     * @Secure(roles="ROLE_HELPCHAPTER_UPDATE,ROLE_USER")
     * @Route("/{id}/edit", name="help_chapter_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisHelpCentreBundle:Chapter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chapter entity.');
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
    * Creates a form to edit a Chapter entity.
    *
    * @param Chapter $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Chapter $entity)
    {
        $form = $this->createForm(new ChapterType(), $entity, array(
            'action' => $this->generateUrl('help_chapter_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Chapter entity.
     *
     * @Secure(roles="ROLE_HELPCHAPTER_UPDATE,ROLE_USER")
     * @Route("/{id}", name="help_chapter_update")
     * @Method("PUT")
     * @Template("HrisHelpCentreBundle:Chapter:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisHelpCentreBundle:Chapter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chapter entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('help_chapter_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Chapter entity.
     *
     * @Secure(roles="ROLE_HELPCHAPTER_DELETE,ROLE_USER")
     * @Route("/{id}", name="help_chapter_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisHelpCentreBundle:Chapter')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Chapter entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('help_chapter'));
    }

    /**
     * Creates a form to delete a Chapter entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('help_chapter_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
