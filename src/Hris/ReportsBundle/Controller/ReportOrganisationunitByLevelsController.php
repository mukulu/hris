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

use Hris\ReportsBundle\Form\ReportOrganisationunitByLevelsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Report Organisationunit By Levels controller.
 *
 * @Route("/reports/organisationunit/levels")
 */
class ReportOrganisationunitByLevelsController extends Controller
{

    /**
     * Show Report Form for generation of Organisation unit by levels
     *
     * @Secure(roles="ROLE_REPORTORGANISATIONUNITLEVELS_GENERATE,ROLE_USER")
     * @Route("/", name="report_organisationunit_levels")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

        $organisationunitByLevelsForm = $this->createForm(new ReportOrganisationunitByLevelsType(),null,array('em'=>$this->getDoctrine()->getManager()));

        return array(
            'organisationunitByLevelsForm'=>$organisationunitByLevelsForm->createView(),
        );
    }

    /**
     * Generate Report for Organisationunit by Levels
     *
     * @Secure(roles="ROLE_REPORTORGANISATIONUNITLEVELS_GENERATE,ROLE_USER")
     * @Route("/", name="report_organisationunit_levels_generate")
     * @Method("PUT")
     * @Template()
     */
    public function generateAction(Request $request)
    {
        $organisationunitByLevelsForm = $this->createForm(new ReportOrganisationunitByLevelsType(),null,array('em'=>$this->getDoctrine()->getManager()));
        $organisationunitByLevelsForm->bind($request);

        if ($organisationunitByLevelsForm->isValid()) {
            $organisationunitByLevelsFormData = $organisationunitByLevelsForm->getData();
            $organisationunit = $organisationunitByLevelsFormData['organisationunit'];
            $level = $organisationunitByLevelsFormData['organisationunitLevel'];
        }
        /*
		 * Filter out organisationunit by selected parent and desired level
		 */
        $selectedParentStructure = $this->getDoctrine()->getManager()->getRepository('HrisOrganisationunitBundle:OrganisationunitStructure')->findOneBy(array('organisationunit'=>$organisationunit));
        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
        $organisationunitStructureObjects = $queryBuilder->select('organisationunitStructure')
                                        ->from('HrisOrganisationunitBundle:OrganisationunitStructure','organisationunitStructure')
                                        ->join('organisationunitStructure.organisationunit','organisationunit')
                                        ->where('organisationunitStructure.level'.$selectedParentStructure->getLevel()->getLevel().'Organisationunit=:organisationunit')
                                        ->andWhere('organisationunitStructure.level=:level')
                                        //->andWhere('organisationunit.active=True')
                                        ->setParameters(array(
                                                'organisationunit'=>$organisationunit,
                                                'level'=>$level
                                        ))
                                        ->getQuery()->getResult();
        // Fetching higher level headings[level<= levelPrefereed] excluding highest level
        $lowerLevels = $queryBuilder->select('DISTINCT(organisationunitLevel.level),organisationunitLevel.name')
                                    ->from('HrisOrganisationunitBundle:OrganisationunitLevel','organisationunitLevel')
                                    ->where($queryBuilder->expr()->lte('organisationunitLevel.level',':lowerLevel'))
                                    ->andWhere($queryBuilder->expr()->gt('organisationunitLevel.level',':selectedLevel'))
                                    ->setParameters(array(
                                            'lowerLevel'=>$level->getLevel(),
                                            'selectedLevel'=>$selectedParentStructure->getLevel()->getLevel()
                                    ))
                                    ->orderBy('organisationunitLevel.level','ASC')->getQuery()->getResult();

        $selectedOrgunitName = $organisationunit->getLongname();
        $title = "List of All " . $lowerLevels[0]['name'] . " Under ". $selectedOrgunitName;
        return array(
            'title' => $title,
            'lowerLevels' => $lowerLevels,
            'orgunitStructureObjects'   => $organisationunitStructureObjects,
            'selectedLevel' => $level,
        );
    }

}
