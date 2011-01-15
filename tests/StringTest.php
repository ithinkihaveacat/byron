<?php

class StringTest extends PHPUnit_Framework_Testcase
{
    
    public function testHelloWorld()
    {
        $s = new \Byron\String("Hello, World!");
        
        $this->assertEquals("Hello, World!", $s->toNamedEntities());
        $this->assertEquals("Hello, World!", $s->toNumericEntities());
    }
    
    public function testGreaterAndLessThan()
    {
        $s = new \Byron\String("Hello, <World>!");
        
        $this->assertEquals("Hello, &#60;World&#62;!", $s->toNumericEntities());
        $this->assertEquals("Hello, &lt;World&gt;!", $s->toNamedEntities());
    }
    
    public function testToUri()
    {
        $s = new \Byron\String("Hello, World!");
        
        $this->assertEquals("Hello%2C%20World%21", $s->toUri());
    }
    
    public function testFromUri()
    {
        $s = new \Byron\String("Hello%2C%20World%21");
        
        $this->assertEquals("Hello, World!", $s->fromUri());
    }
    
    public function testFromXmlEntities()
    {
        $s = new \Byron\String("&lt;&amp;&gt;");
        
        $this->assertEquals("<&>", $s->fromNamedEntities());
    }
    
    public function testFromHtmlEntities()
    {
        $s = new \Byron\String("&lt;&amp;&gt;");
        
        $this->assertEquals("<&>", $s->fromNumericEntities());
    }
    
}