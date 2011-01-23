<?php

namespace Byron\Cache;

class Memcached implements \Byron\Cache {
    
    public $memcache = null;

    public function __construct(\Memcached $m)
    {
        $this->memcache = $m;
    }

    public function get($key)
    {
        return $this->memcache->get($key);
    }
    
    public function add($key, $value, $expires = null)
    {
        if (!is_scalar($value)) {
            throw new \Exception("Cannot add() non-scalar values");
        }
        
        if (is_null($expires)) {
            $expires = 0;
        }
        
        $r = $this->memcache->add($key, $value, $expires);
        
        if (($r === false) && ($this->memcache->getResultCode() != \Memcached::RES_NOTSTORED)) {
            trigger_error("warning: couldn't add() key [$key], code [{$this->memcache->getResultCode()}]");
        }
        
        return $r;
    }
    
    public function set($key, $value, $expires = null)
    {
        if (!is_scalar($value)) {
            throw new \Exception("Cannot set() non scalar values");
        }

        if (is_null($expires)) {
            $expires = 0;
        }

        $r = $this->memcache->set($key, $value, $expires);

        if ($r === false) {
            trigger_error("warning: couldn't set() key [$key], code [{$this->memcache->getResultCode()}]");
        }
        
        return $r;
    }
    
    public function flush()
    {
        return $this->memcache->flush();
    }
    
}