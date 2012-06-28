<?php

class AdapterFileTest extends PHPUnit_Framework_TestCase
{

    public function test200File()
    {
        $client = new Zend_Http_Client();
        $client->setAdapter(new \Byron\Client\Adapter\File(
            __DIR__ . "/../fixtures/200.txt"
        ));

        $client->setUri("http://example.com/");
        $res = $client->request();

        $this->assertTrue($res->isSuccessful());
        $this->assertEquals(200, $res->getStatus());
        $this->assertEquals("text/plain", $res->getHeader("content-type"));
        $this->assertEquals("Hello, World!", trim($res->getBody()));

    }

    public function test500File()
    {
        $client = new Zend_Http_Client();
        $client->setAdapter(new \Byron\Client\Adapter\File(
            __DIR__ . "/../fixtures/500.txt"
        ));

        $client->setUri("http://example.com/");
        $res = $client->request();

        $this->assertFalse($res->isSuccessful());
        $this->assertEquals(500, $res->getStatus());
        $this->assertEquals("application/json", $res->getHeader("content-type"));
        $this->assertEquals(array("message" => "Internal Server Error"), json_decode($res->getBody(), true));

    }

}
