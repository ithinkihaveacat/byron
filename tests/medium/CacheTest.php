<?php

abstract class CacheTest extends PHPUnit_Framework_Testcase
{
    public function tearDown()
    {
        $this->cache->flush();
    }
    
    public function testGet_NonexistantKey_ReturnsFalse()
    {
        $this->assertFalse(!!$this->cache->get("foo"));
    }

    public function testSetAndGet_String_ReturnsString()
    {
        $this->cache->set("foo", "qqqqqq");
        $this->assertEquals("qqqqqq", $this->cache->get("foo"));
    }

    public function testSetAndGet_Array_ThrowsException()
    {
        $this->setExpectedException("InvalidArgumentException");
        $v = array("foo" => 1, "bar" => 2, "baz" => str_repeat("x", 40));
        $this->cache->set("foo", $v);
    }

    public function testSetAndGet_ArrayOfArrays_ThrowsException()
    {
        $this->setExpectedException("InvalidArgumentException");
        $v = array("foo" => str_repeat("x", 40), "bar" => array("quux" => 1));
        $this->cache->set("foo", $v);
    }
    
    public function testSetAndGetSerialised()
    {
        $v = array("foo" => str_repeat("x", 40), "bar" => array("quux" => 1));
        $this->cache->set("foo", json_encode($v));
        $this->assertEquals($v, json_decode($this->cache->get("foo"), true));
    }
    
    public function testAdd()
    {
        $k = "foo";
        $v1 = "qqqqqq";
        $v2 = "kkkkkk";
        
        $this->cache->set($k, $v1);
        $this->assertEquals($v1, $this->cache->get($k));
        $this->assertFalse($this->cache->add($k, $v2));
        $this->assertEquals($v1, $this->cache->get($k));
    }

}
