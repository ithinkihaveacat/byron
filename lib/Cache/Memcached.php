<?php

namespace Byron\Cache;

class Memcached implements \Byron\Cache {
    
    public $memcache = null;

    public $serialization = null;

    /**
     * $namespace is globally unique, and is prepended to the key to avoid collisions.  (If 
     * everything using a cache object creates unique keys for unique inputs, and the
     * namespace is also globally unique, collisions will not occur.)
     * 
     * @param Memcached $m
     * @param string $namespace globally unique, to avoid key collisions within memcache
     * @return unknown_type
     */
    
    function __construct(\Memcached $m) {
        $this->memcache = $m;
    }

    function __sleep() {
        // TODO Serialise more of the Memcached object--there is much more
        // to it than just the server list.  (e.g. Memcached::OPT_PREFIX_KEY.)
        $this->serialization = $this->memcache->getServerList();
        return array("serialization");
    }

    function __wakeup() {
        $m = new \Memcached();
        $m->addServers($this->serialization);
        $this->memcache = $m;
    }

    function test($key) {
    }
    
    function get($key) {
        return $this->memcache->get($key);
    }
    
    function add($key, $value, $expires = null) {
        
        if (is_null($expires)) {
            $expires = 0;
        }
        
        return $this->memcache->add($key, $value, $expires);
        
    }
    
    function set($key, $value, $expires = null) {

        if (is_null($expires)) {
            $expires = 0;
        }

        $r = $this->memcache->set($key, $value, $expires);

        if ($r === false) {
            // Warning rather than fatal because cache is supposed
            // to be expendable, strange that this would be happening
            // though...
            trigger_error("warning: couldn't set to key [$key]");
        }
        else {
            return $r;
        } 

    }
    
    function expire() {
    }
    
}