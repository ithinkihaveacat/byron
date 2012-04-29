<?php

namespace Byron\DOM;

class DOMElement extends \Byron\Proxy {
    
    protected static $_map = array(
        0x0000, 0x001f, 0, 0xffff,
        0x0026, 0x0026, 0, 0xffff, // &
        0x003c, 0x003c, 0, 0xffff, // <
        0x003e, 0x003e, 0, 0xffff, // >
        0x007f, 0xffff, 0, 0xffff
    );

    public function __construct(\DOMElement $object) {
        parent::__construct($object);
    }

    /**
     * Returns the underlying (proxied) DOMElement object.
     *
     * @return \DOMElement
     */

    public function getElement() {
        return $this->_object;
    }

    /**
     * Sets the underlying (proxied) DOMElement object.
     *
     * @param \DOMElement $element
     * @return \DOMElement
     */

    public function setElement(\DOMElement $element) {
        return $this->_object = $element;
    }
    
    /**
     * Converts an array to XML and adds to the element.  Example:
     * 
     *   $doc = DOMDocument::loadXML("<root/>");
     *   $e = new DOMElementX($doc->documentElement);
     *   $e->appendArray(array("name" => "Michael", "email" => "michael@example.com"));
     *   echo $doc->saveXML();
     * 
     * Output:
     * 
     *   <?xml version="1.0"?>
     *   <root>
     *     <row>
     *       <name>Michael</name>
     *       <email>michael@example.com</email>
     *     </row>
     *    </root>
     * 
     * @param array $hash
     * @param string $name the name of the new element to add
     * @param boolean $encode if true (default) $hash's values are encoded as XML; otherwise values are considered XML fragments 
     * @return DOMElement the newly added element
     */
    
    public function appendRowArray($hash, $name = "row", $encode = true) {
        
        $doc = $this->ownerDocument;
        $e = new self($doc->createElement($name));

        if ($encode) {
            $e->appendArray($hash);
            $this->appendChild($e->getElement());
        }
        else {
            foreach ($hash as $k => $v) {
                $c = new self($doc->createElement($k));
                $c->appendFragment($v);
                $e->appendChild($c->getElement());
            }
            $this->appendChild($e->getElement());
        }
        
        return $e;
        
    }

    /**
     * Converts a multi-dimensional array to XML, and appends it to the element.
     * Keys are correspond to element names, and values become content.
     * 
     * This method differs from appendRowArray() in that it handles recursive structures,
     * but is unable to handle embedded XML.
     *
     * @param array $hash
     */

    public function appendArray($hash)
    {

        $doc = $this->ownerDocument;

        foreach ($hash as $k => $v) {
            if (is_array($v)) {
                $e = new self($doc->createElement($k));
                $e->appendArray($v);
                $this->appendChild($e->getElement());
            } else {
                $this->appendChild($doc->createElement($k, mb_encode_numericentity($v, self::$_map, 'utf-8')));
            }
        }

        return $this;

    }
    
    /**
     * Converts an array to XML, and add to the element as attributes.  Example:
     * 
     *   $doc = \Byron\DOM::loadXML("<root/>");
     *   $e = new \Byron\DOM\DOMElement($doc->documentElement);
     *   $e->appendAttributeArray(array("name" => "Michael", "email" => "michael@example.com"));
     *   echo $doc->saveXML();
     * 
     * Output:
     * 
     *   <?xml version="1.0"?>
     *   <root>
     *     <row name="Michael" email="michael@example.com"/>
     *   </root>
     * 
     * @param $hash
     * @param string $name the name of the new element to add
     * @return DOMElement the newly added element
     */
    
    function appendRowAttributeArray($hash, $name = "row")
    {
        
        $doc = $this->ownerDocument;
        $e = $doc->createElement($name);
        
        foreach ($hash as $k => $v) {
            $e->setAttribute($k, $v);
        }
        
        $this->appendChild($e);
        
        return $e;
        
    }

    /**
     * Transforms the keys of $hash into attributes, where the corresponding
     * values become the attributes' values.
     *
     * @param array $hash
     * @return \Byron\DOM\DOMElement
     */

    public function appendAttributeArray($hash)
    {
        foreach ($hash as $k => $v) {
            $this->setAttribute($k, $v);
        }

        return $this;
    }
    
    /**
     * Transforms the keys of $hash into attributes, first removing any existing
     * attributes.
     * 
     * @param array $hash
     * @retur \Byron\DOM\DOMElement
     */
    
    public function setAttributeArray($hash)
    {
        // Remove existing attributes (any better way??)
        
        $i = 0;
        while ($att = $this->attributes->item($i++)) {
            $this->removeAttributeNode($att);
        }
        
        return $this->appendAttributeArray($hash);
    }
    
    /**
     * Interprets string $s as XML, and appends it to the element.  For example, if element $e is "<foo/>",
     * $e->appendFragment("<name>Michael</name>") results in "<foo><name>Michael</name></foo>". 
     * 
     * @param string $s
     * @return \Byron\DOM\DOMElement
     */
    
