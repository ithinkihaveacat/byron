<?php

class ConfigTest extends PHPUnit_Framework_Testcase {
    
    public function testConfigFileNotFound()
    {
        $this->setExpectedException("Exception");
        
        $config = new \Byron\Config("fixtures/doesnotexist.ini");
    }
    
    public function testNoPasswordSet()
    {
        $this->setExpectedException("Exception");
        
        $config = new \Byron\Config("fixtures/config.ini");
        
        $config->getMandatory("config_test2");
    }

    public function testEncryption()
    {
        $s = "Hello, World!";

        $config = new \Byron\Config("fixtures/config.ini");
        $config->setPassword("qqqqqq");

        $v = $config->encrypt($s);
        
        $this->assertNotEquals("", $v);
        $this->assertEquals($s, $config->decrypt($v));
    }

    public function testConfig()
    {
        $config = new \Byron\Config("fixtures/config.ini");
        $config->setPassword("qqqqqq");

        $s = "Hello, World!";

        $this->assertEquals($s, $config->getMandatory("config_test1"));
        $this->assertEquals($s, $config->getMandatory("config_test2"));
    }

}