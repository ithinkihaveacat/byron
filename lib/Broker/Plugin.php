<?php

namespace Byron\Broker;

abstract class Plugin {

    protected $object = null;

    public function __construct($object) {
        $this->setObject($object);
    }

    protected function getObject() {
        return $this->object;
    }

    protected function setObject($object) {
        return $this->object = $object;
    }

    public abstract function getService();

    /**
     * Translate calls to unmatched methods to calls on the underlying
     * object itself.  This is how, for example, you can call getCacheService()
     * from the Flickr broker plugin.
     *
     * @param string $name
     * @param array $args
     * @return mixed
     */

    public function __call($name, $args) {
        return call_user_func_array(array($this->getObject(), $name), $args);
    }

}