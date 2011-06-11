<?php

class DOMTest extends PHPUnit_Framework_Testcase
{

    public function testLoad()
    {
        $doc = \Byron\DOM::loadXml("<root id='foo'/>");
        $this->assertEquals("DOMElement", get_class($doc->documentElement));
        $this->assertEquals("root", $doc->documentElement->nodeName);
        $this->assertEquals("foo", $doc->documentElement->getAttribute("id"));
    }
    
    public function testToArray1()
    {
        $doc = \Byron\DOM::loadXml("<root><entry id='8'><title>Clem</title></entry><entry id='8899'><title>Jason</title></entry></root>");
        $row = $doc->toArray("/root/entry");
        $this->assertEquals(2, count($row));
        $this->assertEquals(
            array(
                array("id" => 8, "title" => "Clem"),
                array("id" => 8899, "title" => "Jason")
            ),
            $row
        );
    }
    
    public function testToArrayOnlyAttributes()
    {
        $doc = \Byron\DOM::loadXml('
            <posts>
              <post href="http://www.indyjt.com/software/?show=ieatbrainz#ieatbrainz" hash="5a39e1f890dcf756a67ff43cc5c630dd" description="Jay Tuleys Website: Software" tag="itunes mp3 tag" time="2004-12-22T14:15:57Z" extended="" meta="28af5e1d1677be2a10f34b54ef1af4c2"/>
              <post href="http://www.ivarhagendoorn.com/personal/musicvideos.html" hash="4d4639211c1bdd8a943dfb966815e940" description="Music Videos" tag="musicvideo" time="2004-12-16T22:53:45Z" extended="" meta="c9127d7fd56e22c6ffd570e092a6d256"/>
            </posts>        
        ');
        
        $expected = array (
            array (
                'href' => 'http://www.indyjt.com/software/?show=ieatbrainz#ieatbrainz',
                'hash' => '5a39e1f890dcf756a67ff43cc5c630dd',
                'description' => 'Jay Tuleys Website: Software',
                'tag' => 'itunes mp3 tag',
                'time' => '2004-12-22T14:15:57Z',
                'extended' => '',
                'meta' => '28af5e1d1677be2a10f34b54ef1af4c2',
            ),
            array (
                'href' => 'http://www.ivarhagendoorn.com/personal/musicvideos.html',
                'hash' => '4d4639211c1bdd8a943dfb966815e940',
                'description' => 'Music Videos',
                'tag' => 'musicvideo',
                'time' => '2004-12-16T22:53:45Z',
                'extended' => '',
                'meta' => 'c9127d7fd56e22c6ffd570e092a6d256',
            ),
        );
        
        $this->assertEquals($expected, $doc->toArray("/posts/post"));
        
    }
    
    public function testToArray3()
    {
        $doc = \Byron\DOM::loadXml("<entry><name>Michael</name><email>michael@example.com</email></entry>");
        $n = new \Byron\DOM\DOMElement($doc->documentElement);
        $this->assertEquals(array(
            "name" => "Michael",
            "email" => "michael@example.com"
        ), $n->toArray());
    }
    
    public function testNoContentReturnsEmptyString()
    {
        $doc = \Byron\DOM::loadXMl("<root/>");
        $n = new \Byron\DOM\DOMElement($doc->documentElement);
        $this->assertEquals('', $n->toArray());
    }
    
    public function testAttributesReturnedInArray() {
        $doc = \Byron\DOM::loadXml("<root><entry id='8' name='llll'><title>Clem</title></entry><entry id='8899'><title>Jason</title></entry></root>");
        $row = new \Byron\DOM\DOMElement($doc->documentElement->firstChild);
        $this->assertEquals(
            array('id' => '8', 'name' => 'llll'),
            $row->attributeToArray()
        );
    }
    
    public function testNoAttributesGivesEmptyArray() {
        $doc = \Byron\DOM::loadXMl("<root/>");
        $n = new \Byron\DOM\DOMElement($doc->documentElement);
        $this->assertEquals(array(), $n->attributeToArray());
    }

    public function testAppendRowArrayEncodeValues()
    {
        $doc = \Byron\DOM::loadXml("<root/>");
        $root = new \Byron\DOM\DOMElement($doc->documentElement);
        $root->appendRowArray(array("name" => "<Clem>", "email" => "clem@example.com"));
        $this->assertXmlStringEqualsXmlString("<root><row><name>&lt;Clem&gt;</name><email>clem@example.com</email></row></root>", $doc->toXml());
    }
    
    public function testAppendRowArrayValuesAreXmlStrings()
    {
        $doc = \Byron\DOM::loadXml("<root/>");
        $root = new \Byron\DOM\DOMElement($doc->documentElement);
        $root->appendRowArray(array("name" => "<first>Clem</first>", "email" => "clem@example.com"), "qqqq", false);
        $this->assertXmlStringEqualsXmlString("<root><qqqq><name><first>Clem</first></name><email>clem@example.com</email></qqqq></root>", $doc->toXml());
    }
    
    public function testAppendArraySimple()
    {
        $doc = \Byron\DOM::loadXml("<root/>");
        $root = new \Byron\DOM\DOMElement($doc->documentElement);
        $root->appendArray(array("name" => "Clem", "email" => "clem@example.com"));
        $this->assertXmlStringEqualsXmlString("<root><name>Clem</name><email>clem@example.com</email></root>", $doc->toXml());
    }
    
    public function testAppendArrayEncodeCharacters()
    {
        $doc = \Byron\DOM::loadXml("<root/>");
        $root = new \Byron\DOM\DOMElement($doc->documentElement);
        $root->appendArray(array("name" => "Clem", "address" => array("line1" => "1600 <Pennsylvania> &ve", "line2" => "DC")));
        $this->assertXmlStringEqualsXmlString("<root><name>Clem</name><address><line1>1600 &lt;Pennsylvania&gt; &amp;ve</line1><line2>DC</line2></address></root>", $doc->toXml());
    }
    
    public function testXPath()
    {
        $doc = \Byron\DOM::loadXmlFile("fixtures/domtest2.xml");
        $xpath = new \Byron\DOM\DOMXPath($doc);
        $this->assertEquals("NL17626133", $xpath->evaluate("string(/ORDERS/ORDER[1]/ORDER_ID)"));
    }
    
    public function testToXml()
    {
        $s = "<root><element id=\"jjj\"><name>qqq  </name></element></root>";

        $doc = \Byron\DOM::loadXml($s);

        $this->assertXmlStringEqualsXmlString($s, $doc->toXml());
        
        $e = new \Byron\DOM\DOMElement($doc->documentElement);
        
        $this->assertXmlStringEqualsXmlString($s, $e->toXml());
    }
    
    public function testToArray2()
    {
        $doc = \Byron\DOM::loadXmlFile("fixtures/domtest1.xml");

        $res = $doc->toArray('/STATUS_MESSAGES/STATUS_MESSAGE/ORDER');

        $this->assertEquals(3, count($res));
        
        // Only test one because the array takes up a decent amount of space
        $this->assertEquals(array(
            'ORDER_ID' => '200000003',
            'TIMESTAMP' => '2010-07-16T13:42:14',
            'TRACKING_URL' => 'http://www.parcelforce.com/portal/pw/track?trackNumber=1113',
            'ORDER_LINE' =>
            array(
                array(
                    'ORDER_LINE_ID' => '1',
                    'STATUS_CODE' => 'SHIPPED',
                    'STATUS_DESCRIPTION' => 'Shipped',
                    'SKU' => '0030000000342',
                    'QUANTITY' => '28'
                ),
                array(
                    'ORDER_LINE_ID' => '2',
                    'STATUS_CODE' => 'SHIPPED',
                    'STATUS_DESCRIPTION' => 'Shipped',
                    'SKU' => '0250000000061',
                    'QUANTITY' => '1',
                ),
            ),
        ), $res[2]);
    }
    
    public function testAppendAttributeArray1()
    {
        $doc = \Byron\DOM::loadXml("<root/>");

        $root = new \Byron\DOM\DOMElement($doc->documentElement);
        $root->appendAttributeArray(array("foo" => "<bar>", "baz" => "quux qq ll"));

        $this->assertXmlStringEqualsXmlString("<root baz=\"quux qq ll\" foo=\"&lt;bar&gt;\"/>", $doc->toXml());
    }
    
    public function testAppendAttributeArray2()
    {
        $doc = \Byron\DOM::loadXml("<page><img src='foo.jpg'/></page>");
        
        $n = new \Byron\DOM\DOMElement($doc->documentElement->firstChild);
        
        $n->appendAttributeArray(array("foo" => "1", "bar" => "2"));
        
        $this->assertXmlStringEqualsXmlString("<page><img src='foo.jpg' foo='1' bar='2'/></page>", $doc->toXml());
    }
    
    public function testSetAttributeArray1()
    {
        $doc = \Byron\DOM::loadXml("<page><img src='foo.jpg'/></page>");
        
        $n = new \Byron\DOM\DOMElement($doc->documentElement->firstChild);
        
        $n->setAttributeArray(array("foo" => "1", "bar" => "2"));
        
        $this->assertXmlStringEqualsXmlString("<page><img foo='1' bar='2'/></page>", $doc->toXml());
    }
    
    public function testFoo()
    {
        $doc = \Byron\DOM::loadXml("<root><name>Michael</name></root>");
        $xpath = new \DOMXPath($doc->getDocument());
        $this->assertEquals("Michael", $xpath->evaluate("string(/root/name)"));
        
        $doc->loadXml("<root><name>Clem</name></root>");
        $xpath = new DOMXPath($doc->getDocument());
        $this->assertEquals("Clem", $xpath->evaluate("string(/root/name)"));
    }
    
    public function testTypes()
    {
        $doc = \Byron\DOM::loadXML("<root><name>Michael</name></root>");
        
        // Basic check of type
        
        $this->assertNotNull($doc);
        $this->assertType('\Byron\DOM\DOMDocument', $doc);
        $this->assertType('DOMDocument', $doc->getDocument());
    }
    
    public function testXPath2()
    {
        $doc = \Byron\DOM::loadXML("<root><name>Michael</name></root>");
        
        // Check XPath works
        
        $xpath = new \Byron\DOM\DOMXpath($doc);
        
        $this->assertEquals("Michael", $xpath->evaluate("string(/root/name)"));
        
        // Change the loaded XML, check that evaluate() returns
        // new value.
        
        $doc->loadXML("<root><name>Clem</name></root>");
        
        $xpath = new \Byron\DOM\DOMXpath($doc);
        
        $this->assertEquals("Clem", $xpath->evaluate("string(/root/name)"));
    }
    
    public function testXPath3()
    {
        $doc = \Byron\DOM::loadXML("<root><name>Michael</name></root>");
        
        // Check XPath works
        
        $xpath = new \Byron\DOM\DOMXPath($doc);
        
        $this->assertEquals("Michael", $xpath->evaluate("string(/root/name)"));
        
        // Change the loaded XML, check that evaluate() returns
        // new value.
        
        $doc->loadXML("<root><name>Clem</name></root>");
        
        $xpath = new \Byron\DOM\DOMXPath($doc);
        
        $this->assertEquals("Clem", $xpath->evaluate("string(/root/name)"));
    }

    public function testToArray()
    {
        $doc = \Byron\DOM::loadXML("<root><entry><name>Michael</name><email>mjs@beebo.org</email></entry><entry><name>Clem</name><email>clem@beebo.org</email></entry></root>");
        
        $entries = $doc->toArray("/root/entry");
        
        $this->assertEquals(2, count($entries));
        
        $this->assertEquals("Michael", $entries[0]["name"]);
        $this->assertEquals("mjs@beebo.org", $entries[0]["email"]);
        
        $this->assertEquals("Clem", $entries[1]["name"]);
        $this->assertEquals("clem@beebo.org", $entries[1]["email"]);
    }
    
    public function testElementToArraySingleValue()
    {
        $doc = \Byron\DOM::loadXML('<entry><foo>jjj</foo></entry>');
        
        $element = new \Byron\DOM\DOMElement($doc->documentElement);
        
        $object = $element->toArray();
        
        $this->assertEquals(array("foo" => "jjj"), $object); // Note: expected value is not array("foo" => array("jjj"))
    }
    
    public function testElementToArrayDoubleValue()
    {
        $doc = \Byron\DOM::loadXML('<entry><foo>jjj</foo><foo>bar</foo></entry>');
        
        $element = new \Byron\DOM\DOMElement($doc->documentElement);
        
        $object = $element->toArray();
        
        $this->assertEquals(array("foo" => array("jjj", "bar")), $object); // Compare to above
    }
    
    public function testElementToArrayTripleValue()
    {
        $doc = \Byron\DOM::loadXML('<entry><foo>jjj</foo><foo>bar</foo><foo>baz</foo></entry>');
        
        $element = new \Byron\DOM\DOMElement($doc->documentElement);
        
        $object = $element->toArray();
        
        $this->assertEquals(array("foo" => array("jjj", "bar", "baz")), $object);
    }
    
    public function testElementToArrayTwoDifferentValues()
    {
        $doc = \Byron\DOM::loadXML('<entry><foo>jjj</foo><bar>bar</bar></entry>');
        
        $element = new \Byron\DOM\DOMElement($doc->documentElement);
        
        $object = $element->toArray();
        
        $this->assertEquals(array("foo" => "jjj", "bar" => "bar"), $object);
    }
    
    public function testElementLarge()
    {
        $doc = \Byron\DOM::loadXML('<entry xmlns:google="http://base.google.com/ns/1.0" xmlns:twitter="http://api.twitter.com/">
      <id>tag:search.twitter.com,2005:7273675206</id>
      <published>2010-01-01T17:19:23Z</published>
      <link type="text/html" href="http://twitter.com/mjcld2009/statuses/7273675206" rel="alternate"/>
      <title>http://twitpic.com/w893m - mj/liz</title>
      <content type="html">&lt;a href=&quot;http://twitpic.com/w893m&quot;&gt;http://twitpic.com/w893m&lt;/a&gt; - mj/liz</content>
      <updated>2010-01-01T17:19:23Z</updated>
      <link type="image/png" href="http://a1.twimg.com/profile_images/596934470/mj_loud_normal.jpg" rel="image"/>
      <google:location>New York</google:location>
      <twitter:geo>
      </twitter:geo>
      <twitter:source>&lt;a href=&quot;http://twitpic.com/&quot; rel=&quot;nofollow&quot;&gt;TwitPic&lt;/a&gt;</twitter:source>
      <twitter:lang></twitter:lang>
      <author>
        <name>mjcld2009 (christine)</name>
        <uri>http://twitter.com/mjcld2009</uri>
      </author>
    </entry>');

        $element = new \Byron\DOM\DOMElement($doc->documentElement);

        $object = $element->toArray();
          
        $this->assertEquals($object['author'][0]['uri'], 'http://twitter.com/mjcld2009');
        $this->assertEquals($object['published'], '2010-01-01T17:19:23Z');
        $this->assertEquals($object['link'], array('', ''));
        $this->assertEquals($object['twitter:geo'], '');
    }
      
    public function testAppendFragment()
    {
        $doc = \Byron\DOM::loadXML('<root><p></p></root>');

        $element = new \Byron\Dom\DOMElement($doc->documentElement->firstChild);

        $element->appendFragment('<img src="foo.jpg"/>');

        $this->assertXmlStringEqualsXmlString('<root><p><img src="foo.jpg"/></p></root>', $doc->toXml());
    }
      
    public function testSetXml()
    {
        $doc = \Byron\DOM::loadXML('<root><p>Hi</p><p/></root>');

        $element = new \Byron\Dom\DOMElement($doc->documentElement->firstChild);

        $element->setXml('<img src="foo.jpg"/>');

        $this->assertXmlStringEqualsXmlString('<root><img src="foo.jpg"/><p/></root>', $doc->toXml());
    }
      
    public function testBodyIsText()
    {
          
        $doc = \Byron\DOM::loadXML('<quotes><quote source="p. 45">Appeals shall not be used...</quote></quotes>');

        $expected = array(
          array(
              "source" => "p. 45",
              "*" => "Appeals shall not be used..."
          )
        );

        $this->assertEquals($expected, $doc->toArray("/quotes/quote"));
    }      
      
    public function testBodyIsMarkedUpText()
    {
        $doc = \Byron\DOM::loadXML('<quotes><quote source="p. 45">Appeals shall <em>not</em> be used...</quote></quotes>');
          
        $expected = array(
          array(
              "source" => "p. 45",
              "*" => "Appeals shall <em>not</em> be used..."
          )
        );
          
        $this->assertEquals($expected, $doc->toArray("/quotes/quote"));
    }

    public function testDOMLoadXml_EntitiesTransformOn_Success()
    {
        $doc = \Byron\Dom::loadXML('<root>&ldquo;&rdquo;</root>', true);
        $this->assertXmlStringEqualsXmlString('<root>&#8220;&#8221;</root>', $doc->toXml());
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testDOMLoadXml_EntitiesTransformOff_Fail()
    {
        $doc = \Byron\Dom::loadXML('<root>&ldquo;&rdquo;</root>', false);
    }

}
