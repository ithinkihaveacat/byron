<?php

require_once("CacheTest.php");
require_once(__DIR__ . "/../../ext/predis_0.7.2.phar");

class CacheRedisTest extends CacheTest
{
    
    public function setUp()
    {
        $redis = new \Predis\Client(array(
            "host" => "127.0.0.1",
            "port" => "6379",
            "database" => "0"
        ));
        try { 
            $redis->ping();
        } catch (\Predis\Network\ConnectionException $e) {
            $this->markTestSkipped("Skipping test, can't connect to redis");
        }
        $this->cache = new \Byron\Cache\Redis($redis);
    }
    
}
