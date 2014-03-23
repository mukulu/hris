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
use Hris\IntergrationBundle\Event\ConfigureMenuEvent as IntergrationConfigureMenuEvent;
use Hris\FormBundle\Event\ConfigureMenuEvent as FormConfigureMenuEvent;
use Hris\RecordsBundle\Event\ConfigureMenuEvent as RecordsConfigureMenuEvent;
use Hris\ReportsBundle\Event\ConfigureMenuEvent as ReportsConfigureMenuEvent;
use Hris\ImportExportBundle\Event\ConfigureMenuEvent as ImportExportConfigureMenuEvent;
use Hris\HelpCentreBundle\Event\ConfigureMenuEvent as HelpCentreConfigureMenuEvent;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;


class MainBuilder extends ContainerAware
{
    public function build(FactoryInterface $factory)
    {
        $securityContext = $this->container->get('security.context');
        $menu = $factory->createItem('root');
        $menu->setExtra('tag','div');
        $menu->setExtra('menulevel','rootmenu');

        $menu->setCurrentUri($this->container->get('request')->getRequestUri());

        // HelpCentre Bundle
        if(
            $securityContext->isGranted('ROLE_HELPCENTRE_BUNDLE_VIEW') ||
            $securityContext->isGranted('ROLE_HELPCENTRE_BUNDLE_MODIFY') ||
            $securityContext->isGranted('ROLE_HELPCENTRE_BUNDLE_MENU') ||
            $securityContext->isGranted('ROLE_SUPER_USER')
        ){
            $this->container->get('event_dispatcher')->dispatch(HelpCentreConfigureMenuEvent::CONFIGURE, new HelpCentreConfigureMenuEvent($factory, $menu));
        }
        // User Bundle
        if(
            $securityContext->isGranted('ROLE_USER_BUNDLE_VIEW') ||
            $securityContext->isGranted('ROLE_USER_BUNDLE_MODIFY') ||
            $securityContext->isGranted('ROLE_USER_BUNDLE_MENU') ||
            $securityContext->isGranted('ROLE_SUPER_USER')
        ){
            $this->container->get('event_dispatcher')->dispatch(UserConfigureMenuEvent::CONFIGURE, new UserConfigureMenuEvent($factory, $menu));
        }
        // Form & ResourceTable Bundle
        if(
            $securityContext->isGranted('ROLE_FORM_BUNDLE_VIEW') ||
            $securityContext->isGranted('ROLE_FORM_BUNDLE_MODIFY') ||
            $securityContext->isGranted('ROLE_FORM_BUNDLE_MENU') ||
            $securityContext->isGranted('ROLE_RESOURCETABLE_BUNDLE_VIEW') ||
            $securityContext->isGranted('ROLE_RESOURCETABLE_BUNDLE_MODIFY') ||
            $securityContext->isGranted('ROLE_SUPER_USER')
        ){
            $this->container->get('event_dispatcher')->dispatch(FormConfigureMenuEvent::CONFIGURE, new FormConfigureMenuEvent($factory, $menu));
        }

        // Target Bundle
        if(
            $securityContext->isGranted('ROLE_TARGET_BUNDLE_VIEW') ||
            $securityContext->isGranted('ROLE_TARGET_BUNDLE_MODIFY') ||
            $securityContext->isGranted('ROLE_TARGET_BUNDLE_MENU') ||
            $securityContext->isGranted('ROLE_SUPER_USER')
        ){
            $this->container->get('event_dispatcher')->dispatch(IndicatorConfigureMenuEvent::CONFIGURE, new IndicatorConfigureMenuEvent($factory, $menu));
        }
        // Organisationunit Bundle
        if(
            $securityContext->isGranted('ROLE_ORGANISATIONUNIT_BUNDLE_VIEW') ||
            $securityContext->isGranted('ROLE_ORGANISATIONUNIT_BUNDLE_MODIFY') ||
            $securityContext->isGranted('ROLE_ORGANISATIONUNIT_BUNDLE_MENU') ||
            $securityContext->isGranted('ROLE_SUPER_USER')
        ){
            $this->container->get('event_dispatcher')->dispatch(OrganisationunitConfigureMenuEvent::CONFIGURE, new OrganisationunitConfigureMenuEvent($factory, $menu));
        }
        // DataQuality Bundle
        if(
            $securityContext->isGranted('ROLE_DATAQUALITY_BUNDLE_VIEW') ||
            $securityContext->isGranted('ROLE_DATAQUALITY_BUNDLE_MODIFY') ||
            $securityContext->isGranted('ROLE_DATAQUALITY_BUNDLE_MENU') ||
            $securityContext->isGranted('ROLE_SUPER_USER')
        ){
            $this->container->get('event_dispatcher')->dispatch(DataQualityConfigureMenuEvent::CONFIGURE, new DataQualityConfigureMenuEvent($factory, $menu));
        }
        // Intergration Bundle
        if(
            $securityContext->isGranted('ROLE_INTERGRATION_BUNDLE_VIEW') ||
            $securityContext->isGranted('ROLE_INTERGRATION_BUNDLE_MODIFY') ||
            $securityContext->isGranted('ROLE_INTERGRATION_BUNDLE_MENU') ||
            $securityContext->isGranted('ROLE_SUPER_USER')
        ){
            $this->container->get('event_dispatcher')->dispatch(IntergrationConfigureMenuEvent::CONFIGURE, new IntergrationConfigureMenuEvent($factory, $menu));
        }
        // Records Bundle
        if(
            $securityContext->isGranted('ROLE_RECORDS_BUNDLE_VIEW') ||
            $securityContext->isGranted('ROLE_RECORDS_BUNDLE_MODIFY') ||
            $securityContext->isGranted('ROLE_RECORDS_BUNDLE_MENU') ||
            $securityContext->isGranted('ROLE_SUPER_USER')
        ){
            $this->container->get('event_dispatcher')->dispatch(RecordsConfigureMenuEvent::CONFIGURE, new RecordsConfigureMenuEvent($factory, $menu));
        }
        // Reports Bundle
        if(
            $securityContext->isGranted('ROLE_REPORTS_BUNDLE_VIEW') ||
            $securityContext->isGranted('ROLE_REPORTS_BUNDLE_MODIFY') ||
            $securityContext->isGranted('ROLE_REPORTS_BUNDLE_MENU') ||
            $securityContext->isGranted('ROLE_SUPER_USER')
        ){
            $this->container->get('event_dispatcher')->dispatch(ReportsConfigureMenuEvent::CONFIGURE, new ReportsConfigureMenuEvent($factory, $menu));
        }
        // ImportExport Bundle
        if(
            $securityContext->isGranted('ROLE_IMPORTEXPORT_BUNDLE_VIEW') ||
            $securityContext->isGranted('ROLE_IMPORTEXPORT_BUNDLE_MODIFY') ||
            $securityContext->isGranted('ROLE_IMPORTEXPORT_BUNDLE_MENU') ||
            $securityContext->isGranted('ROLE_SUPER_USER')
        ){
            $this->container->get('event_dispatcher')->dispatch(ImportExportConfigureMenuEvent::CONFIGURE, new ImportExportConfigureMenuEvent($factory, $menu));
        }
        return $menu;
    }
}