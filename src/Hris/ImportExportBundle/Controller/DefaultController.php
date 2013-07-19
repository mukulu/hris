<?php

namespace Hris\ImportExportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('HrisImportExportBundle:Default:index.html.twig', array('name' => $name));
    }
}
