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
use Hris\OrganisationunitBundle\Form\HierarchyOperationType;
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
     * @Route("/{parent}/parent",requirements={"parent"="\d+"}, name="organisationunit_parent")
     * @Route("/list", name="organisationunit_list")
     * @Route("/list/{parent}/parent", name="organisationunit_list_parent")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($parent=NULL)
    {
        $em = $this->getDoctrine()->getManager();
        if($parent == NULL) {

            $queryBuilder = $em->createQueryBuilder();
            $entities = $queryBuilder->select('organisationunit')
                                        ->from('HrisOrganisationunitBundle:Organisationunit', 'organisationunit')
                                        ->where('organisationunit.parent IS NULL')->getQuery()->getResult();
        }else {
            $queryBuilder = $em->createQueryBuilder();
            $entities = $queryBuilder->select('organisationunit')
                ->from('HrisOrganisationunitBundle:Organisationunit', 'organisationunit')
                ->join('organisationunit.parent','parent')
                ->where('parent.id=:parentId')
                ->setParameter('parentId',$parent)->getQuery()->getResult();
            $parent = $this->getDoctrine()->getManager()->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($parent);
        }
        $delete_forms = NULL;
        foreach($entities as $entity) {
            $delete_form= $this->createDeleteForm($entity->getId());
            $delete_forms[$entity->getId()] = $delete_form->createView();
        }

        return array(
            'entities' => $entities,
            'parent'=>$parent,
            'delete_forms' => $delete_forms,
        );
    }

    /**
     * Creates a new Organisationunit entity.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNIT_ORGANISATIONUNIT_CREATE,ROLE_USER")
     *
     * @Route("/", name="organisationunit_create")
     * @Route("/{parent}/parent",requirements={"parent"="\d+"}, name="organisationunit_create_parent")
     * @Method("POST")
     * @Template("HrisOrganisationunitBundle:Organisationunit:new.html.twig")
     */
    public function createAction(Request $request,$parent=NULL)
    {
        $entity  = new Organisationunit();
        $form = $this->createForm(new OrganisationunitType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!empty($parent)) {
                $parent = $this->getDoctrine()->getManager()->getRepository('HrisOrganisationunitBundle:Organisationunit')->findOneBy(array('id'=>$parent));
                $entity->setParent($parent);
            }else {
                $parent = NULL;
                $entity->setParent($parent);
            }
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
     * @Route("/new/{parent}/parent", name="organisationunit_new_parent")
     * @Method("GET")
     * @Template()
     */
    public function newAction($parent=NULL)
    {
        $entity = new Organisationunit();
        $form   = $this->createForm(new OrganisationunitType(), $entity);
        if(!empty($parent)) {
            $parent = $this->getDoctrine()->getManager()->getRepository('HrisOrganisationunitBundle:Organisationunit')->findOneBy(array('id'=>$parent));
        }else {
            $parent = NULL;
        }

        return array(
            'entity' => $entity,
            'parent'=>$parent,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Organisationunit entity.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNIT_ORGANISATIONUNIT_SHOW,ROLE_USER")
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="organisationunit_show")
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
        $parent = NULL;

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->findOneBy(array('id'=>$id));
            $parent = $entity->getParent();

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Organisationunit entity.');
            }

            $em->createQueryBuilder('organisationunit')
                ->delete('HrisOrganisationunitBundle:Organisationunit','organisationunit')
                ->where('organisationunit.id= :organisationunitId')
                ->setParameter('organisationunitId',$id)
                ->getQuery()->getResult();
            $em->flush();
        }

        return !empty($parent) ? $this->redirect($this->generateUrl('organisationunit_list_parent', array('parent' => $parent->getId()))) : $this->redirect($this->generateUrl('organisationunit_list'));
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

    /**
     * Returns Organisationunit tree json.
     *
     * @Secure(roles="ROLE_ORGANISATIONUNIT_ORGANISATIONUNIT_TREE,ROLE_USER")
     *
     * @Route("/tree.{_format}", requirements={"_format"="yml|xml|json"}, defaults={"format"="json","parent"=0}, name="organisationunit_tree")
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
                                                        GROUP BY organisationunit.id,organisationunit.longname");
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
                                                        GROUP BY organisationunit.id,organisationunit.longname")->setParameter('parentid',$id);

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
     * Displays form for performing Hierarchy Operation
     *
     * @Route("/hierarchyoperation", name="organisationunit_hierarchy_operation")
     * @Method("GET")
     * @Template()
     */
    public function hierarchyOperationAction()
    {

        $hierarchyOperationForm = $this->createForm(new HierarchyOperationType(),null,array('em'=>$this->getDoctrine()->getManager()));

        return array(
            'hierarchyOperationForm'=>$hierarchyOperationForm->createView(),
        );
    }
}
