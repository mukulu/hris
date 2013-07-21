Working with the Test Client
-----------------------------

The Test Client simulates an HTTP client like a browser and makes requests
into your Symfony2 application::

    $crawler = $client->request('GET', '/hello/Fabien');

The ``request()`` method takes the HTTP method and a URL as arguments and
returns a ``Crawler`` instance.

Use the Crawler to find DOM elements in the Response. These elements can then
be used to click on links and submit forms::

    $link = $crawler->selectLink('Go elsewhere...')->link();
    $crawler = $client->click($link);

    $form = $crawler->selectButton('validate')->form();
    $crawler = $client->submit($form, array('name' => 'Fabien'));

The ``click()`` and ``submit()`` methods both return a ``Crawler`` object.
These methods are the best way to browse your application as it takes care
of a lot of things for you, like detecting the HTTP method from a form and
giving you a nice API for uploading files.

.. tip::

    You will learn more about the ``Link`` and ``Form`` objects in the
    :ref:`Crawler<book-testing-crawler>` section below.

The ``request`` method can also be used to simulate form submissions directly
or perform more complex requests::

    // Directly submit a form (but using the Crawler is easier!)
    $client->request('POST', '/submit', array('name' => 'Fabien'));

    // Submit a raw JSON string in the request body
    $client->request(
        'POST',
        '/submit',
        array(),
        array(),
        array('CONTENT_TYPE' => 'application/json'),
        '{"name":"Fabien"}'
    );

    // Form submission with a file upload
    use Symfony\Component\HttpFoundation\File\UploadedFile;

    $photo = new UploadedFile(
        '/path/to/photo.jpg',
        'photo.jpg',
        'image/jpeg',
        123
    );
    $client->request(
        'POST',
        '/submit',
        array('name' => 'Fabien'),
        array('photo' => $photo)
    );

    // Perform a DELETE requests, and pass HTTP headers
    $client->request(
        'DELETE',
        '/post/12',
        array(),
        array(),
        array('PHP_AUTH_USER' => 'username', 'PHP_AUTH_PW' => 'pa$$word')
    );

Last but not least, you can force each request to be executed in its own PHP
process to avoid any side-effects when working with several clients in the same
script::

    $client->insulate();

Browsing
~~~~~~~~

The Client supports many operations that can be done in a real browser::

    $client->back();
    $client->forward();
    $client->reload();

    // Clears all cookies and the history
    $client->restart();

Accessing Internal Objects
~~~~~~~~~~~~~~~~~~~~~~~~~~

.. versionadded:: 2.3
    The ``getInternalRequest()`` and ``getInternalResponse()`` method were
    added in Symfony 2.3.

If you use the client to test your application, you might want to access the
client's internal objects::

    $history   = $client->getHistory();
    $cookieJar = $client->getCookieJar();

You can also get the objects related to the latest request::

    // the HttpKernel request instance
    $request  = $client->getRequest();

    // the BrowserKit request instance
    $request  = $client->getInternalRequest();

    // the HttpKernel response instance
    $response = $client->getResponse();

    // the BrowserKit response instance
    $response = $client->getInternalResponse();

    $crawler  = $client->getCrawler();

If your requests are not insulated, you can also access the ``Container`` and
the ``Kernel``::

    $container = $client->getContainer();
    $kernel    = $client->getKernel();

Accessing the Container
~~~~~~~~~~~~~~~~~~~~~~~

It's highly recommended that a functional test only tests the Response. But
under certain very rare circumstances, you might want to access some internal
objects to write assertions. In such cases, you can access the dependency
injection container::

    $container = $client->getContainer();

Be warned that this does not work if you insulate the client or if you use an
HTTP layer. For a list of services available in your application, use the
``container:debug`` console task.

.. tip::

    If the information you need to check is available from the profiler, use
    it instead.

Accessing the Profiler Data
~~~~~~~~~~~~~~~~~~~~~~~~~~~

On each request, you can enable the Symfony profiler to collect data about the
internal handling of that request. For example, the profiler could be used to
verify that a given page executes less than a certain number of database
queries when loading.

To get the Profiler for the last request, do the following::

    // enable the profiler for the very next request
    $client->enableProfiler();

    $crawler = $client->request('GET', '/profiler');

    // get the profile
    $profile = $client->getProfile();

For specific details on using the profiler inside a test, see the
:doc:`/cookbook/testing/profiling` cookbook entry.

Redirecting
~~~~~~~~~~~

When a request returns a redirect response, the client does not follow
it automatically. You can examine the response and force a redirection
afterwards  with the ``followRedirect()`` method::

    $crawler = $client->followRedirect();

If you want the client to automatically follow all redirects, you can
force him with the ``followRedirects()`` method::

    $client->followRedirects();

.. index::
   single: Tests; Crawler

.. _book-testing-crawler:

The Crawler
-----------

A Crawler instance is returned each time you make a request with the Client.
It allows you to traverse HTML documents, select nodes, find links and forms.

Traversing
~~~~~~~~~~

Like jQuery, the Crawler has methods to traverse the DOM of an HTML/XML
document. For example, the following finds all ``input[type=submit]`` elements,
selects the last one on the page, and then selects its immediate parent element::

    $newCrawler = $crawler->filter('input[type=submit]')
        ->last()
        ->parents()
        ->first()
    ;

