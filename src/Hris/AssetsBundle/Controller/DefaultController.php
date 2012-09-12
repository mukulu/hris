<?php

namespace Hris\AssetsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('HrisAssetsBundle:Default:index.html.twig', array('name' => $name));
    }
}
