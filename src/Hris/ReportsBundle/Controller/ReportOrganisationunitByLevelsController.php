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

use Hris\ReportsBundle\Form\ReportAggregationType;
use Hris\ReportsBundle\Form\ReportOrganisationunitByLevelsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\ReportsBundle\Entity\Report;
use Hris\ReportsBundle\Form\ReportType;

/**
 * Report Aggregation controller.
 *
 * @Route("/reports/organisationunit/levels")
 */
class ReportOrganisationunitByLevelsController extends Controller
{

    /**
     * Show Report Form for generation of Organisation unit by levels
     *
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

        return array(
            'organisationunit' => $organisationunit,
            'organisationunitLevel'   => $level,
        );
    }

}
