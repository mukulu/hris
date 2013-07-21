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

namespace Hris\OrganisationunitBundle\EventListener;

use Hris\OrganisationunitBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    /**
     * @param \Hris\OrganisationunitBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild('Orgunit Module',
            array(
                'uri'=>'#orgunitmodule',
                'extras'=>array('tag'=>'div'),
                'name'=>'Orgunit Module',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
        $orgunitModule = $menu->getChild('Orgunit Module');


        $orgunitModule->addChild('Organisationunits',
            array('route'=>'organisationunit_list',
                'extras'=>array('tag'=>'li'),
                'name'=>'Organisationunits',
                'attributes'=> array('class'=>'nav nav-list','id'=>'organisationunits'),
            )
        );
        $orgunitModule->addChild('Organisationunit Groups',
            array('route'=>'organisationunitgroup_list',
                'extras'=>array('tag'=>'li'),
                'name'=>'Organisationunit Groups',
                'attributes'=> array('class'=>'nav nav-list','id'=>'organisationunitgroups'),
            )
        );
        $orgunitModule->addChild('Organisationunit Groupsets',
            array('route'=>'organisationunitgroupset_list',
                'extras'=>array('tag'=>'li'),
                'name'=>'Organisationunit Groupsets',
                'attributes'=> array('class'=>'nav nav-list','id'=>'organisationunitgroupsets'),
            )
        );
        $orgunitModule->addChild('Organisationunit Levels',
            array('uri'=>'#organisationunitlevel',
                'extras'=>array('tag'=>'li'),
                'name'=>'Organisationunit Levels',
                'attributes'=> array('class'=>'nav nav-list','id'=>'organisationunitlevels'),
            )
        );
    }
}