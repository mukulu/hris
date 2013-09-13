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

use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Hris\FormBundle\Entity\Field;
use Hris\ReportsBundle\Form\ReportAggregationType;
use Hris\ReportsBundle\Form\ReportFriendlyReportType;
use Hris\ReportsBundle\Form\ReportFriendlyType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\ReportsBundle\Entity\Report;
use Hris\ReportsBundle\Form\ReportType;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Zend\Json\Expr;

/**
 * Report Friendly Report controller for generation of friendlier
 * standard/generic reports.
 *
 * @Route("/reports/friendlyreport")
 */
class ReportFriendlyReportController extends Controller
{

    /**
     * Show Report Aggregation
     *
     * @Route("/", name="report_friendlyreport")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

        $friendlyReportForm = $this->createForm(new ReportFriendlyReportType(),null,array('em'=>$this->getDoctrine()->getManager()));

        return array(
            'friendlyReportForm'=>$friendlyReportForm->createView(),
        );
    }

    /**
     * Generate friendly reports
     *
     * @Route("/", name="report_friendlyreport_generate")
     * @Method("PUT")
     * @Template()
     */
    public function generateAction(Request $request)
    {
        $friendlyReportForm = $this->createForm(new ReportFriendlyReportType(),null,array('em'=>$this->getDoctrine()->getManager()));
        $friendlyReportForm->bind($request);

        if ($friendlyReportForm->isValid()) {
            $friendlyReportFormData = $friendlyReportForm->getData();
            $friendlyReport = $friendlyReportFormData['genericReport'];
            $organisationUnit = $friendlyReportFormData['organisationunit'];
            $forms = $friendlyReportFormData['forms'];
            $organisationunitGroupset = $friendlyReportFormData['organisationunitGroupset'];
            $organisationunitGroup = $friendlyReportFormData['organisationunitGroup'];
        }

        return array(
            'friendlyReport'=>$friendlyReport,
            'organisationUnit' => $organisationUnit,
            'forms' => $forms,
            'organisationunitGroupset' => $organisationunitGroupset,
            'organisationunitGroup' => $organisationunitGroup,
        );
    }

}
