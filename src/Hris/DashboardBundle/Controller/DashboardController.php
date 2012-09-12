<?php

namespace Hris\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('HrisDashboardBundle:Dashboard:dashboard.html.twig', array('name' => $name));
    }
}
