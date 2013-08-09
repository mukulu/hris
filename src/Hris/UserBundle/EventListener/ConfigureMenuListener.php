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

namespace Hris\UserBundle\EventListener;

use Hris\UserBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    /**
     * @param \Hris\UserBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild('User Module',
            array(
                'uri'=>'#usermodule',
                'extras'=>array('tag'=>'div'),
                'name'=>'User Module',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
        $userModule = $menu->getChild('User Module');


        $userModule->addChild('System Users',
            array('route'=>'user_list',
                  'extras'=>array('tag'=>'div'),
                  'name'=>'System Users',
                  'attributes'=> array('class'=>'accordion-group'),
            )
        );
//        $userModule->addChild('System Roles',
//            array('uri'=>'#systemroles',
//                  'extras'=>array('tag'=>'div'),
//                  'name'=>'System Roles',
//                  'attributes'=> array('class'=>'accordion-group'),
//            )
//        );
    }
}