Many other methods are also available:

+------------------------+----------------------------------------------------+
| Method                 | Description                                        |
+========================+====================================================+
| ``filter('h1.title')`` | Nodes that match the CSS selector                  |
+------------------------+----------------------------------------------------+
| ``filterXpath('h1')``  | Nodes that match the XPath expression              |
+------------------------+----------------------------------------------------+
| ``eq(1)``              | Node for the specified index                       |
+------------------------+----------------------------------------------------+
| ``first()``            | First node                                         |
+------------------------+----------------------------------------------------+
| ``last()``             | Last node                                          |
+------------------------+----------------------------------------------------+
| ``siblings()``         | Siblings                                           |
+------------------------+----------------------------------------------------+
| ``nextAll()``          | All following siblings                             |
+------------------------+----------------------------------------------------+
| ``previousAll()``      | All preceding siblings                             |
+------------------------+----------------------------------------------------+
| ``parents()``          | Returns the parent nodes                           |
+------------------------+----------------------------------------------------+
| ``children()``         | Returns children nodes                             |
+------------------------+----------------------------------------------------+
| ``reduce($lambda)``    | Nodes for which the callable does not return false |
+------------------------+----------------------------------------------------+

Since each of these methods returns a new ``Crawler`` instance, you can
narrow down your node selection by chaining the method calls::

    $crawler
        ->filter('h1')
        ->reduce(function ($node, $i)
        {
            if (!$node->getAttribute('class')) {
                return false;
            }
        })
        ->first();

.. tip::

    Use the ``count()`` function to get the number of nodes stored in a Crawler:
    ``count($crawler)``

Extracting Information
~~~~~~~~~~~~~~~~~~~~~~

The Crawler can extract information from the nodes::

    // Returns the attribute value for the first node
    $crawler->attr('class');

    // Returns the node value for the first node
    $crawler->text();

    // Extracts an array of attributes for all nodes
    // (_text returns the node value)
    // returns an array for each element in crawler,
    // each with the value and href
    $info = $crawler->extract(array('_text', 'href'));

    // Executes a lambda for each node and return an array of results
    $data = $crawler->each(function ($node, $i)
    {
        return $node->attr('href');
    });

Links
~~~~~

To select links, you can use the traversing methods above or the convenient
``selectLink()`` shortcut::

    $crawler->selectLink('Click here');

This selects all links that contain the given text, or clickable images for
which the ``alt`` attribute contains the given text. Like the other filtering
methods, this returns another ``Crawler`` object.

Once you've selected a link, you have access to a special ``Link`` object,
which has helpful methods specific to links (such as ``getMethod()`` and
``getUri()``). To click on the link, use the Client's ``click()`` method
and pass it a ``Link`` object::

    $link = $crawler->selectLink('Click here')->link();

    $client->click($link);

Forms
~~~~~

Just like links, you select forms with the ``selectButton()`` method::

    $buttonCrawlerNode = $crawler->selectButton('submit');

.. note::

    Notice that you select form buttons and not forms as a form can have several
    buttons; if you use the traversing API, keep in mind that you must look for a
    button.

The ``selectButton()`` method can select ``button`` tags and submit ``input``
tags. It uses several different parts of the buttons to find them:

* The ``value`` attribute value;

* The ``id`` or ``alt`` attribute value for images;

* The ``id`` or ``name`` attribute value for ``button`` tags.

Once you have a Crawler representing a button, call the ``form()`` method
to get a ``Form`` instance for the form wrapping the button node::

    $form = $buttonCrawlerNode->form();

When calling the ``form()`` method, you can also pass an array of field values
that overrides the default ones::

    $form = $buttonCrawlerNode->form(array(
        'name'              => 'Fabien',
        'my_form[subject]'  => 'Symfony rocks!',
    ));

And if you want to simulate a specific HTTP method for the form, pass it as a
second argument::

    $form = $buttonCrawlerNode->form(array(), 'DELETE');

The Client can submit ``Form`` instances::

    $client->submit($form);

The field values can also be passed as a second argument of the ``submit()``
method::

    $client->submit($form, array(
        'name'              => 'Fabien',
        'my_form[subject]'  => 'Symfony rocks!',
    ));

For more complex situations, use the ``Form`` instance as an array to set the
value of each field individually::

    // Change the value of a field
    $form['name'] = 'Fabien';
    $form['my_form[subject]'] = 'Symfony rocks!';

There is also a nice API to manipulate the values of the fields according to
their type::

    // Select an option or a radio
    $form['country']->select('France');

    // Tick a checkbox
    $form['like_symfony']->tick();

    // Upload a file
    $form['photo']->upload('/path/to/lucas.jpg');

.. tip::

    You can get the values that will be submitted by calling the ``getValues()``
    method on the ``Form`` object. The uploaded files are available in a
    separate array returned by ``getFiles()``. The ``getPhpValues()`` and
    ``getPhpFiles()`` methods also return the submitted values, but in the
    PHP format (it converts the keys with square brackets notation - e.g.
    ``my_form[subject]`` - to PHP arrays).
