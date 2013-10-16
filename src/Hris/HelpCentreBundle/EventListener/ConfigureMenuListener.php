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

namespace Hris\HelpCentreBundle\EventListener;

use Hris\HelpCentreBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    /**
     * @param \Hris\HelpCentreBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild('HelpCentre Module',
            array(
                'uri'=>'#helpcentremodule',
                'extras'=>array('tag'=>'div'),
                'name'=>'HelpCentre Module',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );

        $helpCentreModule = $menu->getChild('HelpCentre Module');

        $helpCentreModule->addChild('Help Topics',
            array('route'=>'help_topic',
                  'extras'=>array('tag'=>'div'),
                  'name'=>'Help Topics',
                  'attributes'=> array('class'=>'accordion-group'),
            )
        );

        $helpCentreModule->addChild('Help Chapters',
            array('route'=>'help_chapter',
                'extras'=>array('tag'=>'div'),
                'name'=>'Help Chapters',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
    }
}