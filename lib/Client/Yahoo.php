<?php

namespace Byron\Client;

class Yahoo extends \Byron\Client {
    
    public function __construct($key)
    {
        $this->setKey($key);
    }

    function termExtraction($text)
    {
        
        $res = $this->POST(
            "http://api.search.yahoo.com/ContentAnalysisService/V1/termExtraction",
            array("appid" => $this->getKey(), "context" => $text, "output" => "json")
        );
        
        $obj = json_decode($res, true);
        return $obj["ResultSet"]["Result"];
        
    }

}
