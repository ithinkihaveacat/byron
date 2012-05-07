<?php

// Available assertions:
//
//   http://www.phpunit.de/manual/3.6/en/api.html#api.assert

require_once('PHPUnit/Autoload.php');
require_once(__DIR__ . '/../byron.php');

// Arrange for Zend_* to be found.  Assumed to be installed at the
// same level as "byron" itself, in a directory called "zend-framework".

spl_autoload_register(function ($klass) {

    // Map $klass to the $filename it's supposed to be defined in
    
    if (strpos($klass, "Zend_") === 0) {
        
        $filename = sprintf("%s.php", str_replace("_", "/", $klass));
        require_once($filename);
        
    }
    
});

set_include_path(join(PATH_SEPARATOR, array(
    __DIR__ . "/../../zend-framework/library",
    __DIR__ . "/../zend-framework/library", # For http://travis-ci.org
    get_include_path()
)));

// Helper functions

blib('raw');
