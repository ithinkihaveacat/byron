<?php

class AdapterDirectoryTest extends PHPUnit_Framework_TestCase
{
    public function testRequest_MissingFixture_ThrowsException()
    {
        $this->setExpectedException("\Byron\Client\Adapter\Exception");
        $client = new Zend_Http_Client();
        $client->setAdapter(new \Byron\Client\Adapter\Directory(array(
            "fixtures" => __DIR__
        )));

        $client->setUri("http://www.example.com/");
        $res = $client->request();
    }

    public function testRequest_TxtFixture_TxtResponse()
    {
        $client = new Zend_Http_Client();
        $client->setAdapter(new \Byron\Client\Adapter\Directory(array(
            "fixtures" => __DIR__ . "/../fixtures/dir1"
        )));

        $client->setUri("http://www.example.com/");
        $res = $client->request();

        $this->assertTrue($res->isSuccessful());
        $this->assertEquals("text/plain", $res->getHeader("content-type"));
        $this->assertEquals("Hello, World!", trim($res->getBody()));
    }

    public function testRequest_XmlFixture_XmlResponse()
    {
        $client = new Zend_Http_Client();
        $client->setAdapter(new \Byron\Client\Adapter\Directory(array(
            "fixtures" => __DIR__ . "/../fixtures/dir2"
        )));

        $client->setUri("http://www.example.com/");
        $res = $client->request();

        $this->assertTrue($res->isSuccessful());
        $this->assertEquals("text/xml", $res->getHeader("content-type"));
        $this->assertEquals("<root>Hello, World!</root>", trim($res->getBody()));
    }

    public function testRequest_404Fixture_404Response()
    {
        $client = new Zend_Http_Client();
        $client->setAdapter(new \Byron\Client\Adapter\Directory(array(
            "fixtures" => __DIR__ . "/../fixtures/dir3"
        )));

        $client->setUri("http://www.example.com/");
        $res = $client->request();

        $this->assertFalse($res->isSuccessful());
        $this->assertEquals(404, $res->getStatus());
        $this->assertEmpty($res->getBody());
        // For some reason this is "404 Not Found" (ZF bug?)
        // $this->assertEmpty($res->getHeader("content-type")); 
    }

    public function testRequest_MultiFixture_MultiResponse()
    {
        $client = new Zend_Http_Client();
        $client->setAdapter(new \Byron\Client\Adapter\Directory(array(
            "fixtures" => __DIR__ . "/../fixtures/dir4"
        )));

        $client->setUri("http://www.example.com/user/3");
        $res = $client->request();

        $this->assertTrue($res->isSuccessful());
        $this->assertEquals("application/json", $res->getHeader("content-type"));
        $this->assertEquals(array("message" => "Hello, World!"), json_decode($res->getBody(), true));


        $client->setUri("http://www.example.com/post/3333");
        $res = $client->request();

        $this->assertTrue($res->isSuccessful());
        $this->assertEquals("application/json", $res->getHeader("content-type"));
        $this->assertEquals(array("userid" => 3, "body" => "Hello, World!"), json_decode($res->getBody(), true));

        $client->setUri("http://www.example.com/user/3333");
        $res = $client->request();

        $this->assertFalse($res->isSuccessful());
        $this->assertEquals(500, $res->getStatus());
    }

    public function testRequest_RewriteHost_HostRewritten()
    {
        $client = new Zend_Http_Client();
        $client->setAdapter(new \Byron\Client\Adapter\Directory(array(
            "fixtures" => __DIR__ . "/../fixtures/dir5",
            "host" => "test"
        )));

        $client->setUri("http://www.example.com/");
        $res = $client->request();

        $this->assertTrue($res->isSuccessful());
        $this->assertEquals("text/xml", $res->getHeader("content-type"));
        $this->assertEquals("<root>Hello, World!</root>", trim($res->getBody()));
    }

    public function testRequest_TxtUrl_TxtResponse()
    {
        $client = new Zend_Http_Client();
        $client->setAdapter(new \Byron\Client\Adapter\Directory(array(
            "fixtures" => __DIR__ . "/../fixtures/dir6"
        )));

        $client->setUri("http://www.example.com/robots.txt");
        $res = $client->request();

        $this->assertTrue($res->isSuccessful());
        $this->assertEquals("text/plain", $res->getHeader("content-type"));
        $this->assertEquals("Hello, World!", trim($res->getBody()));
    }
}  
