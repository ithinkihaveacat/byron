<?php

namespace Byron\Ode;

interface Pipe
{
    
    // Has to be pass by reference because some systems (XSL) don't modify the document
    // in place, so we have to do it for them.
    public function __invoke(&$doc, $args = array()); 
    
};