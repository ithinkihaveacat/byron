## Overview

The tests are divided into "small" (no external dependencies or libraries
required) and "medium" (external PHP dependencies, such as Zend Framework) and
"large" (external dependencides, such as redis and memcached).

This more or less follows Google's nomenclature, as described here:

  http://googletesting.blogspot.co.uk/2010/12/test-sizes.html

The method names themselves mostly follow the format

  MethodName_StateUnderTest_ExpectedBehavior

as described at

  http://osherove.com/blog/2005/4/3/naming-standards-for-unit-tests.html

## Running Tests

    # From the "tests" directory

    # Run tests with no external dependencies (fast!)
    $ phpunit small

    # If you have redis, memcached, etc. installed
    $ phpunit medium

See `bootstrap.php` for the expected location of Zend Framework.
