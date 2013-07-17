<?php

namespace Hris\IndicatorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('HrisIndicatorBundle:Default:index.html.twig', array('name' => $name));
    }
}
