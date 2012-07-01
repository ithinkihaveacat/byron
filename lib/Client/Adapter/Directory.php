<?php

namespace Byron\Client\Adapter;

/**
 * OVERVIEW
 *
 * Simple class to provide mocked up content to Zend_Http_Client and friends.
 * The content to provide is determined by the URL: you provide a directory
 * containing files whose names are masted on the URLs they correspond to,
 * point this adapter to the directory containing those files, and set this
 * to be the adapter used by the Zend_Http_Client.
 *
 * ADVANTAGES
 *
 * The main advantage is ease of use: you can generate fixtures simply by
 * injecting the adapter, and then waiting for it to throw an exception saying
 * that a fixture can't be found.  Then, simply run wget or curl against the
 * real URL, and store the result in the file indicated in the exception
 * error message.
 *
 * In addition, there's a simple mapping between fixture file names and URLs,
 * which makes it easy to generate new fixtures from existing ones by renaming 
 * files and copying directories.
 *
 * EXAMPLE
 *
 * 1.
 *
 * Create a "fixtures" directory that contains the file
 * "http_dev_example_com_80_test.xml".
 *
 * 2.
 *
 * In a *Test.php class, change the setUp() method to something like:
 *
 *     public function setUp() {
 *
 *          $client = new \Zend_Http_Client();
 *
 *          $config = array(
 *              "fixtures" => dirname(__FILE__) . "/../../fixtures",
 *              "host" => "test"
 *          );
 *          $adapter = new \Byron\Client\Adapter\Directory($config);
 *          $client->setAdapter($adapter);
 *
 *          $this->cient = $client;
 *
 *          return parent::setUp();
 *
 *      }
 *
 * 3.
 *
 * At this point, requests for "http://dev.example.com/test.xml" will return
 * the content from the file "http_dev_example_com_80_test.xml".  This will be 
 * served with content type text/xml.  (See $_types for the supported extensions
 * and content type mappings.)
 *
 * NOTE
 * 
 * The URLs are converted to filenames by a simple algorithm:
 * 
 *   (1) if the config array has a key "host" is defined, the hostname is 
         replaced by the corresponding value.  (This is mostly so that you 
         don't have to rename fixture files if your configuration changes.)
 *   (2) every non-word character is replaced with "_".
 * 
 * The most convenient way of figuring one what the fixture filename will be is 
 * simply to not provide a fixture; the adapter will then die with a message 
 * saying that file XXX cannot be found.
 *
 * LIMITATIONS
 *
 * There is no way to distinguish between HTTP verbs: GETs and POSTs to the same
 * URL return exactly the same response, for example.
 *
 * Similarly, there's no way to return different responses from the same URL.
 * (e.g. if you want to list objects, delete one, then list objects again.)
 *
 * Because the URL -> filename conversion process is lossy and non-reversible,
 * it's possible for multiple URLs can map to the same filename.  Ensure your
 * filenames are unique.  (Note that the query string *is* used to generate the
 * fixture filename.)
 *
 * @author Michael Stillwell <mjs@beebo.org>
 */

class Directory implements \Zend_Http_Client_Adapter_Interface
{

    /**
     * Stores the sequence of requests and responses.
     *
     * @var array
     */

    protected $_httpRequests = array();

    /**
     * The extensions we look for when resolving URLs to filenames, and the
     * content types they're served as.  For example the file XXX.xml will
     * be served with content type "text/xml".  The the extension is numeric,
     * then the corresponding value is used as the HTTP status code.  For
     * example the file XXX.404 will be served with HTTP status code "404 Not
     * Found".  (In this case, the content-type not be specified.)
     *
     * @var array
     */

    protected $_types = array(
        "xml" => "text/xml",
        "txt" => "text/plain",
        "html" => "text/html",
        "json" => "application/json",
        "404" => "404 Not Found",
        "500" => "500 Internal Server Error"
    );

    protected $_config = array();

    /**
     * $config is of the form:
     *
     *   array(
     *       "fixtures" => "/foo/bar/baz",
     *       "host" => "test"
     *   )
     *
     * The "fixtures" is a directory that is scanned for fixture files, where the
     * filenames are.
     *
     * @param array $config
     */

    public function __construct($config)
    {
        $this->setConfig($config);
    }

    /**
     * Set the configuration array for the adapter
     *
     * @param array $config
     */

    public function setConfig($config = array())
    {
        if (!is_array($config)) {
            //  'Zend/Http/Client/Adapter/Exception.php';
            throw new Zend_Http_Client_Adapter_Exception(
                '$config expects an array, ' . gettype($config) . ' recieved.');
        }

        foreach ($config as $k => $v) {
            $this->_config[strtolower($k)] = $v;
        }
    }


    /**
     * Connect to the remote server
     *
     * @param string  $host
     * @param int     $port
     * @param boolean $secure
     */
    public function connect($host, $port = 80, $secure = false) { }

    /**
     * Send request to the remote server
     *
     * @param string        $method
     * @param Zend_Uri_Http $url
     * @param string        $http_ver
     * @param array         $headers
     * @param string        $body
     * @return string Request as text
     */

    public function write($method, $uri, $http_ver = '1.1', $headers = array(), $body = '')
    {

        // Sort keys in alphabetical order so that the generated
        // filenames match the fixture filenames.  (The order
        // is otherwise somewhat random.)

        parse_str($uri->getQuery(), $query);
        ksort($query);
        $uri->setQuery($query);

        $originalUri = $uri->getUri();

        if (isset($this->_config["host"])) {
            $uri->setHost($this->_config["host"]);
        }

        $basename = preg_replace("/\W+/", "_", $uri->getUri());

        foreach ($this->_types as $extension => $type) {

            $filename = $this->_config["fixtures"] . '/' . $basename . '.' . $extension;

            if (file_exists($filename)) {

                if (preg_match("/^\d+/", $type)) {
                    $response = $this->_serve($type, "", $type);
                } else {
                    $response = $this->_serve($type, file_get_contents($filename));
                }

                $this->_httpRequests[] = array(
                    "uri" => $uri,
                    "body" => $body,
                    "filename" => $filename,
                    "response" => $response
                );

                return;

            }
        }

        // Couldn't find a fixture matching the request.

        throw new Exception(sprintf(
            "Can't find fixture for URL\n\n%s\n\nLooked at\n\n%s/%s.{%s}\n",
            $originalUri,
            $this->_config["fixtures"],
            $basename,
            join(",", array_keys($this->_types))
        ));

    }

    /**
     * Returns the last "response".
     *
     * @return string
     */

    public function read()
    {
        return $this->_httpRequests[count($this->_httpRequests) - 1]["response"];
    }

    /**
     * Close the connection to the server
     *
     */
    public function close() {}

    public function clearHttpRequests()
    {
        $this->_httpRequests = array();
    }

    public function getHttpRequests()
    {
        return $this->_httpRequests;
    }

    protected function _serve($type, $content, $status = "200 OK")
    {
        return join("\r\n", array(
            "HTTP/1.1 $status",
            "Content-Type: $type",
            "",
            $content
        ));
    }
}
