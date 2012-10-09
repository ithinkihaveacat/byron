# Overview

A little library for doing a few things with PHP, the most interesting being
config management/service discovery and DOM manipulation.  (See the `tests`
directory.)  The services have a dependency on Zend Framework's HTTP client;
there might be some other ZF dependencies as well.

# Is the API stable?

Hahahahahaha.

# Requirements

The library uses a few PHP modules.  Either install these from packages, or
build PHP with something like:

    $ ./configure --enable-mbstring --with-openssl --enable-intl --with-xsl && make && make install

# Running Tests

    $ phpunit -c tests/phpunit.xml.dist tests/small
    
Or, if you have Xdebug:

    $ phpunit -c tests/phpunit.xml.dist --coverage-html ./coverage tests/small

# Installation

This library has a dependency on Zend Framework; various bits of code expect
classes such as `Zend_Http_Client` to be autoloaded.  You can either arrange for
this to happen yourself, or else you can use [composer](http://getcomposer.org/)
to install the dependencies and produce an autoloader for you.

    # First, install composer (see http://getcomposer.org/), then
    $ composer install

This will download Zend Framework into the `vendor` directory, and create a
`vendor/autoload.php` file that you can `require()` to autoload the Zend
Framework dependencies.  (To upgrade to a newer version of Zend Framework,
modify `composer.json`, delete `composer.lock` and re-run `composer install`.)

# Build Status

[![Build Status](https://secure.travis-ci.org/ithinkihaveacat/byron.png)](http://travis-ci.org/ithinkihaveacat/byron)

# Author

Michael Stillwell <mjs@beebo.org>
