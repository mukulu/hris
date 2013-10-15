<?php

namespace Hris\HelpCentreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * HelpCentre controller.
 *
 * @Route("/help/helpcentre")
 */
class HelpCentreController extends Controller
{

    /**
     * Lists all Topics and it's chapters.
     *
     * @Route("/", name="help_helpcentre")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HrisHelpCentreBundle:Topic')->findAll();


        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Chapter contents.
     *
     * @Route("/{id}", name="help_chapter_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisHelpCentreBundle:Chapter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chapter entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

}
