<?php

namespace Byron\Client\Adapter;

/**
 * Simple Zend_Http_Client_Adapter_Interface for always returning a constant response
 * taken from a file.  e.g. <em>always</em> return 404 Not Found, irrespective of the request.
 * This is somewhat hacky, because it extends Zend_Http_Client_Adapter_Test, but renders various methods
 * inactive, such as setResponse().  (i.e. setResponse() still exists, but it doesn't do anything.)
 */

class File extends \Zend_Http_Client_Adapter_Test
{

    /**
     * @var string
     */

    protected $_filename;

    /**
     * The (static) response to return, for example, "HTTP/1.1 404 File Not Found".
     *
     * @param string $response
     */

    public function __construct($filename)
    {
        if (!file_exists($filename)) {
            throw new Exception("Can't find fixture [$filename]");
        }

        if (!is_readable($filename)) {
            throw new Exception("Can't read fixture [$filename]");
        }

        $this->_filename = $filename;
    }

    public function read()
    {
        return file_get_contents($this->_filename);
    }

}
