<?php
/*
 *
 * Copyright 2012 Human Resource Information System
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 * @since 2012
 * @author John Francis Mukulu <john.f.mukulu@gmail.com>
 *
 */
namespace Hris\DashboardBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hris\DashboardBundle\Entity\Settings;
use Hris\DashboardBundle\Form\SettingsType;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Settings controller.
 *
 * @Route("/settings")
 */
class SettingsController extends Controller
{
    /**
     * Finds and displays a Settings entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_SETTINGS_SHOW")
     * @Route("/{username}", requirements={"username"="\w+"}, name="settings_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($username)
    {
        $em = $this->getDoctrine()->getManager();
        // Create new settings if nothing found!
        try {
            $entity = $em->createQueryBuilder()->select('settings')
                ->from('HrisDashboardBundle:Settings', 'settings')
                ->innerJoin('settings.user','user')
                ->where('user.username=:username')
                ->setParameter('username',$username)
                ->getQuery()->getSingleResult();
        } catch (\Doctrine\Orm\NoResultException $e) {

        }



        if (!$entity) {
            return $this->redirect($this->generateUrl('settings_new', array('username' => $username)));
        }

        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Displays a form to create a new Settings entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_SETTINGS_CREATE")
     * @Route("/{username}/new", requirements={"username"="\w+"}, name="settings_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($username)
    {
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($this->getUser());

        // Create new settings if nothing found!
        try {
            $entity = $em->getRepository('HrisDashboardBundle:Settings')->findOneBy(array('user'=>$user));
        } catch (\Doctrine\Orm\NoResultException $e) {
            $entity = new Settings();
        }
        if(empty($entity)) $entity = new Settings();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'user'=>$user,
        );
    }

    /**
     * Creates a new Settings entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_SETTINGS_CREATE")
     * @Route("/{username}/create", name="settings_create")
     * @Method("POST")
     * @Template("HrisDashboardBundle:Settings:new.html.twig")
     */
    public function createAction(Request $request,$username)
    {
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($this->getUser());
        // Create new settings
        $entity = $em->getRepository('HrisDashboardBundle:Settings')->findOneBy(array('user'=>$user));
        if(empty($entity)) $entity = new Settings();
        $entity->setUser($user);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();


            return $this->redirect($this->generateUrl('settings_show', array('username' => $user->getUsername())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'user'=>$user,
        );
    }

    /**
     * Deletes a Settings entity.
     *
     * @Secure(roles="ROLE_SUPER_USER,ROLE_SETTINGS_DELETE")
     * @Route("/{id}", name="settings_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HrisDashboardBundle:Settings')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Settings entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('settings'));
    }

    /**
     * Creates a form to delete a Settings entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('settings_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Creates a form to create a Settings entity.
     *
     * @param Settings $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Settings $entity)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($this->getUser());

        $form = $this->createForm(new SettingsType(), $entity, array(
            'action' => $this->generateUrl('settings_create', array('username' => $user->getUsername())),
            'method' => 'POST',
        ));

        return $form;
    }

}
