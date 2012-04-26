<?php

namespace Local {

    class Baz extends \Byron\Broker\Plugin
    {
        public function getService()
        {
            return "qqqqqq";
        }
    }

}

namespace {

    class LocalFoo extends \Byron\Broker\Plugin
    {
        public function getService()
        {
            return "qqqqqq";
        }
    }

    class LocalBar
    {
    }

    class LocalQuux extends \Byron\Broker\Plugin
    {
        public function getService()
        {
            return $this->getShared()->getMandatory("config_test1");
        }
    }

    class BrokerTest extends PHPUnit_Framework_Testcase
    {
    
        public function testFooService()
        {
            $broker = new \Byron\Broker("Local%s");
        
            $this->assertEquals("qqqqqq", $broker->getFooService());
        }
    
        public function testExtendsWrongClass()
        {
            $this->setExpectedException("RuntimeException");
        
            $broker = new \Byron\Broker("Local%s");
        
            $broker->getBarService();
        }
    
        public function testMissingService()
        {
            $this->setExpectedException("BadMethodCallException");
        
            $broker = new \Byron\Broker("Local%s");
        
            $broker->getClemService();
        }
    
        public function testShared()
        {
            $config = new \Byron\Config("fixtures/config.ini");
            $broker = new \Byron\Broker("Local%s", $config);
        
            $this->assertEquals("Hello, World!", $broker->getQuuxService());
        }
    
        public function testNamespacedFormat()
        {
            $broker = new \Byron\Broker("\Local\%s");
        
            $this->assertEquals("qqqqqq", $broker->getBazService());
        }

    }

}
