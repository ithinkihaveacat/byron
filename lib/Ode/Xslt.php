<?php

namespace Byron\Ode;

class Xslt implements Pipe {
    
    protected $stylesheet = null;
    protected $args = null;
    
    /**
     * @param string|DOMDocument $stylesheet
     * @param mixed $args
     */

    public function __construct($stylesheet, $args = null)
    {
        if (is_string($stylesheet)) {
            $this->stylesheet = \Byron\Dom::loadXmlFile($stylesheet);
        } else {
            $this->stylesheet = $stylesheet;
        }
        $this->args = $args;
    }
    
    public function __invoke(&$doc)
    {
        $p = new \XSLTProcessor();
        $p->importStyleSheet($this->stylesheet->getDocument());

        if ($this->args) {
            $p->setParameter('', $this->args);
        }
        
        $res = $p->transformToDoc($doc->getDocument());
         
        if (!$res) {
            throw new \Exception("transformation by [$this->filename] failed");
        }
        
        $doc = new \Byron\Dom\DOMDocument($res);
    }
    
}