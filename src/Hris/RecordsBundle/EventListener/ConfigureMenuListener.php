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

namespace Hris\RecordsBundle\EventListener;

use Hris\RecordsBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    /**
     * @param \Hris\RecordsBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild('Data Module', array(
                'uri'=>'#datamodule',
                'extras'=>array('tag'=>'div'),
                'name'=>'Data Module',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );

        $recordsModule = $menu->getChild('Data Module');

        $recordsModule->addChild('Data Entry',
            array('route'=>'record_form_list',
                  'extras'=>array('tag'=>'div'),
                  'name'=>'Data Entry',
                  'attributes'=> array('class'=>'accordion-group'),
            )
        );
        $recordsModule->addChild('Update Records',
            array('route'=>'record_create',
                  'extras'=>array('tag'=>'div'),
                  'name'=>'Reports Module',
                  'attributes'=> array('class'=>'accordion-group'),
            )
        );
        $recordsModule->addChild('Data Validation',
            array('uri'=>'#datavalidation',
                'extras'=>array('tag'=>'div'),
                'name'=>'Update Records',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
    }
}