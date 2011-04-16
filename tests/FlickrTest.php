<?php

class FlickrTest extends PHPUnit_Framework_Testcase {
    
    public function testConstruct_NoArguments_ThrowsException()
    {
        $this->setExpectedException('Exception');
        
        $flickr = new \Byron\Client\Flickr();
    }

    public function testConstruct_NoApiKey_ThrowsException()
    {
        $this->setExpectedException('Exception');

        $flickr = new \Byron\Client\Flickr(array());
    }

    public function testConstruct_ApiKeyIsNull_ThrowsException()
    {
        $this->setExpectedException('Exception');

        $flickr = new \Byron\Client\Flickr(array(
            "api_key" => null,
            "api_secret" => null,
            "api_token" => null
        ));
    }

    public function testConstruct_ApiSecretIsNull_ThrowsException()
    {
        $this->setExpectedException('Exception');

        $flickr = new \Byron\Client\Flickr(array(
            "api_key" => null,
            "api_secret" => null,
            "api_token" => null
        ));
    }

}
