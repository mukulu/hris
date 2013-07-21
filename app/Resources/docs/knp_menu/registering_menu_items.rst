Registering Menu Items to Global Menu
=====================================

Human Resource Information system makes use of decoupling individual menus of the
system into their specific modules which ensures code extensibility, codes becomes
more flexible pluggable in different subsystems.

This is made possible using Symfony events dispatcher component to allow a main menu
to be extended with sub menu from different bundles.

Create the Event object
------------------------

The event object allows passage of the menu being created in the bundle into
the main menu. Create an ConfigureMenuEvent class extending Event class under
`Event` folder, this class should be under Event namespace in your bundle namespace.::

    <?php
    // src/Hris/SampleBundle/Event/ConfigureMenuEvent.php

    namespace Hris\SampleBundle\Event;

    use Knp\Menu\FactoryInterface;
    use Knp\Menu\ItemInterface;
    use Symfony\Component\EventDispatcher\Event;

    class ConfigureMenuEvent extends Event
    {
        const CONFIGURE = 'hris_sample.menu_configure';

        private $factory;
        private $menu;

        /**
         * @param \Knp\Menu\FactoryInterface $factory
         * @param \Knp\Menu\ItemInterface $menu
         */
        public function __construct(FactoryInterface $factory, ItemInterface $menu)
        {
            $this->factory = $factory;
            $this->menu = $menu;
        }

        /**
         * @return \Knp\Menu\FactoryInterface
         */
        public function getFactory()
        {
            return $this->factory;
        }

        /**
         * @return \Knp\Menu\ItemInterface
         */
        public function getMenu()
        {
            return $this->menu;
        }
    }

.. tip::

    Following the Symfony2 best practices, the first segment of the event name i.e. `hris_sample`
    will be the alias of the bundle, which allows avoiding conflicts.

    That's it. Your builder now provides a hook. Let's see how you can use it!

Create a listener
------------------

Create an event Listener, that listen to `onMenuConfigure` event through the above event
so menu from SampleBundle can be injected into the main menu during runtime.

Create ConfigureMenuListener class under `EventListener` folder, this class must be under
EventListener in your bundle namespace, inside `onMenuConfigure` function extend the main
with submenu from your bundle, this function shall be executed on menuGeneration event.::

    <?php
    // src/Hris/SampleBundle/EventListener/ConfigureMenuListener.php

    namespace Hris\SampleBundle\EventListener;

    use Hris\SampleBundle\Event\ConfigureMenuEvent;

    class ConfigureMenuListener
    {
        /**
         * @param \Hris\SampleBundle\Event\ConfigureMenuEvent $event
         */
        public function onMenuConfigure(ConfigureMenuEvent $event)
        {
            $menu = $event->getMenu();

            // Module header
            $menu->addChild('Sample Module', array(
                    'uri'=>'#samplemodule','attributes'=>
                    array('class'=>'nav-header')
                )
            );
            // Module menu list
            $menu->addChild('Functionality1',array('route'=>'functionality_route_alis'));
            $menu->addChild('Functionality2', array('uri'=>'#functionality_uri'));
            // Separater of sample module menu and the next.
            $menu->addChild('',array('attributes'=>array('class'=>'divider')));
        }
    }

Now you can register the event listener to services list of services. Services configuration
can be found under `Resources/config` folder of the bundle, inside `services.yml` file.
Register the listener as follows::

    services:
        hris_sample.configure_menu_listener:
            class: Hris\SampleBundle\EventListener\ConfigureMenuListener
            tags:
              - { name: kernel.event_listener, event: hris_sample.menu_configure, method: onMenuConfigure }


Add Event dispatcher in MenuBuilder
------------------------------------

Currently event dispatcher class responsible for generation of entire main menu
is located inside `Hris\UserBundle\Menu`,. Inside `MainBuilder` class under `build` method
append line to dispatch menu event for generation during building of the menu.

Declare your Event's namespace outside `MainBuilder` class for use inside build method e.g.::

    use Hris\SampleBundle\Event\ConfigureMenuEvent as SampleConfigureMenuEvent;

Register event dispatcher inside build method::

    $this->container->get('event_dispatcher')->dispatch(SampleConfigureMenuEvent::CONFIGURE, new SampleConfigureMenuEvent($factory, $menu));

.. tip::

    This implementation assumes you use the BuilderAliasProvider
    (getting your menu as AcmeDemoBundle:MainBuilder:build) thus menu
    can be rendered on template with below template tag::

        {{ knp_menu_render('HrisUserBundle:MainBuilder:build',{'template':':Menu:knp_menu.html.twig'}) }}


.. sidebar::

    Plans are ongoing to Centralize Menu MainBuilder in location independent of any bundle.
    Stay on alert.