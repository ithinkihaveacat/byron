<?php

/**
 * Simple wrapper around Flickr API services.  (Not complete--only the methods I
 * use are implemented).  The names match the method names as defined in:
 *
 * http://www.flickr.com/services/api/
 */

namespace Byron\Client; 

class Flickr extends \Byron\Client {
    
    const FLICKR_API = "http://www.flickr.com/services/rest/";
    
    public function GET($method, $args = array()) {

        $res = parent::GET(
            self::FLICKR_API, 
            array_merge($args, array("api_key" => $this->getKey(), "method" => $method, "nojsoncallback" => 1, "format" => "json"))
        );
        
        return json_decode($res, true);

    }
    
    public function POST($method, $args = array()) {
        
        $res = parent::POST(
            self::FLICKR_API,
            array_merge($args, array("api_key" => $this->getKey(), "method" => $method, "nojsoncallback" => 1, "format" => "json"))
        );
        
        return json_decode($res, true);

    }
    
    function photos_search($args = array()) {
        
        $res = $this->GET("flickr.photos.search", $args);
        
        return $res;

    }
    
    function tags_trend($tag, $from = null, $to = null, $sample = null) {
        
        if (is_null($to))     { $to   = time();               }
        if (is_null($from))   { $from = $to - 31556926;       } // a year
        if (is_null($sample)) { $sample = ($to - $from) / 19; }
        
        // Adjust the $from date (if necessary) so that the difference between
        // $to and $from is an exact multiple of $sample.
        
        $from = $to - (floor(($to - $from) / $sample) * $sample);
        
        $tag = (array) $tag;
        
        $total = array();
        
        foreach (range($from, $to, $sample) as $t) {
            
            echo gmdate(DATE_ISO8601, $t), "\n";
        
            $res = $this->photos_search(array(
              "tags" => join(",", $tag), "tag_mode" => "all", "per_page" => 1,
              "max_taken_date" => gmstrftime("%Y-%m-%d %H:%M:%S", $t)
            ));
            
            $total[$t] = $res['photos']['total'];
            
        }
        
        return $total;
        
    }
    
    function people_findByUsername($username) {
        
        $res = $this->GET("flickr.people.findByUsername", array("username" => $username));
        
        return $res["stat"] == "ok" ? $res["user"]["nsid"] : null;
        
    }
    
    function tags_getListPhoto($photo_id) {
        
        $res = $this->GET("flickr.tags.getListPhoto", array("photo_id" => $photo_id));
        
        return $res["stat"] == "ok" ? $res["photo"]["tags"]["tag"] : null;
        
    }
    
    /**
     * 
     * @param string $user_id
     * @param mixed $limit if 0 or null, all available photos will be returned
     * @return array
     */
    
    function people_getPublicPhotos($user_id, $limit = 10) {
        
        $max_per_page = 500; // As specified in Flickr API documentation
        
        $per_page = (empty($limit) || ($limit > $max_per_page)) ? $max_per_page : $limit;
        
        $photos = array();
        
        for ($page = 0; empty($limit) || (($page * $per_page) < $limit); $page++) {
            
            $res = $this->GET("flickr.people.getPublicPhotos", array(
                "user_id" => $user_id, 
                "per_page" => $per_page, 
                "page" => $page + 1
            ));
            
            if (empty($res["photos"]["photo"])) {
                return $photos;
            }
            else {
                $photos = array_merge($photos, $res["photos"]["photo"]);
            }

        }
        
        return $photos;
        
    }
    
    function photos_getInfo($photo_id) {
        
        $res = $this->GET("flickr.photos.getInfo", array("photo_id" => $photo_id));
        
        return $res["stat"] == "ok" ? $res["photo"] : null;
                
    }
    
    function photos_getSizes($photo_id) {
        
        $res = $this->GET("flickr.photos.getSizes", array("photo_id" => $photo_id));
        
        return $res["stat"] == "ok" ? $res["sizes"]["size"] : null;
        
    }
    
}

