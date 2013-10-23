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

namespace Hris\UserBundle\Form\Handler;

use FOS\UserBundle\Model\GroupInterface;
use FOS\UserBundle\Model\GroupManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class GroupFormHandler
{
    protected $request;
    protected $groupManager;
    protected $form;

    public function __construct(FormInterface $form, Request $request, GroupManagerInterface $groupManager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->groupManager = $groupManager;
    }

    public function process(GroupInterface $group = null)
    {
        if (null === $group) {
            $group = $this->groupManager->createGroup('');
        }

        $this->form->setData($group);

        if ('PUT' === $this->request->getMethod()) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($group);

                return true;
            }
        }

        return false;
    }

    protected function onSuccess(GroupInterface $group)
    {
        $this->groupManager->updateGroup($group);
    }
}
