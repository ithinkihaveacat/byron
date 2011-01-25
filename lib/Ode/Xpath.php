<?php

namespace Byron\Ode;

class Xpath implements Pipe {
    
    protected $selector = null;
    protected $transformer = null;
    
    public function __construct($selector, $transformer)
    {
        $this->selector = $selector;
        $this->transformer = $transformer;
    }
    
    public function __invoke(&$doc, $args = array())
    {
        $xpath = new \Byron\Dom\DOMXPath($doc);

        $nl = $xpath->query($this->selector);
        $len = $nl->length;

        for ($i = 0; $i < $len; $i++) {
            $n = $nl->item($i);
            if ($n->nodeType != XML_ELEMENT_NODE) {
                continue;
            }
            $tmp = $this->transformer;
            $tmp(new \Byron\Dom\DOMElement($n), $doc);
        }
    }
    
}
