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
 * Reused from FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * @since 2012
 * @author John Francis Mukulu <john.f.mukulu@gmail.com>
 *
 */

namespace Hris\UserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Group RESTful controller managing group CRUD
 *
 * @Route("/group", name="hris_user_group")
 */
class GroupController extends ContainerAware
{
    /**
     * Show all groups
     *
     * @Route("/", name="user_group")
     * @Route("/list", name="user_group_list")
     * @Method("GET")
     * @Template()
     */
    public function listAction()
    {
        $groups = $this->container->get('fos_user.group_manager')->findGroups();

        return $this->container->get('templating')->renderResponse('HrisUserBundle:Group:list.html.'.$this->getEngine(), array('groups' => $groups));
    }

    /**
     * Finds and displays a Group.
     *
     * @Route("/{id}", requirements={"id"="\d+"}, name="user_group_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $group = $this->findGroupBy('id', $id);

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Group:show.html.'.$this->getEngine(), array('group' => $group));
    }

    /**
     * Displays a form to edit an existing Group.
     *
     * @Route("/{id}/edit", requirements={"id"="\d+"}, name="user_group_edit")
     * @Method("GET|PUT")
     * @Template()
     */
    public function editAction($id)
    {
        $group = $this->findGroupBy('id', $id);
        $form = $this->container->get('fos_user.group.form');
        $formHandler = $this->container->get('fos_user.group.form.handler');

        $process = $formHandler->process($group);
        if ($process) {
            $this->setFlash('fos_user_success', 'group.flash.updated');
            $groupUrl =  $this->container->get('router')->generate('user_group_show', array('id' => $group->getId()));

            return new RedirectResponse($groupUrl);
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Group:edit.html.'.$this->getEngine(), array(
            'form'      => $form->createview(),
            'group'     =>$group,
        ));
    }

    /**
     * Displays a form to create a new Group.
     *
     * @Route("/new", name="user_group_new")
     * @Method("GET|POST")
     * @Template()
     */
    public function newAction()
    {
        $form = $this->container->get('fos_user.group.form');
        $formHandler = $this->container->get('fos_user.group.form.handler');

        $process = $formHandler->process();
        if ($process) {
            $this->setFlash('fos_user_success', 'group.flash.created');
            $parameters = array('groupname' => $form->getData('group')->getName());
            $url = $this->container->get('router')->generate('user_group_show', $parameters);

            return new RedirectResponse($url);
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Group:new.html.'.$this->getEngine(), array(
            'form' => $form->createview(),
        ));
    }

    /**
     * Deletes a Group.
     *
     * @Route("/{id}/delete", requirements={"id"="\d+"}, name="user_group_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $group = $this->findGroupBy('id', $id);
        $this->container->get('fos_user.group_manager')->deleteGroup($group);
        $this->setFlash('fos_user_success', 'group.flash.deleted');

        return new RedirectResponse($this->container->get('router')->generate('fos_user_group_list'));
    }

    /**
     * Find a group by a specific property
     *
     * @param string $key   property name
     * @param mixed  $value property value
     *
     * @throws NotFoundException                    if user does not exist
     * @return \FOS\UserBundle\Model\GroupInterface
     */
    protected function findGroupBy($key, $value)
    {
        if (!empty($value)) {
            $group = $this->container->get('fos_user.group_manager')->{'findGroupBy'}(array("$key"=>$value));
        }

        if (empty($group)) {
            throw new NotFoundHttpException(sprintf('The group with "%s" does not exist for value "%s"', $key, $value));
        }

        return $group;
    }

    protected function getEngine()
    {
        return $this->container->getParameter('fos_user.template.engine');
    }

    /**
     * @param string $action
     * @param string $value
     */
    protected function setFlash($action, $value)
    {
        $this->container->get('session')->getFlashBag()->set($action, $value);
    }
}
