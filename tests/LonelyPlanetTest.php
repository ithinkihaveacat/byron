<?php

class LonelyPlanetTest extends PHPUnit_Framework_Testcase {
    
    public function testPlaces() {
        
        $lp = new \Byron\Client\LonelyPlanet();
        
        $res = $lp->places("london");

        $this->assertEquals(3, count($res));
        $this->assertEquals("London", $res[0]["short-name"]);
        
    }

    public function testAroundere() {
        
        $lp = new \Byron\Client\LonelyPlanet();

        // http://www.getlatlon.com/
        $res = $lp->aroundere(51.5392827, -0.09769); // N1 2SN
        $this->assertGreaterThan(5, count($res));
        $this->assertEquals("25 Canonbury Lane", $res[0]["name"]);

    }

    public function testPoi() {
        
        $lp = new \Byron\Client\LonelyPlanet();

        $res = $lp->poi(370755); // Screen on the Green

        $this->assertEquals("Screen on the Green", $res[0]["name"]);
        $this->assertGreaterThan(10, strlen($res[0]["review"]));

    }
    
}
