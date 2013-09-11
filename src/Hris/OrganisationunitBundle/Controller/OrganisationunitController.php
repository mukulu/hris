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
 *
 * @since 2012
 * @author John Francis Mukulu <john.f.mukulu@gmail.com>
 *
 */
namespace Hris\OrganisationunitBundle\Controller;

use Doctrine\ORM\NoResultException;
use Doctrine\Tests\Common\Annotations\Null;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Hris\OrganisationunitBundle\Form\OrganisationunitType;

/**
 * Organisationunit controller.
 *
 * @Route("/organisationunit")
 */
class OrganisationunitController extends Controller
{

    /**
     * Lists all Organisationunit entities.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNIT_ORGANISATIONUNIT_LIST,ROLE_USER")
     *
     * @Route("/", name="organisationunit")
     * @Route("/{parent}/parent", name="organisationunit_parent")
     * @Route("/list", name="organisationunit_list")
     * @Route("/list/{parent}/parent", name="organisationunit_list_parent")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($parent=NULL)
    {
        $em = $this->getDoctrine()->getManager();
        if($parent == NULL) {
            $entities = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->findAll();
        }
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
     * Returns Organisationunit tree json.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNIT_ORGANISATIONUNIT_TREE,ROLE_USER")
     *
     * @Route("/tree.{_format}", requirements={"_format"="yml|xml|json|"}, defaults={"format"="json","parent"=0}, name="organisationunit_tree")
     * @Route("/tree/{parent}/parent",requirements={"parent"="\d+"},defaults={"parent"=0}, name="organisationunit_tree_parent")
     * @Method("GET")
     * @Template()
     */
    public function treeAction($parent,$_format)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $this->getRequest()->query->get('id');

        if($id == NULL || $id==0) {
            // Root organisationunits called
            $organisationunitQuery = $em->createQuery("SELECT organisationunit.id,organisationunit.longname,
                                                        (
                                                            SELECT COUNT(lowerOrganisationunit.id)
                                                            FROM HrisOrganisationunitBundle:Organisationunit lowerOrganisationunit
                                                            WHERE lowerOrganisationunit.parent=organisationunit
                                                        ) AS lowerChildrenCount
                                                        FROM HrisOrganisationunitBundle:Organisationunit organisationunit
                                                        WHERE organisationunit.parent IS NULL
                                                        GROUP BY organisationunit.id");
            try {
                $entities = $organisationunitQuery->getArrayResult();
            } catch(NoResultException $e) {
                $entities = NULL;
            }
        }else {
            // Leaf organisationunits called
            $organisationunitQuery = $em->createQuery("SELECT organisationunit.id,organisationunit.longname,
                                                        (
                                                            SELECT COUNT(lowerOrganisationunit.id)
                                                            FROM HrisOrganisationunitBundle:Organisationunit lowerOrganisationunit
                                                            WHERE lowerOrganisationunit.parent=organisationunit
                                                        ) AS lowerChildrenCount
                                                        FROM HrisOrganisationunitBundle:Organisationunit organisationunit
                                                        WHERE organisationunit.parent=:parentid
                                                        GROUP BY organisationunit.id")->setParameter('parentid',$id);



            try {
                $entities = $organisationunitQuery->getArrayResult();
            } catch(NoResultException $e) {
                $entities = NULL;
            }
        }

        $organisationunitTreeNodes = NULL;
        foreach($entities as $key=>$entity) {
            if($entity['lowerChildrenCount'] > 0 ) {
                    // Entity has no children
                    $organisationunitTreeNodes[] = Array(
                        'id' => $entity['id'],
                        'longname' => $entity['longname'],
                        'cls' => 'folder'
                    );
            }else {
                // Entity has children
                $organisationunitTreeNodes[] = Array(
                    'id' => $entity['id'],
                    'longname' => $entity['longname'],
                    'cls' => 'file',
                    'leaf' => true,
                );
            }
        }
        $serializer = $this->container->get('serializer');

        return array(
            'entities' => $serializer->serialize($organisationunitTreeNodes,$_format)
        );
    }

    /**
     * Creates a new Organisationunit entity.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNIT_ORGANISATIONUNIT_CREATE,ROLE_USER")
     *
     * @Route("/", name="organisationunit_create")
     * @Method("POST")
     * @Template("HrisOrganisationunitBundle:Organisationunit:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Organisationunit();
        $form = $this->createForm(new OrganisationunitType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('organisationunit_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Organisationunit entity.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNIT_ORGANISATIONUNIT_CREATE,ROLE_USER")
     *
     * @Route("/new", name="organisationunit_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Organisationunit();
        $form   = $this->createForm(new OrganisationunitType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Organisationunit entity.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNIT_ORGANISATIONUNIT_SHOW,ROLE_USER")
     *
     * @Route("/{id}", name="organisationunit_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Organisationunit entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Organisationunit entity.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNIT_ORGANISATIONUNIT_EDIT,ROLE_USER")
     *
     * @Route("/{id}/edit", name="organisationunit_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Organisationunit entity.');
        }

        $editForm = $this->createForm(new OrganisationunitType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Organisationunit entity.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNIT_ORGANISATIONUNIT_EDIT,ROLE_USER")
     *
     * @Route("/{id}", name="organisationunit_update")
     * @Method("PUT")
     * @Template("HrisOrganisationunitBundle:Organisationunit:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Organisationunit entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new OrganisationunitType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('organisationunit_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Organisationunit entity.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNIT_ORGANISATIONUNIT_DELETE,ROLE_USER")
     *
     * @Route("/{id}", name="organisationunit_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Organisationunit entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('organisationunit'));
    }

    /**
     * Creates a form to delete a Organisationunit entity by id.
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