    function appendFragment($s) {
        
        $f = $this->ownerDocument->createDocumentFragment();
        $f->appendXML($s);
        $this->appendChild($f);
        
        return $this;
        
        /*
         * The DOM standards-compliant way of doing this (if you're porting to another language) is something like:
         * 
         *   $doc = new DOMDocument();
         * 
         *   $res = @$doc->loadXML("<root>" . $s . "</root>"); // loadHTML() is a bit more lenient than loadXML() (it allows XHTML entities)
         *   
         * 
         *   $nl = $doc->documentElement->childNodes;
         *
         *   for ($i = 0; $i < $nl->length; $i++) {
         *     $this->object->appendChild($this->object->ownerDocument->importNode($nl->item($i), true));
         *   }
         * 
         *   return $this->object;
         */
    
    }
    
    /**
     * Interprets $s as XML, and replaces this node with its contents.  i.e. the node is destroyed
     * (completely removed from the DOM).
     *
     * @param string $s
     */
    
    public function setXml($s)
    {
        $f = $this->ownerDocument->createDocumentFragment();
        $f->appendXML($s);

        // For some reason the seemingly-equivalent replaceChild() doesn't
        // work.
        $this->parentNode->insertBefore($f, $this->getElement());
        $this->parentNode->removeChild($this->getElement());
    }
    
    /**
     * Returns the attributes of the DOMElement as an array.
     * 
     * @return array
     */
    
    public function attributeToArray() {
        
        $a = array();
        // argh, must be a better way of extracting attributes...
        $i = 0;
        while ($att = $this->attributes->item($i++)) {
            $a[$att->nodeName] = $att->nodeValue;
        }
        return $a;
    
    }
    
    /**
     * Returns the element serialised as XML.
     *
     * @param boolean $includeRoot if true, include the element itself in the output
     * @return string
     */

    public function toXml($includeRoot = true)
    {
        if ($includeRoot) {
            return $this->getElement()->ownerDocument->saveXML($this->getElement());
        } else {
            $s = array();
            foreach ($this->childNodes as $c) {
                $s[] = $this->getElement()->ownerDocument->saveXML($c);
            }
            return join("", $s);
        }
    }
    
    /**
     * Converts element to an array, where keys correspond to the
     * names of elements, and values correspond to the corresponding
     * (text) values.
     *
     * @return array
     */
    
    public function toArray() {
        
        // If any of the children are non-empty text nodes, then we're dealing
        // with something like "The <em>rain</em> in Spain...", which we want
        // to return as a string.
        
        foreach ($this->childNodes as $c) {
            if ($c->nodeType == XML_TEXT_NODE) {
                if (trim($c->wholeText) != '') {
                    return $this->toXml(false);
                }
            }
        }

        // Get a list of all the children that are element nodes.  (As opposed to,
        // say, text nodes.)
        
        $children = array();        

        foreach ($this->childNodes as $c) {
            if ($c->nodeType == XML_ELEMENT_NODE) {
                $children[] = $c;
            }
        }
        
        if ($children) {

            // If the children include element nodes, apply toArray() to the them
            // recursively, and return an array.
            
            $a = array();
            foreach ($children as $c) {
                
                $tmp = new self($c);
                $v = $tmp->toArray();
                
                // The following code assumes that the children will have the same "form"--if one is
                // a string, then the others will too.  In other words, the following sort of XML
                // will lead to unpredictable results:
                //
                //   <row>
                //     <person>Mike</person>
                //     <person><name>Clem</name></person>
                //   </row>
                //
                // You can have all "string" values, or all "array" values, but you can't have both.
                
                if (is_array($v)) {
                    
                    // If $v is an array, then $tmp was an element with children,
                    // and we should merge in the attribute array.  (The
                    // alternative is that $v is a string, in which case
                    // there will be no attributes to merge.)
                    $v = array_merge($tmp->attributeToArray(), $v);
                    
                    if (!array_key_exists($c->tagName, $a)) {
                        // First time we've seen this tagName--create array
                        $a[$c->tagName] = array($v);
                    } else {
                        // Second or subsequent time we've seen this tagName--append to existing array
                        $a[$c->tagName][] = $v;
                    }

                } else {
                    
                    if (!array_key_exists($c->tagName, $a)) {
                        // First time we've seen this tagName--value is string
                        $a[$c->tagName] = $v;
                    } else if (!is_array($a[$c->tagName])) {
                        // Second time we've seen this tagName--convert value to array
                        $a[$c->tagName] = array($a[$c->tagName], $v);
                    } else {
                        // Third or subsequent time we've seen this tagName--append to array
                        $a[$c->tagName][] = $v;
                    }
                    
                }
                
            }
            return $a;
            
        } else {

            // If there are no element nodes, return a string.
            
            return isset($this->firstChild->wholeText) ? trim($this->firstChild->wholeText) : "";
            
        }

    }

}
