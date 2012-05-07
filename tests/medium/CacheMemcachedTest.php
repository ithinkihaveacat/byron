<?php

require_once("CacheTest.php");

class CacheMemcachedTest extends CacheTest
{
    
    public function setUp()
    {
        if (!extension_loaded("memcached")) {
            $this->markTestSkipped("Skipping test, the [memcached] extension is not available");
        }
        $memcached = new \Memcached();
        $memcached->addServer('127.0.0.1', '11211');
        $version = $memcached->getVersion();
        if ($version['127.0.0.1:11211'] == "0.0.0") { // "1.4.7" for good server
            $this->markTestSkipped("Skipping test, can't connect to memcached server");
        }
        $this->cache = new \Byron\Cache\Memcached($memcached);
    }
    
}
