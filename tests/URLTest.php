<?php

class URLTest extends PHPUnit_Framework_Testcase
{
    public function setUp()
    {
        // No autoloader for functions, so we have to do it manually
        blib("url");
    }

    public function testBuildQs1()
    {
        $args = array("foo" => "bar");
        $qs = url_build_qs($args);
        $this->assertFalse(empty($qs));
        $this->assertEquals("foo=bar", $qs);
        $this->assertEquals($args, url_split_qs($qs));
    }
    
    public function testBuildQs2()
    {
        $args = array("foo" => "<bar>");
        $qs = url_build_qs($args);
        $this->assertFalse(empty($qs));
        $this->assertEquals("foo=%3Cbar%3E", $qs);
        $this->assertEquals($args, url_split_qs($qs));
    }
    
    public function testBuildQs3()
    {
        $args = array("foo" => array("a", "b", "c"));
        $qs = url_build_qs($args);
        $this->assertFalse(empty($qs));
        $this->assertEquals("foo%5B0%5D=a&foo%5B1%5D=b&foo%5B2%5D=c", $qs);
        $this->assertEquals($args, url_split_qs($qs));
    }

}