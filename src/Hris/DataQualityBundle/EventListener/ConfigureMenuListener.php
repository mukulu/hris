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

namespace Hris\DataQualityBundle\EventListener;

use Hris\DataQualityBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    /**
     * @param \Hris\DataQualityBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild('DataQuality Module',
            array(
                'uri'=>'#dataqualitymodule',
                'extras'=>array('tag'=>'div'),
                'name'=>'DataQuality Module',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
        $dataQualityModule = $menu->getChild('DataQuality Module');


        $dataQualityModule->addChild('Validations',
            array('route'=>'validation_list',
                'extras'=>array('tag'=>'div'),
                'name'=>'Validations',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
    }
}