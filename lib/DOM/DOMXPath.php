<?php

namespace Byron\DOM;

class DOMXPath extends \DOMXPath
{
    
    public function __construct(\Byron\DOM\DOMDocument $doc)
    {
        parent::__construct($doc->getDocument());
    }
    
}
