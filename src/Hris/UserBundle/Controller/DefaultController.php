<?php

namespace Hris\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('HrisUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
