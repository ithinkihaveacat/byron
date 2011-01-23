<?php

namespace Byron;

interface Cache {
    
    /**
     * Retrieves value associated with $key from the cache backend.  Because
     * backends indicate "not found" in different ways, consumers must
     * assume that any return value that satisfies empty() is a cache miss,
     * and act accordingly.
     * 
     * @param $key
     * @return mixed
     */
    
    public function get($key);
    
    /**
     * Similar to set(), except that it returns false if $key already exists.
     * 
     * @param $key
     * @param $value
     * @param $expires
     * @return boolean false if $key already exists, otherwise true
     */
    
    public function add($key, $value, $expires = null);
    
    /**
     * Saves the $key/$value pair to the cache backend.  Saving anything for
     * that is empty() is not supported--even if this value makes it through
     * to the backend, other clients will assume any such value returned is
     * equivalent to a cache miss.  (Classes extending this class should check
     * to see if empty($value) is true, and throw an error if so.)
     * 
     * @param string $key
     * @param mixed $value
     * @param $expires
     * @return unknown_type
     */
    
    public function set($key, $value, $expires = null);
    
    public function flush();
    
}
