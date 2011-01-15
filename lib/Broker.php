<?php

namespace Byron;

/**
 * The Broker system is merely a more structured way of creating a no-argument function
 * for each service that returns an object appropriately configured (with correct hostnames,
 * usernames, passwords, etc.) for interacting with that service.
 *
 * That is, the constructor for a low-level library function will probably have arguments
 * like hostname, username, password, etc.  It's incovenient and annoying to have to
 * figure out how and where these details should come from every time you want to use
 * the service.  You could create a big PHP file with a function for each service:
 *
 *     function getMySQLService() {
 *         // extract connection details from a (global?) variable
 *         $service = ...
 *         return $service;
 *     }
 *
 * But this is a bit messy an unclean.  The alternative is to create a "Broker"
 * object, giving it a mechanism to create an appropriate class names for a service,
 * when that service is requested.  Then the broker can instantiate that class, passing
 * it any information it might need, like an object storing configuration details.
 * Then, the broker could call a "getService()" method on the newly-instantiated object,
 * returning the return value of "getService()" to the caller.  This is the mechanism
 * implemented by this class.
 */

class Broker {

    protected static $instance = null;
    
    protected $format = null;

    protected $shared = null;

    public function __construct($format, $shared = null) {

        $this->setFormat($format);
        $this->setShared($shared);

        if (is_null(self::$instance)) {
            self::$instance = $this;
        }
        
    }
    
    /**
     * Discouraged; code should be accessing the broker via a local
     * variable.
     */

    public static function getInstance() {
        return self::$instance;
    }

    protected function setFormat($format) {
        return $this->format = $format;
    }

    protected function getFormat() {
        return $this->format;
    }

    public function setShared($shared) {
        return $this->shared = $shared;
    }

    public function getShared() {
        return $this->shared;
    }

    public function __call($name, $args) {

        if (!preg_match("/^get(\w*)(Service?)$/", $name, $matches)) {
            throw new \Exception("only calls of the form [getXxx()] are supported (not [$name()])");
        }

        // TODO Implement some caching?

        $class = sprintf($this->getFormat(), ucfirst($matches[1]));
        
        if (!class_exists($class)) {
            throw new \Exception("can't find class [$class]");
        }

        $obj = new $class($this);

        if (!is_a($obj, '\Byron\Broker\Plugin')) {
            throw new \Exception("class [$class] does not extend \Byron\Broker\Plugin");
        }

        return call_user_func_array(array($obj, "getService"), $args);

    }

}

