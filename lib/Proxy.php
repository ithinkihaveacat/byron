<?php

namespace Byron;

class Proxy {
    
    protected $_object = null;
    
    public function __construct($object) {
        $this->_object = $object;
    }
    
    public function __call($name, $arguments) {
        return call_user_func_array(array($this->_object, $name), $arguments);
    }
    
    public function __get($name) {
        return $this->_object->$name;
    }
    
    public function __set($name, $value) {
        return $this->_object->$name = $value;
    }
    
    public function __isset($name) {
        return isset($this->_object->$name);
    }
    
    public function __unset($name) {
        unset($this->_object->$name);
    }
    
    // PHP 5.3 has a __callStatic() if you need it
    
}
