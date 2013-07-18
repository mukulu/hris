Rendering Menus
================

Once you've setup your menu, rendering it is easy. If you've used event dispatcher
way to decouple menu rendering, simply append the following template tag on your template::

    {{ knp_menu_render('HrisUserBundle:MainBuilder:build',{'template':':Menu:knp_menu.html.twig'}) }}

Additionally, you can pass some options to the renderer::

    {{ knp_menu_render('HrisUserBundle:MainBuilder:build',{'template':':Menu:knp_menu.html.twig','depth': 2, 'currentAsLink': false}) }}

For a full list of options, see the "Other rendering options" header on the
`KnpMenu<https://github.com/KnpLabs/KnpMenu/blob/master/doc/01-Basic-Menus.markdown>` documentation.

You can also "get" a menu, which you can use to render later::

    {% set menuItem = knp_menu_get('HrisUserBundle:MainBuilder:build') %}

    {{ knp_menu_render(menuItem) }}

If you want to only retrieve a certain branch of the menu, you can do the following,
where 'Contact' is one of the root menu items and has children beneath it.::

    {% set menuItem = knp_menu_get('HrisUserBundle:MainBuilder:build', ['HelpCenter']) %}

    {{ knp_menu_render(['HrisUserBundle:MainBuilder:build', 'HelpCenter']) }}

If you want to pass some options to the builder, you can use the third parameter
of the knp_menu_get function::

    {% set menuItem = knp_menu_get('AcmeDemoBundle:Builder:mainMenu', [], {'some_option': 'my_value'}) %}

    {{ knp_menu_render(menuItem) }}

