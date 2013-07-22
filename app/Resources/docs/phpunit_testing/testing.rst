Testing
========

Whenever you write a new line of code, you also potentially add new bugs.
To build better and more reliable applications, you should test your code
using both functional and unit tests.

The PHPUnit Testing Framework
------------------------------

Symfony2 integrates with an independent library - called PHPUnit - to give you
a rich testing framework. This chapter won't cover PHPUnit itself, but it has
its own excellent `documentation<http://www.phpunit.de/manual/3.5/en/>`.

Each test - whether it's a unit test or a functional test - is a PHP class that
should live in the Tests/ subdirectory of your bundles. If you follow this rule,
then you can run all of your application's tests with the following command::

    # specify the configuration directory on the command line
    $ phpunit -c app/


The -c option tells PHPUnit to look in the app/ directory for a configuration file.
If you're curious about the PHPUnit options, check out the app/phpunit.xml.dist file.

.. tip::

    Code coverage can be generated with the --coverage-html option.

Unit Tests
-----------

A unit test is usually a test against a specific PHP class. If you want to test
the overall behavior of your application, see the section about Functional Tests.

Writing Symfony2 unit tests is no different than writing standard PHPUnit unit tests.
Suppose, for example, that you have an incredibly simple class called Calculator in
the Utility/ directory of your bundle::

    // src/Acme/DemoBundle/Utility/Calculator.php
    namespace Acme\DemoBundle\Utility;

    class Calculator
    {
        public function add($a, $b)
        {
            return $a + $b;
        }
    }

To test this, create a CalculatorTest file in the Tests/Utility directory of your bundle::

    // src/Acme/DemoBundle/Tests/Utility/CalculatorTest.php
    namespace Acme\DemoBundle\Tests\Utility;

    use Acme\DemoBundle\Utility\Calculator;

    class CalculatorTest extends \PHPUnit_Framework_TestCase
    {
        public function testAdd()
        {
            $calc = new Calculator();
            $result = $calc->add(30, 12);

            // assert that your calculator added the numbers correctly!
            $this->assertEquals(42, $result);
        }
    }

.. tip::

    By convention, the Tests/ sub-directory should replicate the directory of your bundle.
    So, if you're testing a class in your bundle's Utility/ directory,
    put the test in the Tests/Utility/ directory.

Just like in your real application - autoloading is automatically enabled via the
bootstrap.php.cache file (as configured by default in the phpunit.xml.dist file).

Running tests for a given file or directory is also very easy::

    # run all tests in the Utility directory
    $ phpunit -c app src/Acme/DemoBundle/Tests/Utility/

    # run tests for the Calculator class
    $ phpunit -c app src/Acme/DemoBundle/Tests/Utility/CalculatorTest.php

    # run all tests for the entire Bundle
    $ phpunit -c app src/Acme/DemoBundle/



