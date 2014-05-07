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

namespace Hris\IntergrationBundle\EventListener;

use Hris\IntergrationBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    /**
     * @param \Hris\IntergrationBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild('Intergration Module',
            array(
                'uri'=>'#intergrationmodule',
                'extras'=>array('tag'=>'div'),
                'name'=>'Intergration Module',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
        $integrationModule = $menu->getChild('Intergration Module');


        $integrationModule->addChild('DHIS Integration',
            array('route'=>'dhisdataconnection',
                'extras'=>array('tag'=>'div'),
                'name'=>'DHIS Integration',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
        $integrationModule->addChild('TIIS Integration',
            array('route'=>'tiisdataconnection',
                'extras'=>array('tag'=>'div'),
                'name'=>'TIIS Integration',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
    }
}