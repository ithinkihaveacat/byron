<?php

namespace Byron;

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

        if (!preg_match("/^get(\w*)Service$/", $name, $matches)) {
            throw new \Exception("only calls of the form [getXxxService()] are supported (not [$name()])");
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

