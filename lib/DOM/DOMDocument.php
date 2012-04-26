<?php

/**
 * This class proxies a DOMDocument, adding a few convenience functions.
 */

namespace Byron\DOM;

class DOMDocument extends \Byron\Proxy {
    
    public function __construct(\DOMDocument $object) {
        parent::__construct($object);
    }

    /**
     * Returns the underlying \DOMDocument object.
     *
     * @return \DOMDocument
     */
    
    public function getDocument() {
        return $this->_object;
    }

    /**
     * Set the underlying \DOMDocument object.
     *
     * @param \DOMDocument $document
     * @return \DOMDocument
     */

    public function setDocument(DOMDocument $document) {
        return $this->_object = $document;
    }
    
    /**
     * Converts a nodelist, as selected by an XPath expression, to an array
     * of arrays.  (This method may be useful if the bulk of your document is 
     * essentially tabular data which you want to convert to arrays--use XPath
     * to select the elements you're interested in, and you're done.)
     * 
     * @param string $exp XPath expression (returning a nodelist)
     */
    
    public function toArray($exp) {
        
        $xpath = new DOMXPath($this);
        
        $nl = $xpath->query($exp);

        $a = array();
        foreach ($nl as $n) {
            
            if ($n->nodeType == XML_DOCUMENT_NODE) {
                $t = new DOMElement($n->documentElement);
            } else if ($n->nodeType == XML_ELEMENT_NODE) {
                $t = new DOMElement($n);
            } else {
                continue;
            }
            $v = $t->toArray();
            if ($v) {
                if (is_array($v)) {
                    $a[] = array_merge($t->attributeToArray(), $v);
                } else {
                    $a[] = array_merge($t->attributeToArray(), array("*" => $v));
                }
            } else {
                $a[] = array_merge($t->attributeToArray());
            }
        }

        return $a;
        
    }

    /**
     * Returns the document, serialised as XML.
     *
     * @return string XML string
     */

    public function toXml() {
        return $this->getDocument()->saveXML($this->getDocument()->documentElement);
    }
    
}
