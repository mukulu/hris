<?php

namespace Hris\DataQualityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('HrisDataQualityBundle:Default:index.html.twig', array('name' => $name));
    }
}
