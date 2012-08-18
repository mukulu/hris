<?php

namespace Udsm\Hrhis\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('UdsmHrhisAuthBundle:Default:index.html.twig', array('name' => $name));
    }
}
