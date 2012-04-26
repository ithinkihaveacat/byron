<?php

class ConfigTest extends PHPUnit_Framework_Testcase {

    public function setUp()
    {
        if (!extension_loaded('openssl')) {
            $this->markTestSkipped('The [openssl] extension is not loaded');
        }        
    }
    
    public function testConfigFileNotFound()
    {
        $this->setExpectedException("InvalidArgumentException");
        
        $config = new \Byron\Config(__DIR__ . "/../fixtures/doesnotexist.ini");
    }
    
    public function testNoPasswordSet()
    {
        $this->setExpectedException("InvalidArgumentException");
        
        $config = new \Byron\Config(__DIR__ . "/../fixtures/config.ini");
        
        $config->getMandatory("config_test2");
    }

    public function testEncryption()
    {
        $s = "Hello, World!";

        $config = new \Byron\Config(__DIR__ . "/../fixtures/config.ini");
        $config->setPassword("qqqqqq");

        $v = $config->encrypt($s);
        
        $this->assertNotEquals("", $v);
        $this->assertEquals($s, $config->decrypt($v));
    }

    public function testConfig()
    {
        $config = new \Byron\Config(__DIR__ . "/../fixtures/config.ini");
        $config->setPassword("qqqqqq");

        $s = "Hello, World!";

        $this->assertEquals($s, $config->getMandatory("config_test1"));
        $this->assertEquals($s, $config->getMandatory("config_test2"));
    }

}
