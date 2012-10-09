<?php

class StringTest extends PHPUnit_Framework_Testcase
{
    
    public function testHelloWorld()
    {
        $s = new \Byron\String("Hello, World!");
        
        $this->assertEquals("Hello, World!", $s->toHtmlEntities());
        $this->assertEquals("Hello, World!", $s->toNamedEntities());
        $this->assertEquals("Hello, World!", $s->toNumericEntities());
    }
    
    public function testGreaterAndLessThan()
    {
        $s = new \Byron\String("Hello, <World>!");
        
        $this->assertEquals("Hello, &#60;World&#62;!", $s->toNumericEntities());
        $this->assertEquals("Hello, &lt;World&gt;!", $s->toHtmlEntities());
        $this->assertEquals("Hello, &lt;World&gt;!", $s->toNamedEntities());
        $this->assertEquals("Hello, &lt;World&gt;!", $s->toXmlEntities());
    }

    public function testSpecialCharacters()
    {
        $s = new \Byron\String("Some of my favourite characters as &, <, >, £, ¶, # and '");

        $this->assertEquals("Some of my favourite characters as &#38;, &#60;, &#62;, &#163;, &#182;, # and '", $s->toNumericEntities());
        $this->assertEquals("Some of my favourite characters as &amp;, &lt;, &gt;, &pound;, &para;, # and '", $s->toHtmlEntities());
        $this->assertEquals("Some of my favourite characters as &amp;, &lt;, &gt;, &pound;, &para;, # and '", $s->toNamedEntities());
        $this->assertEquals("Some of my favourite characters as &amp;, &lt;, &gt;, £, ¶, # and '", $s->toXmlEntities());
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
