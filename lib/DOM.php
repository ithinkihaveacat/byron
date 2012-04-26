<?php

namespace Byron;

class DOM {
    
    /**
     * Takes the filename of a HTML file, returns a proxied DOMDocument object that
     * does everything a DOMDocument does, but with some extra convenience functions.
     *
     * @param string $filename
     * @return \Byron\DOM\DOMDocument Does everything a DOMDocument does, but has additional helper functions
     */

    static function loadHtmlFile($filename) {
        $doc = new \DOMDocument();
        $doc->loadHTMLFile($filename);
        return new DOM\DOMDocument($doc);
    }

    /**
     * Takes an HTML string, returns a proxied DOMDocument object that
     * does everything a DOMDocument does, but with some extra convenience functions.
     *
     * @param string $s XML string
     * @return \Byron\DOM\DOMDocument Does everything a DOMDocument does, but has additional helper functions
     */

    static function loadHtml($s) {
        $doc = new \DOMDocument();
        $doc->loadHTML($s);
        return new DOM\DOMDocument($doc);
    }

    /**
     * Takes the filename of an XML file, returns a proxied DOMDocument object that
     * does everything a DOMDocument does, but with some extra convenience functions.
     *
     * (This is equivalent to load() in the DOMDocument API, but is renamed here for
     * consistency.)
     *
     * @param string $filename
     * @return \Byron\DOM\DOMDocument Does everything a DOMDocument does, but has additional helper functions
     */

    static function loadXmlFile($filename, $transform = false)
    {
        if ($transform) {
            rawlog("transforming...");
            $contents = file_get_contents($filename);
            if ($contents === false) {
                throw new \Exception("can't load [$filename]");
            }
            return self::loadXml($contents, $transform);         
        } else {
            $doc = new \DOMDocument();
            $res = $doc->load($filename);
            if (!$res) {
                throw new \Exception("can't load [$filename]");
            }
            return new DOM\DOMDocument($doc);
        }
    }

    /**
     * Takes an XML string, returns a proxied DOMDocument object that
     * does everything a DOMDocument does, but with some extra convenience functions.
     *
     * @param string $s XML string
     * @return \Byron\DOM\DOMDocument Does everything a DOMDocument does, but has additional helper functions
     */

    static function loadXml($s, $transform = false)
    {
        if ($transform) {
            $s = strtr($s, array(
                "&hellip;" => "&#8230;",
                "&ndash;" => "&#8211;",
                "&mdash;" => "&#8212;",
                "&lsquo;" => "&#8216;",
                "&rsquo;" => "&#8217;",
                "&ldquo;" => "&#8220;",
                "&rdquo;" => "&#8221;"
            ));
        }

        $doc = new \DOMDocument();
        $ret = $doc->loadXML($s);
        return $ret ? new DOM\DOMDocument($doc) : false;
    }

}
