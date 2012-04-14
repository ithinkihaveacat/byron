<?php

// Available assertions:
//
//   http://www.phpunit.de/manual/3.4/en/api.html#api.assert

require_once('PHPUnit/Autoload.php');
require_once(__DIR__ . '/../byron.php');

// Arrange for Zend_* to be found.  Assumed to be installed at the
// same level as "byron" itself.

spl_autoload_register(function ($klass) {

    // Map $klass to the $filename it's supposed to be defined in
    
    if (strpos($klass, "Zend_") === 0) {
        
        $filename = sprintf("%s/../../%s.php", __DIR__, str_replace("_", "/", $klass));

        if (is_readable($filename)) { // can use stream_resolve_include_path() to search include path
            require_once($filename);
        } 
        
    }
    
});

set_include_path(join(PATH_SEPARATOR, array(
    __DIR__ . "/../../",
    get_include_path()
)));

// Helper functions

blib('raw');
