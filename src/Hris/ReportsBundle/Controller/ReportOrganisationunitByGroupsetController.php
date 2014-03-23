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
namespace Hris\ReportsBundle\Controller;

use Hris\ReportsBundle\Form\ReportOrganisationunitByGroupsetType;
use Hris\ReportsBundle\Form\ReportOrganisationunitByLevelsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Report Organisationunit By Groupset controller.
 *
 * @Route("/reports/organisationunit/groupset")
 */
class ReportOrganisationunitByGroupsetController extends Controller
{

    /**
     * Show Report Form for generation of Organisation unit by groupset
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_REPORTORGANISATIONUNITGROUPSET_GENERATE")
     * @Route("/", name="report_organisationunit_groupset")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

        $organisationunitByGroupsetForm = $this->createForm(new ReportOrganisationunitByGroupsetType(),null,array('em'=>$this->getDoctrine()->getManager()));

        return array(
            'organisationunitByGroupForm'=>$organisationunitByGroupsetForm->createView(),
        );
    }

    /**
     * Generate Report for Organisationunit by Groupset
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_REPORTORGANISATIONUNITGROUPSET_GENERATE")
     * @Route("/", name="report_organisationunit_groupset_generate")
     * @Method("PUT")
     * @Template()
     */
    public function generateAction(Request $request)
    {
        $organisationunitByGroupsetForm = $this->createForm(new ReportOrganisationunitByGroupsetType(),null,array('em'=>$this->getDoctrine()->getManager()));
        $organisationunitByGroupsetForm->bind($request);

        if ($organisationunitByGroupsetForm->isValid()) {
            $organisationunitByGroupsetFormData = $organisationunitByGroupsetForm->getData();
            $this->organisationunit = $organisationunitByGroupsetFormData['organisationunit'];
            $this->organisationunitGroupset = $organisationunitByGroupsetFormData['organisationunitGroupset'];
        }

        $this->proccessGroupset();

        return array(
            'organisationunit' => $this->organisationunit,
            'organisationunitGroupset'   => $this->organisationunitGroupset,
            'organisationunitGroupCounts' => $this->organisationunitGroupCounts,
            'title' => $this->title,
        );
    }

    /**
     * Generate a Report Redirect for Organisationunit by Groupset
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_REPORTORGANISATIONUNITGROUPSET_GENERATE")
     * @Route("/redirect", name="report_organisationunit_groupset_generate_redirect")
     * @Method("GET")
     * @Template("HrisReportsBundle:ReportOrganisationunitByGroupset:generate.html.twig")
     */
    public function generateRedirectAction(Request $request)
    {

        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
        $organisationunitId = $this->getRequest()->query->get('organisationunit');
        $organisationunitGroupsetId = $this->getRequest()->query->get('organisationunitGroupset');

        $em = $this->getDoctrine()->getManager();
        $this->organisationunit = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->find($organisationunitId);
        $this->organisationunitGroupset = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroupset')->find($organisationunitGroupsetId);


        $organisationunitByGroupsetForm = $this->createForm(new ReportOrganisationunitByGroupsetType(),null,array('em'=>$this->getDoctrine()->getManager()));
        $organisationunitByGroupsetForm->bind($request);

        $this->proccessGroupset();

        return array(
            'organisationunit' => $this->organisationunit,
            'organisationunitGroupset'   => $this->organisationunitGroupset,
            'organisationunitGroupCounts' => $this->organisationunitGroupCounts,
            'title' => $this->title,
        );
    }

    /**
     * Generate Report for Organisationunit by Group
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_REPORTORGANISATIONUNITGROUPSET_GENERATE")
     * @Route("/organisationunitgroup", name="report_organisationunit_group_generate")
     * @Method("GET")
     * @Template()
     */
    public function generateOrganisationunitGroupAction(Request $request)
    {
        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
        $organisationunitId = $this->getRequest()->query->get('organisationunit');
        $organisationunitGroupId = $this->getRequest()->query->get('organisationunitGroup');

        $em = $this->getDoctrine()->getManager();
        $this->organisationunit = $em->getRepository('HrisOrganisationunitBundle:Organisationunit')->findOneBy(array('id'=>$organisationunitId));
        $this->organisationunitGroup = $em->getRepository('HrisOrganisationunitBundle:OrganisationunitGroup')->findOneBy(array('id'=>$organisationunitGroupId));

        $this->proccessGroup();

        return array(
            'organisationunit' => $this->organisationunit,
            'organisationunitGroup'   => $this->organisationunitGroup,
            'organisationunitStructures' => $this->organisationunitStructures,
            'lowerLevels' => $this->lowerLevels,
            'title' => $this->title,
        );
    }

