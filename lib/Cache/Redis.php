<?php

namespace Byron\Cache;

class Redis implements \Byron\Cache {
    
    public $redis = null;

    public function __construct(\Predis\Client $r)
    {
        $this->redis = $r;
    }

    public function get($key)
    {
        return $this->redis->get($key);
    }

    public function set($key, $value, $expires = null)
    {
        if (!is_scalar($value)) {
            throw new \Exception("Cannot set() non scalar values");
        }
        
        if (is_null($expires)) {
            $expires = 3600;
        }

        return $this->redis->setex($key, $expires, $value);
    }

    public function add($key, $value, $expires = null)
    {
        if (!is_scalar($value)) {
            throw new \Exception("Cannot set() non scalar values");
        }
        
        if (is_null($expires)) {
            $expires = 3600;
        }

        $res = $this->redis->pipeline(function($pipe) use ($key, $value, $expires) {
            $pipe->setnx($key, $value);
            $pipe->expire($key, $expires);
        });
        
        return $res[0];
    }
    
    public function flush()
    {
        return $this->redis->flushdb();
    }
    
}