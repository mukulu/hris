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

namespace Hris\FormBundle\EventListener;

use Hris\FormBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    /**
     * @param \Hris\FormBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild('Form Module',
            array(
                'uri'=>'#formmodule',
                'extras'=>array('tag'=>'div'),
                'name'=>'Form Module',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
        $formModule = $menu->getChild('Form Module');

        $formModule->addChild('Fields',
            array('route'=>'field_list',
                'extras'=>array('tag'=>'div'),
                'name' =>'Fields',
                'attributes'=> array('class'=>'accordion-group')
            )
        );
        $formModule->addChild('Field Groups',
            array('route'=>'fieldgroup_list',
                'extras'=>array('tag'=>'div'),
                'name' =>'Field Groups',
                'attributes'=> array('class'=>'accordion-group')
            )
        );
        $formModule->addChild('Field Option Groupsets',
            array('route'=>'fieldoptiongroupset_list',
                  'extras'=>array('tag'=>'div'),
                  'name' =>'Field Option Groupsets',
                  'attributes'=> array('class'=>'accordion-group')
            )
        );
        $formModule->addChild('Relational Filters',
            array('route'=>'relationalfilter_list',
                  'extras'=>array('tag'=>'div'),
                  'name' =>'Relational Filters',
                  'attributes'=> array('class'=>'accordion-group')
            )
        );
        $formModule->addChild('Arithmetic Filters',
            array('route'=>'arithmeticfilter_list',
                  'extras'=>array('tag'=>'div'),
                  'name' =>'Arithmetic Filters',
                  'attributes'=> array('class'=>'accordion-group')
            )
        );
        $formModule->addChild('Friendly Reports',
            array('route'=>'friendlyreport_list',
                  'extras'=>array('tag'=>'div'),
                  'name' =>'Friendly Reports',
                  'attributes'=> array('class'=>'accordion-group')
            )
        );
        $formModule->addChild('Forms',
            array('route'=>'form_list',
                  'extras'=>array('tag'=>'div'),
                  'name' =>'Forms',
                  'attributes'=> array('class'=>'accordion-group')
            )
        );

        $menu->addChild('Resource Tables',
            array(
                'uri'=>'#resourcetablemodule',
                'extras'=>array('tag'=>'div'),
                'name'=>'Resource Tables',
                'attributes'=> array('class'=>'accordion-group'),
            )
        );
        $resourceTableModule = $menu->getChild('Resource Tables');

        $resourceTableModule->addChild('Resource Table',
            array('route'=>'resourcetable_list',
                'extras'=>array('tag'=>'div'),
                'name' =>'Resource Table',
                'attributes'=> array('class'=>'accordion-group')
            )
        );

    }
}