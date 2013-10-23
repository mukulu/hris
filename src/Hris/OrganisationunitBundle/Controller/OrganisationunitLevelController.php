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
    private $returnMessage;

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

        $queryBuilder = $em->createQueryBuilder();
        $organisationunitStructureCount =  $queryBuilder->select('count( organisationunitStructure.id )')->from('HrisOrganisationunitBundle:OrganisationunitStructure','organisationunitStructure')->getQuery()->getSingleScalarResult();
        $queryBuilder = $em->createQueryBuilder();
        $organisationunitCount =  $queryBuilder->select('count( organisationunit.id )')->from('HrisOrganisationunitBundle:Organisationunit','organisationunit')->getQuery()->getSingleScalarResult();

        // Regenerate Orgunit Stucture of Orgunit and OrgunitStructure Differs
        if($organisationunitCount!=$organisationunitStructureCount) {
            $regenerationRequired=True;
        }else {
            $regenerationRequired=False;
        }

        $delete_forms = NULL;
        foreach($entities as $entity) {
            $delete_form= $this->createDeleteForm($entity->getId());
            $delete_forms[$entity->getId()] = $delete_form->createView();
        }

        return array(
            'entities' => $entities,
            'delete_forms' => $delete_forms,
            'regenerationRequired'=>$regenerationRequired,
        );
    }

    /**
     * Regenerate all OrganisationunitLevel entities.
     *
     * @Route("/regeneration", name="organisationunitlevel_regeneration")
     * @Method("GET")
     * @Template()
     */
    public function regenerationAction()
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Check and Notify if organisationunit structure doesn't exist
        $queryBuilder = $entityManager->createQueryBuilder();
        $organisationunitStructureCount =  $queryBuilder->select('count( organisationunitStructure.id )')->from('HrisOrganisationunitBundle:OrganisationunitStructure','organisationunitStructure')->getQuery()->getSingleScalarResult();
        $queryBuilder = $entityManager->createQueryBuilder();
        $organisationunitCount =  $queryBuilder->select('count( organisationunit.id )')->from('HrisOrganisationunitBundle:Organisationunit','organisationunit')->getQuery()->getSingleScalarResult();

        // Regenerate Orgunit Stucture of Orgunit and OrgunitStructure Differs
        if($organisationunitCount!=$organisationunitStructureCount) {
            $this->returnMessage ='';
            // Regenerate Orgunit Structure
            $organisationunitStructure = new OrganisationunitStructureController();
            $this->returnMessage = $organisationunitStructure->regenerateOrganisationunitStructure($entityManager);
        }else {
            $this->returnMessage='Organisationunit structure is complete!';
        };


        // Regenerate Levels if OrgunitLevel and DISTINCT OrgunitStructure.level differs
        $organisationunitStructureLevels = $entityManager->createQuery('SELECT DISTINCT organisationunitLevel.level FROM HrisOrganisationunitBundle:OrganisationunitStructure organisationunitStructure INNER JOIN organisationunitStructure.level organisationunitLevel ORDER BY organisationunitLevel.level ')->getResult();
        $organisationunitLevelInfos = $entityManager->createQuery('SELECT organisationunitLevel.level,organisationunitLevel.name,organisationunitLevel.description FROM HrisOrganisationunitBundle:OrganisationunitLevel organisationunitLevel ORDER BY organisationunitLevel.level ')->getResult();
        $organisationunitStructureLevels = $this->array_value_recursive('level', $organisationunitStructureLevels);
        $organisationunitLevelsLevel = $this->array_value_recursive('level', $organisationunitLevelInfos);
        if($organisationunitLevelsLevel != $organisationunitStructureLevels && !empty($organisationunitStructureLevels)) {
            if(!empty($organisationunitLevelInfos)) {
                // Cache in-memory saved Level names and descriptions
                $organisationunitLevelsName = $this->array_value_recursive('name', $organisationunitLevelInfos);
                $organisationunitLevelsDescription = $this->array_value_recursive('description', $organisationunitLevelInfos);
                $organisationunitLevelsName = array_combine($organisationunitLevelsLevel,$organisationunitLevelsName);
                $organisationunitLevelsDescription = array_combine($organisationunitLevelsLevel,$organisationunitLevelsDescription);
                $qb = $entityManager->createQueryBuilder('organisationunitLevel')->delete('HrisOrganisationunitBundle:OrganisationunitLevel','organisationunitLevel')->getQuery() -> getResult();
            }
            foreach($organisationunitStructureLevels as $key => $organisationunitStructureLevel) {
                // Update Levels
                $organisationunitLevel = new OrganisationunitLevel();
                if(in_array($organisationunitStructureLevel,$organisationunitLevelsLevel)) {
                    $organisationunitLevel->setName($organisationunitLevelsName[$organisationunitStructureLevel]);
                    $organisationunitLevel->setDescription($organisationunitLevelsDescription[$organisationunitStructureLevel]);
                    $organisationunitLevel->setLevel($organisationunitStructureLevel);
                    $entityManager->persist($organisationunitLevel);
                }else {
                    $organisationunitLevel->setName('Level'.$organisationunitStructureLevel);
                    $organisationunitLevel->setDescription('Level'.$organisationunitStructureLevel);
                    $organisationunitLevel->setLevel($organisationunitStructureLevel);
                    $entityManager->persist($organisationunitLevel);
                }
            }
            $entityManager->flush();
        }
        return $this->redirect($this->generateUrl('organisationunitlevel_list'));
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
     * @Route("/{id}", requirements={"id"="\d+"}, name="organisationunitlevel_show")
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
     * @Route("/{id}/edit", requirements={"id"="\d+"}, name="organisationunitlevel_edit")
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
     * @Route("/{id}", requirements={"id"="\d+"}, name="organisationunitlevel_update")
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

            return $this->redirect($this->generateUrl('organisationunitlevel_show', array('id' => $id)));
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
     * @Route("/{id}", requirements={"id"="\d+"}, name="organisationunitlevel_delete")
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

        $lowerOrganisationunitCount = $em->createQuery("SELECT COUNT(lowerOrganisationunit.id)
                                                            FROM HrisOrganisationunitBundle:Organisationunit lowerOrganisationunit
                                                            INNER JOIN lowerOrganisationunit.parent parentOrganisationunit
                                                            WHERE parentOrganisationunit.id=".$organisationunitid)->getSingleScalarResult();
        if(isset($lowerOrganisationunitCount) && !empty($lowerOrganisationunitCount)) {
            $organisationunitLevels = $queryBuilder->select('organisationunitLevel.id,organisationunitLevel.name')->from('HrisOrganisationunitBundle:OrganisationunitLevel', 'organisationunitLevel')
                                        ->where('organisationunitLevel.level> (
                                            SELECT selectedLevel.level
                                            FROM HrisOrganisationunitBundle:OrganisationunitStructure organisationunitStructure
                                            INNER JOIN organisationunitStructure.organisationunit organisationunit
                                            INNER JOIN organisationunitStructure.level selectedLevel
                                            WHERE organisationunit.id='.$organisationunitid.'
                                            )
                                        ')
                                        ->orderby('organisationunitLevel.level','ASC')->getQuery()->getResult();
        }else {
            // Lowest Level selected return NULL
            $organisationunitLevels = NULL;
        }

        $serializer = $this->container->get('serializer');

        return array(
            'entities' => $serializer->serialize($organisationunitLevels,$_format)
        );
    }

    /**
     * Get all values from specific key in a multidimensional array
     *
     * @param $key string
     * @param $arr array
     * @return null|string|array
     */
    public function array_value_recursive($key, array $arr){
        $val = array();
        array_walk_recursive($arr, function($v, $k) use($key, &$val){if($k == $key) array_push($val, $v);});
        return count($val) > 1 ? $val : array_pop($val);
    }
}
