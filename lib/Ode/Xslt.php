<?php

namespace Byron\Ode;

class Xslt implements Pipe {
    
    protected $stylesheet = null;
    
    /**
     * @param string|DOMDocument $stylesheet
     */

    public function __construct($stylesheet)
    {
        if (is_string($stylesheet)) {
            $this->stylesheet = \Byron\Dom::loadXmlFile($stylesheet);
        } else {
            $this->stylesheet = $stylesheet;
        }
    }
    
    public function __invoke(&$doc, $args = array())
    {
        $p = new \XSLTProcessor();
        $p->importStyleSheet($this->stylesheet->getDocument());
        
        if ($args) {
            $p->setParameter('', $args);
        }
        
        $res = $p->transformToDoc($doc->getDocument());
         
        if (!$res) {
            throw new \Exception("transformation by [$this->filename] failed");
        }
        
        $doc = new \Byron\Dom\DOMDocument($res);
    }
    
}