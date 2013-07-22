Functional Tests
=================

Functional tests check the integration of the different layers of an application
(from the routing to the views). They are no different from unit tests as far as
PHPUnit is concerned, but they have a very specific workflow:

* Make a request;
* Test the response;
* Click on a link or submit a form;
* Test the response;
* Rinse and repeat.

Your First Functional Test
---------------------------

Functional tests are simple PHP files that typically live in the Tests/Controller
directory of your bundle. If you want to test the pages handled by your
DemoController class, start by creating a new DemoControllerTest.php file that
extends a special WebTestCase class.

For example, the Symfony2 Standard Edition provides a simple functional test for
its DemoController (DemoControllerTest) that reads as follows::

    // src/Acme/DemoBundle/Tests/Controller/DemoControllerTest.php
    namespace Acme\DemoBundle\Tests\Controller;

    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class DemoControllerTest extends WebTestCase
    {
        public function testIndex()
        {
            $client = static::createClient();

            $crawler = $client->request('GET', '/demo/hello/Fabien');

            $this->assertGreaterThan(
                0,
                $crawler->filter('html:contains("Hello Fabien")')->count()
            );
        }
    }

.. tip::

    To run your functional tests, the WebTestCase class bootstraps the kernel of
    your application. In most cases, this happens automatically. However, if your
    kernel is in a non-standard directory, you'll need to modify your phpunit.xml.dist
    file to set the KERNEL_DIR environment variable to the directory of your kernel::

        <phpunit>
            <!-- ... -->
            <php>
                <server name="KERNEL_DIR" value="/path/to/your/app/" />
            </php>
            <!-- ... -->
        </phpunit>


The createClient() method returns a client, which is like a browser that you'll use
to crawl your site::

    $crawler = $client->request('GET', '/demo/hello/Fabien');

The request() method (see more about the request method) returns a Crawler object
which can be used to select elements in the Response, click on links, and submit forms.

.. tip::

    The Crawler only works when the response is an XML or an HTML document.
    To get the raw content response, call $client->getResponse()->getContent().

Click on a link by first selecting it with the Crawler using either an XPath expression
or a CSS selector, then use the Client to click on it. For example, the following code
finds all links with the text Greet, then selects the second one, and ultimately clicks on it::

    $link = $crawler->filter('a:contains("Greet")')->eq(1)->link();

    $crawler = $client->click($link);

Submitting a form is very similar; select a form button, optionally override some form values,
and submit the corresponding form::

    $form = $crawler->selectButton('submit')->form();

    // set some values
    $form['name'] = 'Lucas';
    $form['form_name[subject]'] = 'Hey there!';

    // submit the form
    $crawler = $client->submit($form);

.. tip::

    The form can also handle uploads and contains methods to fill in different types of
    form fields (e.g. select() and tick()). For details, see the Forms section below.

Now that you can easily navigate through an application, use assertions to test that
it actually does what you expect it to. Use the Crawler to make assertions on the DOM::

    // Assert that the response matches a given CSS selector.
    $this->assertGreaterThan(0, $crawler->filter('h1')->count());

Or, test against the Response content directly if you just want to assert that the content
contains some text, or if the Response is not an XML/HTML document::

    $this->assertRegExp(
        '/Hello Fabien/',
        $client->getResponse()->getContent()
    );

.. sidebar::

    More about the request() method:
    --------------------------------

    The full signature of the request() method is::

        request(
            $method,
            $uri,
            array $parameters = array(),
            array $files = array(),
            array $server = array(),
            $content = null,
            $changeHistory = true
        )

    The ``server`` array is the raw values that you'd expect to normally
    find in the PHP `$_SERVER`_ superglobal. For example, to set the `Content-Type`,
    `Referer` and `X-Requested-With' HTTP headers, you'd pass the following (mind
    the `HTTP_` prefix for non standard headers)::

        $client->request(
            'GET',
            '/demo/hello/Fabien',
            array(),
            array(),
            array(
                'CONTENT_TYPE'          => 'application/json',
                'HTTP_REFERER'          => '/foo/bar',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            )
        );

.. index::
   single: Tests; Assertions

.. sidebar:: Useful Assertions

    To get you started faster, here is a list of the most common and
    useful test assertions::

        // Assert that there is at least one h2 tag
        // with the class "subtitle"
        $this->assertGreaterThan(
            0,
            $crawler->filter('h2.subtitle')->count()
        );

        // Assert that there are exactly 4 h2 tags on the page
        $this->assertCount(4, $crawler->filter('h2'));

        // Assert that the "Content-Type" header is "application/json"
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        // Assert that the response content matches a regexp.
        $this->assertRegExp('/foo/', $client->getResponse()->getContent());

        // Assert that the response status code is 2xx
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Assert that the response status code is 404
        $this->assertTrue($client->getResponse()->isNotFound());
        // Assert a specific 200 status code
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );

        // Assert that the response is a redirect to /demo/contact
        $this->assertTrue(
            $client->getResponse()->isRedirect('/demo/contact')
        );
        // or simply check that the response is a redirect to any URL
        $this->assertTrue($client->getResponse()->isRedirect());
