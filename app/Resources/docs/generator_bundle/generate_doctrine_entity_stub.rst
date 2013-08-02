Generating a New Doctrine Entity Stub
=====================================

Usage
------

The generate:doctrine:entity command generates a new Doctrine entity stub including
the mapping definition and the class properties, getters and setters.

By default the command is run in the interactive mode and asks questions to determine
the bundle name, location, configuration format and default structure::

    php app/console generate:doctrine:entity

The command can be run in a non interactive mode by using the --non-interaction option
without forgetting all needed options::

    php app/console generate:doctrine:entity --non-interaction --entity=AcmeBlogBundle:Post --fields="title:string(100) body:text" --format=xml

Available Options
------------------

* --entity: The entity name given as a shortcut notation containing the bundle name in
  which the entity is located and the name of the entity. For example:
  AcmeBlogBundle:Post::

    php app/console generate:doctrine:entity --entity=AcmeBlogBundle:Post

* --fields: The list of fields to generate in the entity class::

    php app/console generate:doctrine:entity --fields="title:string(100) body:text"

* --format: (annotation) [values: yml, xml, php or annotation] This option determines
  the format to use for the generated configuration files like routing. By default,
  the command uses the annotation format::

    php app/console generate:doctrine:entity --format=annotation

* --with-repository: This option tells whether or not to generate the related
  Doctrine EntityRepository class::

    php app/console generate:doctrine:entity --with-repository


Updating database tables
-------------------------

After generation of doctrine entity classes, the database schemas can be
updated with the following commands.

Seeing SQL queries generated from schema changes::

    app/console doctrine:schema:update --dump-sql

Applying changes of SQL queries on the database::

    app/console doctrine:schema:update --force

Dropping existing database so you can start with clean slate::

    app/console doctrine:database:drop --force

Creating fresh blank new database from existing doctrine entities::

    app/console doctrine:database:create


Generating Getters and Setters
-------------------------------

Even though Doctrine now knows how to persist a Product object to the database, the class itself isn't
really useful yet. Since Product is just a regular PHP class, you need to create getter and setter methods
(e.g. getName(), setName()) in order to access its properties (since the properties are protected).
Fortunately, Doctrine can do this for you by running::

    $ php app/console doctrine:generate:entities Hris/UserBundle/Entity/User


