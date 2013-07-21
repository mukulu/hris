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

namespace Hris\ReportsBundle\EventListener;

use Hris\ReportsBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    /**
     * @param \Hris\ReportsBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild('Reports Module', array(
                'uri'=>'#reportsmodule','attributes'=>
                array('class'=>'nav-header')
            )
        );

        $menu->addChild('Records Report',array('uri'=>'#recordsreport'));
        $menu->addChild('Aggregated Report',array('uri'=>'#aggregatedreport'));
        $menu->addChild('Generic Report',array('uri'=>'#genericreport'));
        $menu->addChild('Completeness Report',array('uri'=>'#completenessreport'));
        $menu->addChild('History&Training Report',array('uri'=>'#historyandtrainingreport'));
        $menu->addChild('Orgunit By Levels Report',array('uri'=>'#orgunitbylevelreport'));
        $menu->addChild('Orgunit By Groupset Report',array('uri'=>'#orgunitgroupsetreport'));
        $menu->addChild('Shared Reports',array('route'=>'report_list'));
        $menu->addChild('reportssplit',array('attributes'=>array('class'=>'divider')));
    }
}