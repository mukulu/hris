<?php

namespace Hris\FormBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\FormBundle\Entity\Field;
use Hris\FormBundle\Form\FieldType;
use Hris\FormBundle\Form\DesignFormType;

/**
 * Design Form controller.
 *
 * @Route("/form")
 */
class DesignFormController extends Controller
{
	/**
     * Design custom form for data entry.
     *
     * @Route("/{id}/design", name="form_design")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HrisFormBundle:Form')->find($id);
        $editForm = $this->createForm(new DesignFormType(), $entity);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Form entity.');
        }

        return array(
            'entity'    => $entity,
        	'form'   	=> $editForm->createView(),
        );
    }

}