<?php

namespace Byron\Client; 

class LonelyPlanet extends \Byron\Client
{

    const API = "http://apigateway.lonelyplanet.com/api";

    public function places($name)
    {
        $doc = \Byron\DOM::loadXml($this->GET(self::API . "/places", array("name" => $name)));
        return $doc->toArray("/places/place");
    }

    public function boundingBoxes($n, $s, $e, $w, $type = null)
    {
        $res = $this->GET(
            sprintf("%s/bounding_boxes/%s,%s,%s,%s/pois", self::API, $n, $s, $e, $w), 
            $type ? array("type" => $type) : array()
        );
        $doc = \Byron\DOM::loadXml($res);
        return $doc->toArray("/pois/poi");
    }

    public function aroundere($lat, $lon, $radius = 500, $type = null)
    {
        list($n, $s, $e, $w) = \Byron\Geo::boundingbox($lat, $lon, $radius);
        return $this->boundingBoxes($n, $s, $e, $w, $type);
    }

    public function poi($id) 
    {
        $doc = \Byron\DOM::loadXml($this->GET(sprintf("%s/pois/%s", self::API, $id)));
        return $doc->toArray("/poi");
    }
    
    
}
