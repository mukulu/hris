<?php

namespace Hris\ReportsBundle\Controller;

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

        $defaultData = array(
        );

        $aggregationForm = $this->createFormBuilder($defaultData)
            ->add('organisationunit', 'entity',
                array('class' => 'HrisOrganisationunitBundle:Organisationunit' )
            )
            ->add('forms', 'entity',
                array('class' => 'HrisFormBundle:Form')
            )
            ->add('fields', 'entity',
                array( 'class' => 'HrisFormBundle:Field')
            )
            ->add('Generate', 'submit')
            ->getForm();

        return array(
            'aggregationForm'=>$aggregationForm->createView(),
        );
    }

}
