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

        $menu->addChild('Reports Module',
            array(
                'uri'=>'#reportsmodule',
                'extras'=>array('tag'=>'div'),
                'name'=>'Reports Module',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );

        $reportsModule = $menu->getChild('Reports Module');


        $reportsModule->addChild('Records Report',
            array('uri'=>'#recordsreport',
                'extras'=>array('tag'=>'li'),
                'name'=>'Records Report',
                'attributes'=> array('class'=>'nav nav-list','id'=>'recordsreport'),
            )
        );
        $reportsModule->addChild('Aggregated Report',
            array('uri'=>'#aggregatedreport',
                'extras'=>array('tag'=>'li'),
                'name'=>'Aggregated Report',
                'attributes'=> array('class'=>'nav nav-list','id'=>'aggregatedreport'),
            )
        );
        $reportsModule->addChild('Generic Report',
            array('uri'=>'#genericreport',
                'extras'=>array('tag'=>'li'),
                'name'=>'Generic Report',
                'attributes'=> array('class'=>'nav nav-list','id'=>'genericreport'),
            )
        );
        $reportsModule->addChild('Completeness Report',
            array('uri'=>'#completenessreport',
                'extras'=>array('tag'=>'li'),
                'name'=>'Completeness Report',
                'attributes'=> array('class'=>'nav nav-list','id'=>'completenessreport'),
            )
        );
        $reportsModule->addChild('History&Training Report',
            array('uri'=>'#historyandtrainingreport',
                'extras'=>array('tag'=>'li'),
                'name'=>'History&Training Report',
                'attributes'=> array('class'=>'nav nav-list','id'=>'historyandtrainingreport'),
            )
        );
        $reportsModule->addChild('Orgunit By Levels Report',
            array('uri'=>'#orgunitbylevelreport',
                'extras'=>array('tag'=>'li'),
                'name'=>'Orgunit By Levels Report',
                'attributes'=> array('class'=>'nav nav-list','id'=>'orgunitbylevelreport'),
            )
        );
        $reportsModule->addChild('Orgunit By Groupset Report',
            array('uri'=>'#orgunitgroupsetreport',
                'extras'=>array('tag'=>'li'),
                'name'=>'Orgunit By Groupset Report',
                'attributes'=> array('class'=>'nav nav-list','id'=>'orgunitgroupsetreport'),
            )
        );
        $reportsModule->addChild('Shared Reports',
            array('route'=>'report_list',
                'extras'=>array('tag'=>'li'),
                'name'=>'Shared Reports',
                'attributes'=> array('class'=>'nav nav-list','id'=>'report_list'),
            )
        );
    }
}