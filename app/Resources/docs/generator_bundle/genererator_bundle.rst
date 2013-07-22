Sensio Generator Bundle
=======================

The SensioGeneratorBundle extends the default Symfony2 command line interface
by providing new interactive and intuitive commands for generating code skeletons
like bundles, form classes or CRUD controllers based on a Doctrine 2 schema.

List of Available Commands
---------------------------

The SensioGeneratorBundle comes with four new commands that can be run in
interactive mode or not. The interactive mode asks you some questions to
configure the command parameters to generate the definitive code.
The list of new commands are listed below:

The directory structure of a Symfony2 :term:`application` is rather flexible,
but the directory structure of the *Standard Edition* distribution reflects
the typical and recommended structure of a Symfony2 application:

* Generating a New Bundle Skeleton
* Generating a CRUD Controller Based on a Doctrine Entity
* Generating a CRUD Controller Based on a Doctrine Entity
* Generating a New Form Type Class Based on a Doctrine Entity

Overriding Skeleton Templates
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

All generators use a template skeleton to generate files. By default,
the commands use templates provided by the bundle under its Resources/skeleton directory.

You can define custom skeleton templates by creating the same directory and file structure
in APP_PATH/Resources/SensioGeneratorBundle/skeleton or BUNDLE_PATH/Resources/SensioGeneratorBundle/skeleton
if you want to extend the generator bundle (to be able to share your templates for instance in several projects).

For instance, if you want to override the edit template for the CRUD generator,
create a crud/views/edit.html.twig.twig file under APP_PATH/Resources/SensioGeneratorBundle/skeleton.

When overriding a template, have a look at the default templates to learn more about
the available templates, their path, and the variables they have access.

Instead of copy/pasting the original template to create your own,
you can also extend it and only override the relevant parts:

Complex templates in the default skeleton are split into Twig blocks to allow
easy inheritance and to avoid copy/pasting large chunks of code.
In some cases, templates in the skeleton include other ones, like in the
crud/views/edit.html.twig.twig template for instance:

If you have defined a custom template for this template, it is going to be
used instead of the default one. But you can explicitly include the original
skeleton template by prefixing its path with skeleton/ like we did above:

You can learn more about this neat "trick" in "Overriding a Template that also extends itself".

Overriding a Template that also extends itself
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

A template can be customized in two different ways:
* `Inheritance`: A template extends a parent template and overrides some blocks;
* `Replacement`: If you use the filesystem loader, Twig loads the first template it
    finds in a list of configured directories; a template found in a directory replaces
    another one from a directory further in the list.

But how do you combine both: replace a template that also extends itself
(aka a template in a directory further in the list)?

Let's say that your templates are loaded from both .../templates/mysite and
.../templates/default in this order. The page.twig template, stored in
.../templates/default reads as follows::

    {# page.twig #}
    {% extends "layout.twig" %}

    {% block content %}
    {% endblock %}

You can replace this template by putting a file with the same name in .../templates/mysite.
And if you want to extend the original template, you might be tempted to write the following::

    {# page.twig in .../templates/mysite #}
    {% extends "page.twig" %} {# from .../templates/default #}

Of course, this will not work as Twig will always load the template from .../templates/mysite.

It turns out it is possible to get this to work, by adding a directory right at the end of your
template directories, which is the parent of all of the other directories: .../templates in our case.
This has the effect of making every template file within our system uniquely addressable.
Most of the time you will use the "normal" paths, but in the special case of wanting to extend
a template with an overriding version of itself we can reference its parent's full,
unambiguous template path in the extends tag::

    {# page.twig in .../templates/mysite #}
    {% extends "default/page.twig" %} {# from .../templates #}