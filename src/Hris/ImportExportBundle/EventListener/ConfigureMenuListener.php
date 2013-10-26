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

        $menu->addChild('ImportExport Module', array(
                'uri'=>'#importexport',
                'extras'=>array('tag'=>'div'),
                'name'=>'Import-Export Management',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );

        $importexportModule = $menu->getChild('ImportExport Module');

        $importexportModule->addChild('Import Data',
            array('route'=>'importexport_import',
                'extras'=>array('tag'=>'div'),
                'name'=>'Import Data',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
        $importexportModule->addChild('Export Data',
            array('route'=>'importexport_export',
                'extras'=>array('tag'=>'div'),
                'name'=>'Export Data',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
//        $importexportModule->addChild('Export Metadata',
//            array('route'=>'importexport_exportmetadata',
//                'extras'=>array('tag'=>'div'),
//                'name'=>'Export Metadata',
//                'attributes'=> array('class'=>'accordion-group'),
//            )
//        );
        $importexportModule->addChild('Import History',
            array('route'=>'importexport_history_list',
                'extras'=>array('tag'=>'div'),
                'name'=>'Import History',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
    }
}