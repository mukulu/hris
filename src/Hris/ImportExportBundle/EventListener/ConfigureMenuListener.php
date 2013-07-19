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

namespace Hris\ImportExportBundle\EventListener;

use Hris\ImportExportBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    /**
     * @param \Hris\ImportExportBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild('Import/Export Management', array(
                'uri'=>'#importexportmanagement','attributes'=>
                array('class'=>'nav-header')
            )
        );

        $menu->addChild('Import Data',array('uri'=>'#importdata'));
        $menu->addChild('Export Data', array('uri'=>'#exportdata'));
        $menu->addChild('Export Metadata', array('uri'=>'#metadata'));
        $menu->addChild('Import History', array('route'=>'importexport_history_list'));


        $menu->addChild('datamanagementsplit',array('attributes'=>array('class'=>'divider')));
    }
}