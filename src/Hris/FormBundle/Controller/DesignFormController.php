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
 * @Route("/designform")
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
    
    /**
     * Edits an existing Form entity.
     *
     * @Route("/{id}", name="design_update")
     * @Method("PUT")
     * @Template("HrisFormBundle:Form:index.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('HrisFormBundle:Form')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Form entity.');
    	}
    
    	$editForm = $this->createForm(new DesignFormType(), $entity);
    	$editForm->bind($request);
    
    	if ($editForm->isValid()) {
    		$em->persist($entity);
    		$em->flush();
    
    		return $this->redirect($this->generateUrl('form_design', array('id' => $id)));
    	}
    
    	return array(
    			'entity'      => $entity,
    			'form'   => $editForm->createView(),
    	);
    }

}