<?php

require_once("CacheTest.php");

class CacheMemcachedTest extends CacheTest
{
    
    public function setUp()
    {
        $memcached = new \Memcached();
        $memcached->addServer('127.0.0.1', '11211');
        $memcached->setOption(Memcached::OPT_SERIALIZER, Memcached::SERIALIZER_JSON);
        $this->cache = new \Byron\Cache\Memcached($memcached);
    }
    
}