    /**
     * Process Organisationunit Group report
     */
    public function proccessGroup()
    {
        $this->title = $this->organisationunitGroup->getName(). " under ". $this->organisationunit->getLongname();

        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();

        $this->organisationunitStructures = $queryBuilder->select( 'organisationunitStructure')
            ->from('HrisOrganisationunitBundle:OrganisationunitStructure','organisationunitStructure')
            ->join('organisationunitStructure.organisationunit','organisationunit')
            ->join('organisationunit.organisationunitGroup','organisationunitGroup')
            ->join('organisationunitStructure.level','organisationunitLevel')
            ->where('organisationunitGroup.id = :organisationunitGroupId')
            ->andWhere('organisationunit.active=True')
            ->andWhere('organisationunitLevel.level >= (
                                        SELECT selectedOrganisationunitLevel.level
                                        FROM HrisOrganisationunitBundle:OrganisationunitStructure selectedOrganisationunitStructure
                                        INNER JOIN selectedOrganisationunitStructure.level selectedOrganisationunitLevel
                                        WHERE selectedOrganisationunitStructure.organisationunit=:selectedOrganisationunit )'
            )
            ->andWhere('organisationunitStructure.level'.$this->organisationunit->getOrganisationunitStructure()->getLevel()->getLevel().'Organisationunit=:levelId')
            ->setParameters(array('organisationunitGroupId'=> $this->organisationunitGroup->getId(),'levelId'=>$this->organisationunit->getId(),'selectedOrganisationunit'=>$this->organisationunit->getId()))
            ->getQuery()->getResult();

        // Fetching higher level headings[level<= levelPrefereed] excluding highest level
        $this->lowerLevels = $this->getDoctrine()->getManager()->createQueryBuilder()->select('organisationunitLevel')
            ->from('HrisOrganisationunitBundle:OrganisationunitLevel', 'organisationunitLevel')
            ->andWhere($queryBuilder->expr()->gt('organisationunitLevel.level', $this->organisationunit->getOrganisationunitStructure()->getLevel()->getLevel()))
            ->orderBy('organisationunitLevel.level', 'ASC')->getQuery()->getResult();
    }

    /**
     * Process Organisationunit Groupset report
     */
    public function proccessGroupset()
    {
        $this->title = $this->organisationunit->getLongname().": Organisation Unit Report by ".$this->organisationunitGroupset->getName();

        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
        $this->organisationunitGroupCounts = $queryBuilder->select( 'organisationunitGroup.name,organisationunitGroup.id, COUNT(organisationunit.id) as organisationunitCount ')
            ->from('HrisOrganisationunitBundle:OrganisationunitGroupset','organisationunitGroupset')
            ->join('organisationunitGroupset.organisationunitGroup','organisationunitGroup')
            ->join('organisationunitGroup.organisationunit','organisationunit')
            ->join('organisationunit.organisationunitStructure','organisationunitStructure')
            ->join('organisationunitStructure.level','organisationunitLevel')
            ->where('organisationunitGroupset.id = :organisationunitGroupsetId')
            ->andWhere('organisationunit.active=True')
            ->andWhere('organisationunitLevel.level >= (
                                        SELECT selectedOrganisationunitLevel.level
                                        FROM HrisOrganisationunitBundle:OrganisationunitStructure selectedOrganisationunitStructure
                                        INNER JOIN selectedOrganisationunitStructure.level selectedOrganisationunitLevel
                                        WHERE selectedOrganisationunitStructure.organisationunit=:selectedOrganisationunit )'
            )
            ->groupBy('organisationunitGroup.name,organisationunitGroup.id')
            ->andWhere('organisationunitStructure.level'.$this->organisationunit->getOrganisationunitStructure()->getLevel()->getLevel().'Organisationunit=:levelId')
            ->setParameters(array('organisationunitGroupsetId'=> $this->organisationunitGroupset->getId(),'levelId'=>$this->organisationunit->getId(),'selectedOrganisationunit'=>$this->organisationunit->getId()))
            ->getQuery()->getResult();
    }

    /**
     * @var Organisationunit
     */
    private $organisationunit;

    /**
     * @var OrganisationunitGroup
     */
    private $organisationunitGroup;

    /**
     * @var Collection
     */
    private $organisationunitGroupset;

    /**
     * @var Array
     */
    private $organisationunitGroupCounts;

    /**
     * @var Collection
     */
    private $organisationunitStructures;

    /**
     * @var Collection
     */
    private $lowerLevels;

    /**
     * @var string
     */
    private $title;

}
