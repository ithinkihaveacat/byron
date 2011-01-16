<?php

/**
 * Class autoloader for all classes in the \Byron namespace.  If attempting to load such 
 * a class, but no such class is found, and exception is thrown.
 */

spl_autoload_register(function ($klass) {
    
    // Map $klass to the $filename it's supposed to be defined in
    
    if (strpos($klass, "Byron\\") === 0) {
        
        $filename = sprintf("%s/%s/%s.php", __DIR__, "lib", str_replace("\\", "/", substr($klass, strlen("Byron\\"))));
        
        // Does $filename exist?
    
        if (is_readable($filename)) { // can use stream_resolve_include_path() to search include path
            require_once($filename);
        } 
        
        // It seems like it would be a good idea to throw an Exception()
        // here saying that $filename could not be found, but this is
        // less helpful than you might think.  It works fine if there's
        // only one callback in the spl_autoload stack, but if there's
        // another one after this, then even if we throw an exception here,
        // the later callback function gets called.  If it also throws
        // an exception, then it's the later exception that gets reported
        // to the client, not yours.  So, since you can't rely on any
        // Exception you might throw here to be usable by the caller, it's
        // better to not throw an exception at all.
        
    }
    
});

/**
 * Simple function to "require" library files in the "utils" directory. 
 * (Since, as functions, the autoload mechanism doesn't apply.)
 *
 */

function blib($s) {
    require_once(sprintf("%s/utils/%s.php", __DIR__, $s));
}
