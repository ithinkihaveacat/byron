<?php

// Available assertions:
//
//   http://www.phpunit.de/manual/3.6/en/api.html#api.assert

require_once('PHPUnit/Autoload.php');
require_once(__DIR__ . '/../byron.php');
require_once(__DIR__ . '/../vendor/autoload.php');

// Arrange for Zend_* to be found.  Assumed to be installed at the
// same level as "byron" itself, in a directory called "zend-framework".

spl_autoload_register(function ($klass) {

    // Map $klass to the $filename it's supposed to be defined in
    
    if (strpos($klass, "Zend_") === 0) {
        
        $filename = sprintf("%s.php", str_replace("_", "/", $klass));
        require_once($filename);
        
    }
    
});

// Helper functions

blib('raw');
