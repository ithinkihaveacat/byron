<?php

namespace Byron\Client\Adapter;

/**
 * Simple Zend_Http_Client_Adapter_Interface for always returning a constant 
 * return code.  e.g. <em>always</em> return 404 Not Found, irrespective of the
 * request.  This is somewhat hacky, because it extends 
 * Zend_Http_Client_Adapter_Test, but renders various methods inactive, such as
 * setResponse().  (i.e. setResponse() still exists, but it doesn't do anything.)
 */

class String extends \Zend_Http_Client_Adapter_Test
{

    /**
     * @var string
     */
    
    protected $_response;

    /**
     * @var string
     */

    protected $_last_write;

    /**
     * The (static) response to return, for example, "HTTP/1.1 404 File Not Found".
     *
     * @param string $response
     */

    public function __construct($response)
    {
        $this->_response = $response;
    }

    public function read()
    {
        return $this->_response;
    }

    public function write($method, $uri, $http_ver = '1.1', $headers = array(), $body = '')
    {

        $string = parent::write($method, $uri, $http_ver, $headers, $body);

        $this->_last_write = array(
            "method" => $method,
            "uri" => $uri,
            "http_ver" => $http_ver,
            "headers" => $headers,
            "body" => $body,
            "string" => $string
        );

    }

    public function getLastWrite()
    {
        return $this->_last_write;
    }

}
