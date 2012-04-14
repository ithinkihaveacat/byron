<?php

namespace Byron {

    class Dvar {

        private static $instance = null;

        private $stack = array();

        // private function __construct() {
        // }

        public static function getInstance() {
            if (!isset(self::$instance)) {
                $className = __CLASS__;
                self::$instance = new $className();
            }
            return self::$instance;
        }

        public function exists($name) {
            return array_key_exists($name, $this->stack);
        }

        public function get($name) {
            if (!$this->exists($name)) {
                throw new \InvalidArgumentException("name [$name] not found");
            }
            if (!count($this->stack[$name])) {
                throw new \InvalidArgumentException("name [$name] is undefined");
            }
            return $this->stack[$name][0];
        }

        public function push($name, $value) {
            if (!array_key_exists($name, $this->stack)) {
                $this->stack[$name] = array();
            }
            return array_unshift($this->stack[$name], $value);
        }

        public function pop($name) {
            if (!$this->exists($name)) {
                throw new \InvalidArgumentException("name [$name] is undefined");
            }
            return array_shift($this->stack[$name]);
        }

        public function reset() {
            $this->stack = array();
        }

    }

}

namespace {

    function dvar() {
        return \Byron\Dvar::getInstance();
    }

    function dcall(array $bindings, $block) {
        foreach ($bindings as $k => $v) {
            dvar()->push($k, $v);
        }
        $res = $block();
        foreach ($bindings as $k => $v) {
            dvar()->pop($k);
        }
        return $res;
    }

    function dexists($name) {
        return dvar()->exists($name);
    }

    function dget($name) {
        return dvar()->get($name);
    }

    function dpush($name, $value) {
        return dvar()->push($name, $value);
    }

    function dpop($name) {
        return dvar()->pop($name);
    }

    function dnew($class) {
        $args = func_get_args();
        $class = array_shift($args);
        if (dvar()->exists($class)) {
            $class = dvar()->get($class);
        }
        $rclass = new ReflectionClass($class);
        return $rclass->newInstanceArgs($args);
    }

    function dreset() {
        return \Byron\Dvar::getInstance()->reset();
    }

}
