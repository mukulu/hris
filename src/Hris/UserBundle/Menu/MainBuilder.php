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

namespace Hris\UserBundle\Menu;

use Hris\UserBundle\MenuEvents;
use Hris\UserBundle\Event\ConfigureMenuEvent as UserConfigureMenuEvent;
use Hris\IndicatorBundle\Event\ConfigureMenuEvent as IndicatorConfigureMenuEvent;
use Hris\OrganisationunitBundle\Event\ConfigureMenuEvent as OrganisationunitConfigureMenuEvent;
use Hris\DataQualityBundle\Event\ConfigureMenuEvent as DataQualityConfigureMenuEvent;
use Hris\FormBundle\Event\ConfigureMenuEvent as FormConfigureMenuEvent;
use Hris\RecordsBundle\Event\ConfigureMenuEvent as RecordsConfigureMenuEvent;
use Hris\ReportsBundle\Event\ConfigureMenuEvent as ReportsConfigureMenuEvent;
use Hris\ImportExportBundle\Event\ConfigureMenuEvent as ImportExportConfigureMenuEvent;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;


class MainBuilder extends ContainerAware
{
    public function build(FactoryInterface $factory)
    {
        $menu = $factory->createItem('root');
        $menu->setExtra('tag','div');
        $menu->setExtra('menulevel','rootmenu');

        $menu->setCurrentUri($this->container->get('request')->getRequestUri());

        $this->container->get('event_dispatcher')->dispatch(UserConfigureMenuEvent::CONFIGURE, new UserConfigureMenuEvent($factory, $menu));
        $this->container->get('event_dispatcher')->dispatch(IndicatorConfigureMenuEvent::CONFIGURE, new IndicatorConfigureMenuEvent($factory, $menu));
        $this->container->get('event_dispatcher')->dispatch(OrganisationunitConfigureMenuEvent::CONFIGURE, new OrganisationunitConfigureMenuEvent($factory, $menu));
        $this->container->get('event_dispatcher')->dispatch(DataQualityConfigureMenuEvent::CONFIGURE, new DataQualityConfigureMenuEvent($factory, $menu));
        $this->container->get('event_dispatcher')->dispatch(FormConfigureMenuEvent::CONFIGURE, new FormConfigureMenuEvent($factory, $menu));
//        $this->container->get('event_dispatcher')->dispatch(RecordsConfigureMenuEvent::CONFIGURE, new RecordsConfigureMenuEvent($factory, $menu));
//        $this->container->get('event_dispatcher')->dispatch(ReportsConfigureMenuEvent::CONFIGURE, new ReportsConfigureMenuEvent($factory, $menu));
//        $this->container->get('event_dispatcher')->dispatch(ImportExportConfigureMenuEvent::CONFIGURE, new ImportExportConfigureMenuEvent($factory, $menu));

        return $menu;
    }
}