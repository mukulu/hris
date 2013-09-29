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
            array('route'=>'report_employeerecords',
                'extras'=>array('tag'=>'div'),
                'name'=>'Records Report',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
        $reportsModule->addChild('Aggregated Report',
            array('route'=>'report_aggregation',
                'extras'=>array('tag'=>'div'),
                'name'=>'Aggregated Report',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
        $reportsModule->addChild('Generic Report',
            array('route'=>'report_friendlyreport',
                'extras'=>array('tag'=>'div'),
                'name'=>'Generic Report',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
        $reportsModule->addChild('Completeness Report',
            array('route'=>'report_organisationunit_completeness',
                'extras'=>array('tag'=>'div'),
                'name'=>'Completeness Report',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
        $reportsModule->addChild('History & Training',
            array('route'=>'report_historytraining',
                'extras'=>array('tag'=>'div'),
                'name'=>'History & Training',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
        $reportsModule->addChild('Orgunit By Levels',
            array('route'=>'report_organisationunit_levels',
                'extras'=>array('tag'=>'div'),
                'name'=>'Orgunit By Levels Report',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
        $reportsModule->addChild('Orgunit By Groupset',
            array('route'=>'report_organisationunit_groupset',
                'extras'=>array('tag'=>'div'),
                'name'=>'Orgunit By Groupset Report',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
        $reportsModule->addChild('Shared Reports',
            array('route'=>'report_list',
                'extras'=>array('tag'=>'div'),
                'name'=>'Shared Reports',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
    }
}