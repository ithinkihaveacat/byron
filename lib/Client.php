<?php

namespace Byron;

/**
 * \Byron\Client extends \Zend_Http_Client, and behaves like it in most respects.
 * However, there are two crucial differences: (1) the constructor takes a "key", which
 * will generally be an API key, or a datastructure representing an API key; and (2)
 * the return value of an actual request can be configured to be just the body of the 
 * response, and not the response object itself.  (This is useful if the service
 * you're talking to isn't very RESTful, and pretty much always returns a 200, embedding
 * the real status in the body of the response.)
 */

class Client extends \Zend_Http_Client {

    /**
     *
     * @var array
     */
    
    protected $key = null;

    /**
     * Whether to return a response object, or the raw response body.  The value you
     * want more or less depends on how RESTful the service you're talking to is.  If
     * it's very RESTful (e.g. 404 indicates something is missing, rather than an
     * error), then you probably want the response object.  On the other hand, if the
     * server pretty much always returns a 200 response, and embeds error messages
     * in the content itself, then you probably want the raw response body.
     *
     * @var boolean
     */

    protected $returnResponseObject = false;
    
    public function __construct($key = null)
    {
        $this->setKey($key);
    }

    public function setKey($key) {
        return $this->key = $key;
    }
    
    public function getKey() {
        return $this->key;
    }
    
    public function setReturnResponseObject($r)
    {
        return $this->returnResponseObject = $r;
    }
    
    public function getReturnResponseObject()
    {
        return $this->returnResponseObject;
    }

    /**
     * 
     * @param $url string
     * @param $args array
     * @return string
     */

    public function GET($url, $args = array()) {
        
        $frag = parse_url($url);
        
        // We do this little shuffle and rewrite of $url and $args to deal
        // with the case where $url already contains a query string--in which
        // case we overwrite and amend it with any parameter given in $args.
        
        if (array_key_exists("query", $frag)) {
            $url = sprintf("%s://%s/%s", $frag["scheme"], $frag["host"], $frag["path"]);
            parse_str($frag["query"], $args2);
            $args = array_merge($args2, $args);
        }
        
        $this->resetParameters();
        $this->setUri($url);
        $this->setMethod('GET');
        $this->setParameterGet($args);

        return $this->request();
        
    }

    /**
     * 
     * @param $url string
     * @param $args array
     * @return string
     */
    
    public function POST($url, $args = array()) {
        
        $this->resetParameters();
        $this->setUri($url);
        $this->setMethod('POST');
        $this->setParameterPost($args);
        
        return $this->request();
        
    }

    /**
     * Perform the actual request using the client instance.  If the instance variable
     * $returnResponseObject is true a Zend_Http_Response is returned, otherwise the
     * response body only is returned.  (In this case some basic error checking
     * is performed--an exception is thrown if the server returned a 404, for example.)
     *
     * @return Zend_Http_Response|string
     * @throws Service_Exception
     */

    public function request() {

        $response = parent::request();

        if ($this->returnResponseObject) {
            return $response;
        } 
        
        if (!$response->isSuccessful()) {
            if ($response->isError()) {
                throw new \Exception(sprintf("server returned status code [%d] for [%s]", $response->getStatus(), $this->getUri()));
            } else {
                throw new \Exception(sprintf("couldn't contact server at [%s]", $this->getUri()));
            }
        }
        else {
            return $response->getBody();
        }

    }
    
}