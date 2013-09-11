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
 * @author Wilfred Felix Senyoni <senyoni@gmail.com>
 *
 */
namespace Hris\ReportsBundle\Controller;

use Hris\OrganisationunitBundle\Entity\Organisationunit;
use Hris\FormBundle\Entity\Field;
use Hris\ReportsBundle\Form\ReportRecordsType;
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
 * Report Records controller.
 *
 * @Route("/reports/records")
 */
class ReportRecordsController extends Controller
{

    /**
     * Show Report Records
     *
     * @Route("/", name="report_records")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

        $recordsForm = $this->createForm(new ReportRecordsType(),null,array('em'=>$this->getDoctrine()->getManager()));

        return array(
            'recordsForm'=>$recordsForm->createView(),
        );
    }

    /**
     * Generate records reports
     *
     * @Route("/", name="report_records_generate")
     * @Method("PUT")
     * @Template()
     */
    public function generateAction(Request $request)
    {
        $recordsForm = $this->createForm(new ReportRecordsType(),null,array('em'=>$this->getDoctrine()->getManager()));
        $recordsForm->bind($request);

        if ($recordsForm->isValid()) {
            $recordsFormData = $recordsForm->getData();
            $organisationUnit = $recordsFormData['organisationunit'];
            $forms = $recordsFormData['forms'];
            $withLowerLevels = $recordsFormData['withLowerLevels'];
        }




        return array(
        );
    }
}
