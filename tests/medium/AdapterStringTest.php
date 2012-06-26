<?php

class AdapterStringTest extends PHPUnit_Framework_TestCase
{
    public function test404()
    {
        $urls = array(
            "http://www.example.com/",
            "http://www.example.com/?q=foo"
        );

        $methods = array(
            "GET",
            "POST",
            "PUT",
            "DELETE"
        );

        foreach ($urls as $url) {
            foreach ($methods as $method) {
                $client = new Zend_Http_Client();
                $client->setAdapter(new \Byron\Client\Adapter\String(
                    "HTTP/1.1 404 File Not Found"
                ));

                $client->setUri("http://example.com/");
                $res = $client->request();

                $this->assertEquals(404, $res->getStatus());
                $this->assertFalse($res->isSuccessful());
            }
        }

    }

    public function test200()
    {
        $urls = array(
            "http://www.example.com/",
            "http://www.example.com/?q=foo"
        );

        $methods = array(
            "GET",
            "POST",
            "PUT",
            "DELETE"
        );

        foreach ($urls as $url) {
            foreach ($methods as $method) {
                $client = new Zend_Http_Client();
                $client->setAdapter(new \Byron\Client\Adapter\String(join("\r\n", array(
                    "HTTP/1.1 200 OK",
                    "Content-Type: text/plain",
                    "",
                    "Hello, World!"
                ))));

                $client->setUri("http://example.com/");
                $res = $client->request();

                $this->assertTrue($res->isSuccessful());
                $this->assertEquals(200, $res->getStatus());
                $this->assertEquals("text/plain", $res->getHeader("content-type"));
                $this->assertEquals("Hello, World!", $res->getBody());
            }
        }

    }
}
