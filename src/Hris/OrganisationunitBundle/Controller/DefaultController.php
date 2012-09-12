<?php

namespace Hris\OrganisationunitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('HrisOrganisationunitBundle:Default:index.html.twig', array('name' => $name));
    }
}
