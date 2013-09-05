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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Hris\OrganisationunitBundle\Entity\OrganisationunitLevel;
use Hris\OrganisationunitBundle\Form\OrganisationunitLevelType;

/**
 * OrganisationunitLevel controller.
 *
 * @Route("/organisationunitlevel")
 */
class OrganisationunitLevelController extends Controller
{

    /**
     * Lists all OrganisationunitLevel entities.
     *
     * @Route("/", name="organisationunitlevel")
     * @Route("/list", name="organisationunitlevel_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitLevel')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new OrganisationunitLevel entity.
     *
     * @Route("/", name="organisationunitlevel_create")
     * @Method("POST")
     * @Template("HrisOrganisationunitBundle:OrganisationunitLevel:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new OrganisationunitLevel();
        $form = $this->createForm(new OrganisationunitLevelType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('organisationunitlevel_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new OrganisationunitLevel entity.
     *
     * @Route("/new", name="organisationunitlevel_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new OrganisationunitLevel();
        $form   = $this->createForm(new OrganisationunitLevelType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a OrganisationunitLevel entity.
     *
     * @Route("/{id}", name="organisationunitlevel_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitLevel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitLevel entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing OrganisationunitLevel entity.
     *
     * @Route("/{id}/edit", name="organisationunitlevel_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitLevel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitLevel entity.');
        }

        $editForm = $this->createForm(new OrganisationunitLevelType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing OrganisationunitLevel entity.
     *
     * @Route("/{id}", name="organisationunitlevel_update")
     * @Method("PUT")
     * @Template("HrisOrganisationunitBundle:OrganisationunitLevel:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitLevel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrganisationunitLevel entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new OrganisationunitLevelType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('organisationunitlevel_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a OrganisationunitLevel entity.
     *
     * @Route("/{id}", name="organisationunitlevel_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitLevel')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find OrganisationunitLevel entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('organisationunitlevel'));
    }

    /**
     * Creates a form to delete a OrganisationunitLevel entity by id.
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

    /**
     * Returns OrganisationunitLevel json.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNIT_ORGANISATIONUNIT_LEVEL,ROLE_USER")
     *
     * @Route("/levels.{_format}", requirements={"_format"="yml|xml|json|"}, defaults={"format"="json","parent"=0}, name="organisationunit_levels")
     * @Route("/levels/{parent}/parent",requirements={"parent"="\d+"},defaults={"parent"=0}, name="organisationunit_levels_parent")
     * @Method("GET|POST")
     * @Template()
     */
    public function levelsAction($parent,$_format)
    {
        $em = $this->getDoctrine()->getManager();
        $organisationunitid = $this->getRequest()->get('organisationunitid');
        $queryBuilder = $em->createQueryBuilder();
        $organisationunitLevels = $queryBuilder->select('organisationunitLevel.level,organisationunitLevel.name')->from('HrisOrganisationunitBundle:OrganisationunitLevel', 'organisationunitLevel')
                                    ->where('organisationunitLevel.level> (
                                        SELECT selectedLevel.level
                                        FROM HrisOrganisationunitBundle:OrganisationunitStructure organisationunitStructure
                                        INNER JOIN organisationunitStructure.organisationunit organisationunit
                                        INNER JOIN organisationunitStructure.level selectedLevel
                                        WHERE organisationunit.id='.$organisationunitid.'
                                        )
                                    ')->getQuery()->getResult();

        $serializer = $this->container->get('serializer');

        return array(
            'entities' => $serializer->serialize($organisationunitLevels,$_format)
        );
    }
}
