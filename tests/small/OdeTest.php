<?php

class OdeTest extends PHPUnit_Framework_Testcase
{
    public function setUp()
    {
        if (!extension_loaded('xsl')) {
            $this->markTestSkipped('The [xsl] extension is not loaded');
        }        
    }
    
    public function testXpathMatchRoot()
    {
        $doc = \Byron\Dom::loadXml("<root/>");

        $transformer = new \Byron\Ode\Xpath("/root", function($node, $doc) {
            $node->appendAttributeArray(array("id" => "yes"));
        });

        $transformer($doc);
        
        $this->assertXmlStringEqualsXmlString('<root id="yes"/>', $doc->toXml());
    }
    
    public function testXpathNoMatch()
    {
        $doc = \Byron\Dom::loadXml("<root/>");
        
        $i = 0;
        
        $transformer = new \Byron\Ode\Xpath("doesnotmatch", function($node, $doc) use (&$i) {
            $i++;
        });
        
        $transformer($doc);
        
        $this->assertXmlStringEqualsXmlString('<root/>', $doc->toXml());
        $this->assertEquals(0, $i);
    }
    
    public function testXpathMatchNoWidthOrHeight()
    {
        $doc = \Byron\Dom::loadXml('<root><img src="foo.jpg" width="600" height="400"/><img src="bar.jpg" width="7"/></root>');
        
        $transformer = new \Byron\Ode\Xpath("img[not(@width) or not(@height)]", function($node, $doc) {
            $node->appendAttributeArray(array("width" => 200, "height" => 100));
        });
        
        $transformer($doc);
        
        $this->assertXmlStringEqualsXmlString(
            '<root><img src="foo.jpg" width="600" height="400"/><img src="bar.jpg" width="200" height="100"/></root>',
            $doc->toXml()
        );
            
    }
    
    public function testCssNoMatch()
    {
        $doc = \Byron\Dom::loadXml("<root/>");
        
        $i = 0;
        
        $transformer = new \Byron\Ode\Css("p.doesnotmatch", function($node, $doc) use (&$i) {
            $i++;
        });
        
        $transformer($doc);
        
        $this->assertXmlStringEqualsXmlString('<root/>', $doc->toXml());
        $this->assertEquals(0, $i);
    }
    
    public function testXpathMatchLocal()
    {
        $doc = \Byron\DOM::loadXml('<root><img src="/img/foo.jpg"/><img src="http://beebo.org/img/something.jpg"/></root>');
        
        $transformer = new \Byron\Ode\Xpath("img[starts-with(@src, '/')]", function($node, $doc) {
            $node->setAttribute("src", "http://static.beebo.org/_version/3243234" . $node->getAttribute("src"));
        });
        
        $transformer($doc);
        
        $this->assertXmlStringEqualsXmlString(
            '<root><img src="http://static.beebo.org/_version/3243234/img/foo.jpg"/><img src="http://beebo.org/img/something.jpg"/></root>',
            $doc->toXml()
        );
    }

    public function testCssMatchClass()
    {
        $doc = \Byron\Dom::loadXml('<page><p class="foo"/><p/></page>');
        
        $i = 0;

        $transformer = new \Byron\Ode\Css("p.foo", function($node, $doc) use (&$i) {
            $node->appendAttributeArray(array("id" => "yes"));
            $i++;
        });

        $transformer($doc);
        
        $this->assertXmlStringEqualsXmlString('<page><p class="foo" id="yes"/><p/></page>', $doc->toXml());
        $this->assertEquals(1, $i);
    }

    public function testCssMatchAll()
    {
        $doc = \Byron\Dom::loadXml('<page><p class="foo"/><p/></page>');
        
        $i = 0;

        $transformer = new \Byron\Ode\Css("p", function($node, $doc) use (&$i) {
            $node->appendAttributeArray(array("id" => "yes"));
            $i++;
        });

        $transformer($doc);
        
        $this->assertXmlStringEqualsXmlString('<page><p class="foo" id="yes"/><p id="yes"/></page>', $doc->toXml());
        $this->assertEquals(2, $i);
    }
    
    public function testXsl1()
    {
        $doc = \Byron\Dom::loadXml('<page/>');
        
        $transformer = new \Byron\Ode\Xslt("fixtures/xsl1.xsl");
        
        $transformer($doc);
        
        $this->assertXmlStringEqualsXmlString("<qqqqqq/>", $doc->toXml());
    }

    public function testXslWithArgs()
    {
        $doc = \Byron\Dom::loadXml('<page/>');

        $transformer = new \Byron\Ode\Xslt("fixtures/xsl2.xsl");

        $transformer($doc, array("greeting" => "Hullo, World!"));

        $this->assertXmlStringEqualsXmlString("<qqqqqq>Hullo, World!</qqqqqq>", $doc->toXml());
    }
    
    public function testReplace()
    {
        $doc = \Byron\Dom::loadXml('<page class="qqqqqq"><sidebar/></page>');
        
        $transformer = new \Byron\Ode\Css("sidebar", function($node, $doc) {
            $node->setXml('<p class="foo">Maybe this <em>works</em>.</p>');
        });
        
        $transformer($doc);
        
        $this->assertXmlStringEqualsXmlString(
            '<page class="qqqqqq"><p class="foo">Maybe this <em>works</em>.</p></page>',
            $doc->toXml()
        );
        
    }
    
}
