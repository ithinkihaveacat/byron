<?php

/**
 * Simple routines for restoring and retrieving configuration 
 * information based around the PHP function parse_ini_file().
 *
 * getMandatory() and getOptional() are used in preference
 * to get() with some arguments because I kept forgetting
 * what the arguments meant, and their order.  It's better
 * this way, trust me!
 * 
 * @license MIT
 * @author Michael Stillwell <mjs@beebo.org>
 */

namespace Byron;

class Config
{

    protected static $instance = null;

    protected $data = array();

    protected $filename = array();

    protected $password = null;

    public function __construct($filename = null) {
        
        if (is_null(self::$instance)) {
            self::$instance = $this;
        }
        
        if (!is_null($filename)) {
            $this->load($filename);
        }
        
    }

    public static function getInstance() {
        return is_null(self::$instance) ? new self() : self::$instance;
    }
    
    /**
     * Loads the configuration information given in file $filename into
     * the configuration object.  This uses the function parse_ini_file()
     * internally, so the passed file should be compatible with it.
     *
     * If load is called multiple times, the configuration settings are 
     * merged; in the case of a conflict, last file wins.
     *
     * @param string $filename
     */

    public function load($filename) {
    
        if (!file_exists($filename)) {
            throw new \Exception("configuration file [$filename] does not exist");
        }
        else {
            $filename = realpath($filename);
        }
        
        $d = @parse_ini_file($filename);
        
        if (is_null($d)) {
            throw new \Exception("couldn't parse configuration file [$filename]");
        }
        
        $this->data = array_merge($this->data, $d);
        $this->filename[] = $filename;

        return $d;
        
    }
    
    /**
     * Returns true if key $k exists, otherwise false.
     *
     * @param string $k
     * @return boolean
     */
    
    public function exists($k) {
        return array_key_exists($k, $this->data);
    }
    
    /**
     * Returns the configuration value associated with key $k, dying if
     * it does not exist.
     *
     * @param string $k
     * @return mixed
     */
    
    public function getMandatory($k) {
        
        if (array_key_exists($k, $this->data)) {
            return $this->decrypt($this->data[$k]);
        }
        else {
            throw new \Exception(sprintf("key [%s] missing from [%s]", $k, join(";", $this->filename)));
        }
        
    }
    
    /**
     * Returns the configuration value associated with key $k, returning
     * $default if it does not exist.
     *
     * @param string $k
     * @param mixed $default optional, defaults to null
     * @return mixed
     */
    
    public function getOptional($k, $default = null) {
        return array_key_exists($k, $this->data) ? $this->decrypt($this->data[$k]) : $default;
    }
    
    /**
     * Store the value $v as key $k in the configuration object.  (This is
     * not persisted; this class provides no way to write configuration information
     * to disk.)
     *
     * @param string $k
     * @param mixed $v
     * @return mixed
     */

    public function set($k, $v) {
        return $this->data[$k] = $v;
    }
    
    public function setPassword($password) {
        $this->password = $password;
    }

    public function getPassword() {

        if (is_null($this->password)) {

            if (!defined('DOCUMENT_ROOT')) {
                throw new \Exception("password is not set, and constant [DOCUMENT_ROOT] is undefined");
            }

            $filename = DOCUMENT_ROOT . "/../config/password";
            if (!file_exists($filename)) {
                throw new \Exception("password file [$filename] does not exist");
            }

            $this->password = file_get_contents($filename);

        }

        return $this->password;

    }

    public function encrypt($s) {
        return 'encrypted:' . openssl_encrypt($s, "aes-256-cbc", $this->getPassword(), false, "8a43818f1ec21007");
    }

    public function decrypt($s) {
        if (strpos($s, "encrypted:") === 0) {
            return openssl_decrypt(substr($s, strlen("encrypted:")), "aes-256-cbc", $this->getPassword(), false, "8a43818f1ec21007");
        } else {
            return $s;
        }
    }

}
