<?php

namespace Hris\RecordsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('HrisRecordsBundle:Default:index.html.twig', array('name' => $name));
    }
}
