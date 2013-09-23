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

use Hris\OrganisationunitBundle\Entity\OrganisationunitLevel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\OrganisationunitBundle\Entity\OrganisationunitStructure;
use Hris\OrganisationunitBundle\Form\OrganisationunitStructureType;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * OrganisationunitStructure controller.
 *
 * @Route("/organisationunitstructure")
 */
class OrganisationunitStructureController extends Controller
{

    private $returnMessage;

    private $entityManager;
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

    /**
     * Generate organiastionunit structure
     * @param $entityManager
     * @return string
     */
    public function regenerateOrganisationunitStructure($entityManager) {
        $this->entityManager = $entityManager;
        $stopwatch = new Stopwatch();
        $stopwatch->start('organisationnunitStructureRegeneration');
        $parents = $this->entityManager->createQuery("SELECT organisationunit FROM HrisOrganisationunitBundle:Organisationunit organisationunit WHERE organisationunit.parent IS NULL")->getResult();
        $qb = $this->entityManager->createQueryBuilder()->delete('HrisOrganisationunitBundle:OrganisationunitStructure','organisationunitStructure')->getQuery();
        try {
            $orgStructure = $qb->getSingleResult();
            $this->returnMessage.= "Deletion of orgunit structure was successful\n";
        } catch (\Doctrine\Orm\NoResultException $e) {
            $this->returnMessage.= "Deletion of Orgunit Structure Failed/No Contents to Delete\n";
        }
        $this->persistOrganisationunitStructure($parents,1);
        $this->entityManager->flush();
        /*
         * Check Clock for time spent
        */
        $organisationunitStructureGenerationTime = $stopwatch->stop('resourceTableGeneration');
        $duration = $organisationunitStructureGenerationTime->getDuration()/60;
        $duration = round($duration, 2);

        if( $duration < 1 ) {
            $durationMessage = ($duration*60).' seconds';
        }else if ( $duration >= 60 ) {
            $durationMessage = ( $duration / 60 ) . " hours";
        }else {
            $durationMessage = $duration . " minutes";
        }
        $this->returnMessage .='Orgunit structure generation completeted in'. $durationMessage .'\n';
        echo $this->returnMessage;
        return $this->returnMessage;
    }

    /**
     * @param $parentOrganisatinunits
     * @param $level
     */
    public function persistOrganisationunitStructure($parentOrganisatinunits, $level) {
        $this->returnMessage .= "Persisting Level ". $level ." successfully!\n";
        $levelEntity = $this->entityManager->getRepository('HrisOrganisationunitBundle:OrganisationunitLevel')->findOneBy(array('level'=>$level));
        if(empty($levelEntity)) {
            $levelEntity = new OrganisationunitLevel();
            $levelEntity->setLevel($level);
            $levelEntity->setName('Level '.$level);
            $this->entityManager->persist($levelEntity);
        }
        if ( sizeof($parentOrganisatinunits) > 0 ) {
            // Store the passed parents
            foreach($parentOrganisatinunits as $key => $organisationunit ) {
                $organisationunitStructure = new OrganisationunitStructure();
                $organisationunitStructure->setOrganisationunit($organisationunit);
                $organisationunitStructure->setLevel($levelEntity);
                /*
                 * Store parents in structure
                 */
                for($incr=1;$incr<$level;$incr++) {
                    $nLevelParent=$this->getParentByNLevelsBack($organisationunit,($level-$incr));
                    call_user_func_array(array($organisationunitStructure,"setLevel${incr}Organisationunit"),array($nLevelParent));
                }
                call_user_func_array(array($organisationunitStructure, "setLevel${level}Organisationunit"),array($organisationunit));
                // Persist orgunit
                $this->entityManager->persist($organisationunitStructure);
            }

            /*
             * Fetch the grand children if exists
            */
            foreach($parentOrganisatinunits as $key=>$organisationunit) {
                $parentOrganisationunitIds[] = $organisationunit->getId();
            }
            $queryBuilder = $this->entityManager->createQueryBuilder();
            $childrenOrgunits = $queryBuilder->select('organisationunit')
                                            ->from('HrisOrganisationunitBundle:Organisationunit','organisationunit')
                                            ->innerJoin('organisationunit.parent','parent')
                                            ->where($queryBuilder->expr()->in('parent.id',$parentOrganisationunitIds))
                                            ->orderBy('parent.id','ASC')
                                            ->addOrderBy('organisationunit.longname','ASC')
                                            ->getQuery()->getResult();
            if( ! empty($childrenOrgunits) ) {$this->persistOrganisationunitStructure($childrenOrgunits,$level+1);}
        }
    }

    /**
     * Get ParentByNLevelsBack
     * Translates into something like, say $level=2
     * $organisationunit->getParent()->getParent();
     *
     * @param $organisationunit
     * @param $level
     * @throws Exception
     * @return Organisationunit
     */
    public function getParentByNLevelsBack($organisationunit,$level) {
        // Translates into something like, say $level=2
        // $organisationunit->getParent()->getParent();
        if($level>1) {
            // Recursively Call persist upper most parent and call lower parent
            $parentNLevelsBack = $this->getParentByNLevelsBack($organisationunit,$level-1);
            if(method_exists($parentNLevelsBack,"getParent")) {
                $parentNLevelsBack = call_user_func_array(array($parentNLevelsBack, "getParent"),$args=Array());
                // Translates into something like $parentNLevelsBack = $parentNLevelsBack->getParent($args);
            }else {
                throw new Exception('Object or method doesn\'t exist');
            }
        }else {
            // Return the parent
            if(method_exists($organisationunit,"getParent")) {
                $parentNLevelsBack = call_user_func_array(array($organisationunit, "getParent"),$args=Array());
                // Translates into something like $parentNLevelsBack = $parentNLevelsBack->getParent($args);
            }else {
                throw new Exception('Object or method doesn\'t exist');
            }
        }
        return $parentNLevelsBack;
    }
}
