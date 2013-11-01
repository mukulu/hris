<?php
/*
 *
 * Copyright 2012 Human Resource Information System
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * Reused from FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * @since 2012
 * @author John Francis Mukulu <john.f.mukulu@gmail.com>
 *
 */
namespace Hris\UserBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Hris\OrganisationunitBundle\Entity\Organisationunit;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Hris\UserBundle\Form\UserEditType;
use Hris\UserBundle\Form\UserNewType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\UserBundle\Entity\User;
use Hris\UserBundle\Form\UserType;

/**
 * User controller.
 *
 * @Route("/user", name="hris_user")
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_USER_LIST")
     * @Route("/", name="user")
     * @Route("/list", name="user_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisUserBundle:User')->findAll();
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
     * Creates a new User entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_USER_CREATE")
     * @Route("/create", name="user_create")
     * @Method("POST")
     * @Template("HrisUserBundle:User:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form   = $this->createForm(new UserNewType(), $entity,array('em'=>$this->getDoctrine()->getManager()));
        $form->bind($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_list', array('id' => $entity->getId())));
        }else {
            print_r($form->getErrors());
            return $this->redirect($this->generateUrl('user_new'));
        }

    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_USER_CREATE")
     * @Route("/new", name="user_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createForm(new UserNewType(), $entity,array('em'=>$this->getDoctrine()->getManager()));

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a User entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_USER_SHOW")
     * @Route("/{id}", requirements={"id"="\d+"}, name="user_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Check if username exists(unique)
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_USER_ISUSERNAMEUNIQUE,IS_AUTHENTICATED_ANONYMOUSLY,ROLE_USER")
     * @Route("/isusernameunique/{_format}", requirements={"_format"="yml|xml|json"}, defaults={"_format"="json"}, name="user_isusernameunique")
     * @Method("GET")
     * @Template()
     */
    public function isUsernameUniqueAction($_format)
    {
        $em = $this->getDoctrine()->getManager();
        $username = $this->getRequest()->query->get('username');

        $entity = $em->getRepository('HrisUserBundle:User')->findBy(array('username'=>$username));

        if (empty($entity)) {
            $isusernameunique = 'true';
        }else {
            $isusernameunique = 'false';
        }

        $serializer = $this->container->get('serializer');

        return array(
            'isusernameunique' => $serializer->serialize($isusernameunique,$_format)
        );
    }

    /**
     * Check if email exists(unique)
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_USER_ISEMAILUNIQUE,IS_AUTHENTICATED_ANONYMOUSLY,ROLE_USER")
     * @Route("/isemailunique/{_format}", requirements={"_format"="yml|xml|json"}, defaults={"_format"="json"}, name="user_isemailunique")
     * @Method("GET")
     * @Template()
     */
    public function isEmailUniqueAction($_format)
    {
        $em = $this->getDoctrine()->getManager();
        $email = $this->getRequest()->query->get('email');

        $entity = $em->getRepository('HrisUserBundle:User')->findBy(array('email'=>$email));

        if (empty($entity)) {
            $isemailunique = 'true';
        }else {
            $isemailunique = 'false';
        }

        $serializer = $this->container->get('serializer');

        return array(
            'isemailunique' => $serializer->serialize($isemailunique,$_format)
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_USER_UPDATE")
     * @Route("/{id}/edit", requirements={"id"="\d+"}, name="user_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserType(), $entity,array('em'=>$this->getDoctrine()->getManager()));
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing User entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_USER_UPDATE")
     * @Route("/{id}", requirements={"id"="\d+"}, name="user_update")
     * @Method("PUT")
     * @Template("HrisUserBundle:User:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new UserType(), $entity,array('em'=>$this->getDoctrine()->getManager()));
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_list'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a User entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_USER_DELETE")
     * @Route("/{id}", requirements={"id"="\d+"}, name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisUserBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user'));
    }

    /**
     * Creates a form to delete a User entity by id.
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
