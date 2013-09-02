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
 * @author Wilfred Felix Senyoni <senyoni@gmail.com>
 *
 */
namespace Hris\ReportsBundle\Controller;

use Hris\ReportsBundle\Form\ReportAggregationType;
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
 * @Route("/reports/aggregation")
 */
class ReportAggregationController extends Controller
{

    /**
     * Show Report Aggregation
     *
     * @Route("/", name="report_aggregation")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

        $aggregationForm = $this->createForm(new ReportAggregationType(),null,array('em'=>$this->getDoctrine()->getManager()));

        return array(
            'aggregationForm'=>$aggregationForm->createView(),
        );
    }

    /**
     * Generate aggregated reports
     *
     * @Route("/", name="report_aggregation_generate")
     * @Method("PUT")
     * @Template()
     */
    public function generateAction(Request $request)
    {
        $aggregationForm = $this->createForm(new ReportAggregationType(),null,array('em'=>$this->getDoctrine()->getManager()));
        $aggregationForm->bind($request);

        if ($aggregationForm->isValid()) {
            $aggregationFormData = $aggregationForm->getData();
            $organisationunit = $aggregationFormData['organisationunit'];
            $forms = $aggregationFormData['forms'];
            $fields = $aggregationFormData['fields'];
        }

        return array(
            'organisationunit' => $organisationunit,
            'forms'   => $forms,
            'fields' => $fields,
        );
    }

}
